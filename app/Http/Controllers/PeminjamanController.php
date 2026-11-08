<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter status filter jika ada (semua, Dipinjam, atau Selesai)
        $statusFilter = $request->get('status', 'semua');

        // 1. Ambil data Induk (Sesi Peminjaman) dengan PAGINATE (misal 5 data per halaman)
        $queryPeminjaman = DB::table('tb_peminjaman')
            ->join('tb_teknisi', 'tb_peminjaman.id_teknisi', '=', 'tb_teknisi.id')
            ->leftJoin('users as u_pinjam', 'tb_peminjaman.id_user_pinjam', '=', 'u_pinjam.id')
            ->select(
                'tb_peminjaman.*',
                'u_pinjam.nama as nama_petugas',
                'tb_teknisi.id_teknisi as kode_teknisi',
                'tb_teknisi.nama as nama_teknisi',
                'tb_teknisi.jk',
                'tb_teknisi.department',
                'tb_teknisi.no_hp',
                'tb_teknisi.alamat'
            );

        // Jika user memilih filter status, saring data induk yang memiliki detail dengan status tersebut
        if ($statusFilter === 'Dipinjam' || $statusFilter === 'Selesai') {
            $queryPeminjaman->whereIn('tb_peminjaman.id', function ($sub) use ($statusFilter) {
                $sub->select('id_peminjaman')
                    ->from('tb_detail_peminjaman')
                    ->where('status_pinjam', $statusFilter);
            });
        }

        // Menggunakan paginate(5) agar rapi dan tidak merusak rowspan
        // withQueryString() agar jika kita klik halaman 2 atau filter, parameter statusnya tidak hilang
        $peminjaman = $queryPeminjaman->orderBy('tb_peminjaman.id', 'DESC')->paginate(5);

        // 2. Ambil data Anak (Detail Barang) dan nama petugas pengembali
        foreach ($peminjaman as $p) {
            $queryDetail = DB::table('tb_detail_peminjaman')
                ->join('tb_alat', 'tb_detail_peminjaman.id_alat', '=', 'tb_alat.id')
                ->leftJoin('users as u_kembali', 'tb_detail_peminjaman.id_user_kembali', '=', 'u_kembali.id')
                ->where('tb_detail_peminjaman.id_peminjaman', $p->id);

            if ($statusFilter === 'Dipinjam' || $statusFilter === 'Selesai') {
                $queryDetail->where('tb_detail_peminjaman.status_pinjam', $statusFilter);
            }

            $p->details = $queryDetail->select('tb_detail_peminjaman.*', 'tb_alat.nama_alat', 'u_kembali.nama as petugas_kembali')->get();
        }

        $teknisi = DB::table('tb_teknisi')->get();
        $alat = DB::table('tb_alat')->where('stok_tersedia', '>', 0)->get();

        return view('peminjaman.index', compact('peminjaman', 'teknisi', 'alat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_teknisi' => 'required|integer',
            'id_alat' => 'required|array',
            'id_alat.*' => 'integer'
        ]);

        DB::beginTransaction();
        try {
            $idPeminjaman = DB::table('tb_peminjaman')->insertGetId([
                'id_user_pinjam' => session('user_id') ?? 1,
                'id_teknisi' => $request->id_teknisi,
                'tanggal_pinjam' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->id_alat as $alatId) {
                DB::table('tb_detail_peminjaman')->insert([
                    'id_peminjaman' => $idPeminjaman,
                    'id_alat' => $alatId,
                    'status_pinjam' => 'Dipinjam',
                    'tanggal_kembali' => null,
                    'kondisi_kembali' => null,
                    'id_user_kembali' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('tb_alat')->where('id', $alatId)->decrement('stok_tersedia', 1);
            }

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $detail = DB::table('tb_detail_peminjaman')->where('id', $id)->first();
        abort_if(!$detail, 404);

        $kondisi = $request->kondisi_kembali ?? 'Baik';
        $catatan = $request->catatan;

        if ($request->jenis_aksi == 'kembalikan') {
            DB::table('tb_detail_peminjaman')->where('id', $id)->update([
                'status_pinjam' => 'Selesai',
                'tanggal_kembali' => now(),
                'kondisi_kembali' => $kondisi,
                'catatan' => $catatan,
                'id_user_kembali' => session('user_id') ?? 1,
                'updated_at' => now(),
            ]);

            if ($kondisi == 'Baik') {
                DB::table('tb_alat')->where('id', $detail->id_alat)->increment('stok_tersedia', 1);
            } else {
                DB::table('tb_alat')->where('id', $detail->id_alat)->increment('stok_hilang', 1);
            }

            return redirect()->back()->with('success', 'Alat berhasil dikembalikan.');
        } elseif ($request->jenis_aksi == 'revisi') {
            $kondisi_lama = $detail->kondisi_kembali;
            if ($kondisi_lama != $kondisi) {
                if ($kondisi == 'Baik') {
                    DB::table('tb_alat')->where('id', $detail->id_alat)->decrement('stok_hilang', 1);
                    DB::table('tb_alat')->where('id', $detail->id_alat)->increment('stok_tersedia', 1);
                } else {
                    DB::table('tb_alat')->where('id', $detail->id_alat)->decrement('stok_tersedia', 1);
                    DB::table('tb_alat')->where('id', $detail->id_alat)->increment('stok_hilang', 1);
                }
            }

            DB::table('tb_detail_peminjaman')->where('id', $id)->update([
                'kondisi_kembali' => $kondisi,
                'catatan' => $catatan,
                'id_user_kembali' => session('user_id') ?? 1,
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Revisi pengembalian berhasil disimpan!');
        }
    }

    public function destroy($id)
    {
        $detail = DB::table('tb_detail_peminjaman')->where('id', $id)->first();
        abort_if(!$detail, 404);

        DB::table('tb_alat')->where('id', $detail->id_alat)->increment('stok_tersedia', 1);
        DB::table('tb_detail_peminjaman')->where('id', $id)->delete();

        $sisa_detail = DB::table('tb_detail_peminjaman')->where('id_peminjaman', $detail->id_peminjaman)->count();
        if ($sisa_detail == 0) {
            DB::table('tb_peminjaman')->where('id', $detail->id_peminjaman)->delete();
        }

        return redirect()->back()->with('success', 'Peminjaman alat dibatalkan.');
    }
}