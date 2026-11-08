<?php

namespace App\Http\Controllers;

use Carbon\Carbon;  // <-- Pastikan Carbon di-import di atas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session('is_login')) {
            return redirect('/')->with('error', 'Anda harus login terlebih dahulu!');
        }

        // Statistik Ringkasan Stok (Akumulasi angka stok dari tabel tb_alat)
        $totalStok = DB::table('tb_alat')->sum('stok_total');
        $totalTersedia = DB::table('tb_alat')->sum('stok_tersedia');
        $totalHilang = DB::table('tb_alat')->sum('stok_hilang');

        // Total item detail peminjaman yang statusnya masih 'Dipinjam'
        $sedang_dipinjam = DB::table('tb_detail_peminjaman')->where('status_pinjam', 'Dipinjam')->count();

        $total_teknisi = DB::table('tb_teknisi')->count();

        $status_dipinjam_count = $sedang_dipinjam;
        $status_selesai_count = DB::table('tb_detail_peminjaman')->where('status_pinjam', 'Selesai')->count();

        $alat_menipis = DB::table('tb_alat')->where('stok_tersedia', '<=', 2)->get();

        $list_terlambat = DB::table('tb_detail_peminjaman')
            ->join('tb_alat', 'tb_detail_peminjaman.id_alat', '=', 'tb_alat.id')
            ->join('tb_peminjaman', 'tb_detail_peminjaman.id_peminjaman', '=', 'tb_peminjaman.id')
            ->join('tb_teknisi', 'tb_peminjaman.id_teknisi', '=', 'tb_teknisi.id')
            ->where('tb_detail_peminjaman.status_pinjam', 'Dipinjam')
            ->where('tb_peminjaman.tanggal_pinjam', '<=', now()->subDays(3)->toDateString())
            ->select('tb_detail_peminjaman.*', 'tb_alat.nama_alat', 'tb_teknisi.nama as nama_teknisi', 'tb_peminjaman.tanggal_pinjam')
            ->get();

        $alat_terlambat_count = $list_terlambat->count();
        $startOfWeek = Carbon::now()->startOfWeek();  // Senin pukul 00:00:00
        $endOfWeek = Carbon::now()->endOfWeek();  // Minggu pukul 23:59:59
        $aktivitas_chart = [];

        // Looping dari hari Senin ($i = 0) sampai Minggu ($i = 6) di minggu ini
        for ($i = 0; $i < 7; $i++) {
            $tanggal = (clone $startOfWeek)->addDays($i)->toDateString();

            $count = DB::table('tb_peminjaman')
                ->whereDate('tanggal_pinjam', $tanggal)
                ->count();

            $aktivitas_chart[] = $count;
        }

        return view('dashboard', compact(
            'totalStok',
            'totalTersedia',
            'totalHilang',
            'sedang_dipinjam',
            'total_teknisi',
            'alat_menipis',
            'alat_terlambat_count',
            'list_terlambat',
            'status_dipinjam_count',
            'status_selesai_count',
            'aktivitas_chart'
        ));
    }
}
