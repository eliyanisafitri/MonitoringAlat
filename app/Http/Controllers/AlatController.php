<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlatController extends Controller
{
    // MENAMPILKAN DATA ALAT (Dilengkapi Search & Pertahankan Enum Kategori)
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Mengambil data alat dengan filter pencarian jika ada
        $alat = DB::table('tb_alat')
            ->when($search, function($query, $search) {
                return $query->where('nama_alat', 'like', "%{$search}%")
                             ->orWhere('kode_alat', 'like', "%{$search}%")
                             ->orWhere('kategori', 'like', "%{$search}%");
            })
            ->orderBy('id', 'DESC')
            ->get();

        // Mengambil definisi ENUM dari kolom kategori
        $column = DB::select("SHOW COLUMNS FROM tb_alat WHERE Field = 'kategori'");

        $kategoriList = [];

        if (!empty($column)) {
            preg_match('/^enum\((.*)\)$/', $column[0]->Type, $matches);

            if (isset($matches[1])) {
                $kategoriList = str_getcsv($matches[1], ',', "'");
            }
        }

        return view('alat.index', compact('alat', 'kategoriList', 'search'));
    }

    public function create()
    {
        return redirect()->route('alat.index');
    }

    // MENYIMPAN DATA ALAT BARU (hitung stok)
    public function store(Request $request)
    {
        $request->validate([
            'kode_alat' => 'required|string|max:50|unique:tb_alat,kode_alat',
            'nama_alat' => 'required',
            'kategori' => 'required',
            'lokasi_penyimpanan' => 'required',
            'stok_total' => 'required|integer|min:0',
            'stok_hilang' => 'required|integer|min:0',
        ], [
            'kode_alat.unique' => 'Gagal! Kode alat ini sudah terdaftar.',
        ]);

        // 1. Stok hilang tidak boleh lebih besar dari stok total
        if ($request->stok_hilang > $request->stok_total) {
            return redirect()->back()->withErrors(['stok_hilang' => 'Gagal! Stok hilang tidak boleh melebihi jumlah stok total.'])->withInput();
        }

        // 2. Hitung stok tersedia secara otomatis 
        $stok_tersedia = $request->stok_total - $request->stok_hilang;

        // 3. Pagar pengaman tambahan jaga-jaga kalau hasilnya minus
        if ($stok_tersedia < 0) {
            return redirect()->back()->withErrors(['error' => 'Gagal! Stok tersedia tidak boleh bernilai minus.'])->withInput();
        }

        DB::table('tb_alat')->insert([
            'kode_alat' => strtoupper($request->kode_alat),
            'nama_alat' => $request->nama_alat,
            'kategori' => $request->kategori,
            'lokasi_penyimpanan' => $request->lokasi_penyimpanan,
            'stok_total' => $request->stok_total,
            'stok_hilang' => $request->stok_hilang,
            'stok_tersedia' => $stok_tersedia,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('alat.index')
            ->with('success', 'Data alat berhasil ditambahkan!');
    }
    public function show(string $id)
    {
        return redirect()->route('alat.index');
    }

    public function edit(string $id)
    {
        return redirect()->route('alat.index');
    }

    // MEMPERBARUI DATA ALAT (validasi unik kode_alat saat update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_alat' => 'required|string|max:50|unique:tb_alat,kode_alat,' . $id,
            'nama_alat' => 'required',
            'kategori' => 'required',
            'lokasi_penyimpanan' => 'required',
            'stok_total' => 'required|integer|min:0',
            'stok_hilang' => 'required|integer|min:0',
        ], [
            'kode_alat.unique' => 'Gagal! Kode alat ini sudah digunakan.',
        ]);

        // 1. Cek berapa banyak alat ini yang statusnya masih dibawa (Dipinjam) oleh teknisi
        $sedang_dipinjam = DB::table('tb_detail_peminjaman')
            ->where('id_alat', $id)
            ->where('status_pinjam', 'Dipinjam')
            ->count();

        // 2. Gunakan rumus absolut untuk mencari stok tersedia yang baru (Memperbaiki tanda minus)
        $stok_tersedia_baru = $request->stok_total - $sedang_dipinjam - $request->stok_hilang;

        // 3. Validasi: Jangan biarkan simpan kalau hitungannya minus
        if ($stok_tersedia_baru < 0) {
            return redirect()->back()->withErrors([
                'error' => 'Data tidak masuk akal! Saat ini ada ' . $sedang_dipinjam . ' alat yang sedang dipinjam teknisi.'
            ])->withInput();
        }

        // 4. Jika aman, update database tb_alat (Ditambahkan kode_alat & lokasi)
        DB::table('tb_alat')->where('id', $id)->update([
            'kode_alat' => strtoupper($request->kode_alat),
            'nama_alat' => $request->nama_alat,
            'kategori' => $request->kategori,
            'lokasi_penyimpanan' => $request->lokasi_penyimpanan,
            'stok_total' => $request->stok_total,
            'stok_hilang' => $request->stok_hilang,
            'stok_tersedia' => $stok_tersedia_baru,
            'updated_at' => now(),
        ]);

        return redirect()->route('alat.index')->with('success', 'Data alat & stok berhasil diperbarui otomatis!');
    }
}