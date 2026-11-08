@extends('template.master')

@section('content')
<div class="page-inner" style="width: 100% !important;">

    <!-- HEADER / SELAMAT DATANG -->
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-4 pt-2">
        <div>
            <h3 class="fw-bold mb-3">Dashboard Gudang Pak Anto</h3>
            <h6 class="op-7 mb-2">Sistem Monitoring Inventaris & Peminjaman Alat Lapangan</h6>
        </div>
        <div class="ms-md-auto py-md-0 py-2">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-primary btn-round">
                <i class="fas fa-dolly-flatbed me-1"></i> Kelola Peminjaman
            </a>
        </div>
    </div>

    <!-- KOTAK STATISTIK (WIDGET ANGKA) -->
    <div class="row">
        <!-- Card 1: Total Jenis Alat -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big icon-primary bubble-shadow-small text-center">
                                <i class="fas fa-tools"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-sm-0 ms-3">
                            <div class="numbers">
                                <p class="card-category">Total Jenis Alat</p>
                                <h4 class="card-title">{{ $total_alat }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Sedang Dipinjam -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big icon-warning bubble-shadow-small text-center">
                                <i class="fas fa-hand-holding"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-sm-0 ms-3">
                            <div class="numbers">
                                <p class="card-category">Sedang Dipinjam</p>
                                <h4 class="card-title">{{ $sedang_dipinjam }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Teknisi -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big icon-success bubble-shadow-small text-center">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-sm-0 ms-3">
                            <div class="numbers">
                                <p class="card-category">Total Teknisi</p>
                                <h4 class="card-title">{{ $total_teknisi }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BAGIAN BAWAH: PERINGATAN STOK MENIPIS -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-round shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="card-title fw-bold text-dark m-0">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i> Peringatan Stok Alat Menipis (Tersedia ≤ 2)
                    </h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 5%">NO</th>
                                    <th style="width: 40%">NAMA ALAT</th>
                                    <th style="width: 20%">KATEGORI</th>
                                    <th class="text-center" style="width: 15%">STOK TERSEDIA</th>
                                    <th class="text-center" style="width: 20%">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alat_menipis as $index => $alat)
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-danger">{{ $alat->nama_alat }}</td>
                                    <td>{{ $alat->kategori }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger fs-6">{{ $alat->stok_tersedia }} Unit</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('alat.index') }}" class="btn btn-sm btn-outline-primary fw-bold">
                                            <i class="fas fa-edit me-1"></i> Kelola Stok
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-check-circle text-success fs-3 mb-2 d-block"></i>
                                        Aman! Tidak ada alat yang stoknya menipis saat ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection