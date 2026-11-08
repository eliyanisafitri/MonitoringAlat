@extends('template.master')

@section('content')
	<div class="page-inner" style="width: 100% !important;">

		<!-- HEADER / SELAMAT DATANG -->
		<div
			class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-4 pt-2">
			<div>
				<h3 class="fw-bold mb-3">Dashboard Gudang</h3>
				<h6 class="op-7 mb-2">Sistem Monitoring Peminjaman Alat
				</h6>
			</div>
			<div class="ms-md-auto py-md-0 py-2">
				<a href="{{ route('peminjaman.index') }}"
					class="btn btn-primary btn-round shadow-sm">
					<i class="fas fa-dolly-flatbed me-1"></i> Kelola Peminjaman
				</a>
			</div>
		</div>

		<!-- KOTAK STATISTIK (4 CARD RINGKASAN STOK) -->
		<div class="row">
			<!-- Card 1: Stok Total -->
			<div class="col-sm-6 col-md-3 mb-4">
				<div class="card card-stats card-round border-0 shadow-sm"
					style="border-left: 5px solid #1572e8 !important;">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-icon">
								<!-- Ubah jadi bg-primary text-white -->
								<div
									class="icon-big rounded-circle bg-primary p-3 text-center text-white"
									style="box-shadow: 0 4px 15px rgba(21, 114, 232, 0.4) !important;">
									<i class="fas fa-boxes fa-2x"></i>
								</div>
							</div>
							<div class="col col-stats ms-3">
								<div class="numbers">
									<p class="card-category fw-bold text-uppercase mb-1"
										style="font-size: 0.75rem; letter-spacing: 1px; color: #8b929a;">STOK
										TOTAL</p>
									<h3 class="card-title fw-bolder text-dark mb-0"
										style="font-size: 1.8rem;">{{ $totalStok ?? 0 }}</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Card 2: Tersedia -->
			<div class="col-sm-6 col-md-3 mb-4">
				<div class="card card-stats card-round border-0 shadow-sm"
					style="border-left: 5px solid #31ce36 !important;">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-icon">
								<!-- Ubah jadi bg-success text-white -->
								<div
									class="icon-big rounded-circle bg-success p-3 text-center text-white"
									style="box-shadow: 0 4px 15px rgba(49, 206, 54, 0.4) !important;">
									<i class="fas fa-check-circle fa-2x"></i>
								</div>
							</div>
							<div class="col col-stats ms-3">
								<div class="numbers">
									<p class="card-category fw-bold text-uppercase mb-1"
										style="font-size: 0.75rem; letter-spacing: 1px; color: #8b929a;">
										TERSEDIA</p>
									<h3 class="card-title fw-bolder mb-0"
										style="color: #31ce36; font-size: 1.8rem;">{{ $totalTersedia ?? 0 }}
									</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Card 3: Sedang Dipinjam -->
			<div class="col-sm-6 col-md-3 mb-4">
				<div class="card card-stats card-round border-0 shadow-sm"
					style="border-left: 5px solid #ffad46 !important;">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-icon">
								<!-- Ubah jadi bg-warning text-white -->
								<div
									class="icon-big rounded-circle bg-warning p-3 text-center text-white"
									style="box-shadow: 0 4px 15px rgba(255, 173, 70, 0.4) !important;">
									<i class="fas fa-dolly fa-2x"></i>
								</div>
							</div>
							<div class="col col-stats ms-3">
								<div class="numbers">
									<p class="card-category fw-bold text-uppercase mb-1"
										style="font-size: 0.75rem; letter-spacing: 1px; color: #8b929a;">SEDANG
										DIPINJAM</p>
									<h3 class="card-title fw-bolder mb-0"
										style="color: #ffad46; font-size: 1.8rem;">{{ $sedang_dipinjam ?? 0 }}
									</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Card 4: Hilang / Rusak -->
			<div class="col-sm-6 col-md-3 mb-4">
				<div class="card card-stats card-round border-0 shadow-sm"
					style="border-left: 5px solid #f25961 !important;">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-icon">
								<!-- Ubah jadi bg-danger text-white -->
								<div class="icon-big rounded-circle bg-danger p-3 text-center text-white"
									style="box-shadow: 0 4px 15px rgba(242, 89, 97, 0.4) !important;">
									<i class="fas fa-exclamation-triangle fa-2x"></i>
								</div>
							</div>
							<div class="col col-stats ms-3">
								<div class="numbers">
									<p class="card-category fw-bold text-uppercase mb-1"
										style="font-size: 0.75rem; letter-spacing: 1px; color: #8b929a;">HILANG
										/ RUSAK</p>
									<h3 class="card-title fw-bolder mb-0"
										style="color: #f25961; font-size: 1.8rem;">{{ $totalHilang ?? 0 }}</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- BAGIAN GRAFIK (GARIS & DONAT STATUS) -->
		<div class="row mt-2">
			<!-- Grafik Garis Aktivitas -->
			<div class="col-md-7">
				<div class="card card-round shadow-sm">
					<div class="card-header bg-white py-3">
						<div class="card-head-row">
							<div class="card-title fw-bold">
								<i class="fas fa-chart-line text-primary me-2"></i> Statistik Aktivitas
								Peminjaman
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="chart-container" style="min-height: 250px">
							<canvas id="statistikPeminjamanChart"></canvas>
						</div>
					</div>
				</div>
			</div>

			<!-- Grafik Donat Status Peminjaman -->
			<div class="col-md-5">
				<div class="card card-round shadow-sm">
					<div class="card-header bg-white py-3">
						<div class="card-title fw-bold">
							<i class="fas fa-chart-pie text-success me-2"></i> Status Peminjaman Alat
						</div>
					</div>
					<div class="card-body">
						<div class="chart-container" style="min-height: 250px">
							<canvas id="donatPeminjamanChart"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- BAGIAN PERINGATAN: ALAT TERLAMBAT LEWAT DARI 3 HARI -->
		<div class="row mt-2">
			<div class="col-12">
				<div class="card card-round border-danger shadow-sm">
					<div class="card-header bg-danger py-3 text-white">
						<h4 class="card-title fw-bold m-0 text-white">
							<i class="fas fa-clock me-2"></i> Perhatian: Alat yang Belum Kembali Lewat
							dari 3 Hari
						</h4>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table-hover mb-0 table align-middle">
								<thead class="table-light">
									<tr>
										<th class="text-center" style="width: 5%">NO</th>
										<th style="width: 30%">NAMA ALAT</th>
										<th style="width: 25%">DIPINJAM OLEH (TEKNISI)</th>
										<th style="width: 15%">TANGGAL PINJAM</th>
										<th class="text-center" style="width: 15%">KETERANGAN</th>
										<th class="text-center" style="width: 10%">AKSI</th>
									</tr>
								</thead>
								<tbody>
									@forelse($list_terlambat as $index => $item)
										<tr>
											<td class="fw-bold text-center">{{ $index + 1 }}</td>
											<td class="fw-bold text-danger">{{ $item->nama_alat }}</td>
											<td><span
													class="badge bg-info text-dark">{{ $item->nama_teknisi }}</span>
											</td>
											<td>{{ date('d-m-Y', strtotime($item->tanggal_pinjam)) }}</td>
											<td class="text-center">
												<span class="badge bg-danger">Terlambat
													({{ \Carbon\Carbon::parse($item->tanggal_pinjam)->diffInDays(now()) }}
													Hari)
												</span>
											</td>
											<td class="text-center">
												<a href="{{ route('peminjaman.index') }}"
													class="btn btn-sm btn-outline-primary fw-bold">
													<i class="fas fa-check-circle me-1"></i> Proses
												</a>
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="6" class="text-muted py-4 text-center">
												<i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
												Aman! Tidak ada alat yang terlambat dikembalikan lewat dari 3 hari.
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

		<!-- BAGIAN BAWAH: PERINGATAN STOK MENIPIS -->
		<div class="row mt-2">
			<div class="col-12">
				<div class="card card-round shadow-sm">
					<div class="card-header bg-white py-3">
						<h4 class="card-title fw-bold text-dark m-0">
							<i class="fas fa-exclamation-triangle text-warning me-2"></i> Peringatan
							Stok Alat Menipis (Tersedia ≤ 2)
						</h4>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table-hover mb-0 table align-middle">
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
											<td class="fw-bold text-center">{{ $index + 1 }}</td>
											<td class="fw-bold text-danger">{{ $alat->nama_alat }}</td>
											<td>{{ $alat->kategori ?? '-' }}</td>
											<td class="text-center">
												<span class="badge bg-danger fs-6">{{ $alat->stok_tersedia }}
													Unit</span>
											</td>
											<td class="text-center">
												<a href="{{ route('alat.index') }}"
													class="btn btn-sm btn-outline-primary fw-bold">
													<i class="fas fa-edit me-1"></i> Kelola Stok
												</a>
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="5" class="text-muted py-4 text-center">
												<i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
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

	<!-- SCRIPT UNTUK MENAMPILKAN GRAFIK (CHART.JS) -->
	@push('scripts')
		<script>
			// 1. Grafik Garis (Statistik Aktivitas Peminjaman)
			var ctx = document.getElementById('statistikPeminjamanChart').getContext('2d');
			var statistikPeminjamanChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu',
						'Minggu'
					],
					datasets: [{
						label: 'Jumlah Peminjaman Alat',
						data: {!! json_encode($aktivitas_chart ?? [0, 0, 0, 0, 0, 0, 0]) !!},
						borderColor: '#177dff',
						backgroundColor: 'rgba(23, 125, 255, 0.1)',
						borderWidth: 3,
						fill: true,
						tension: 0.4
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							display: true,
							position: 'top'
						}
					},
					scales: {
						y: {
							beginAtZero: true,
							ticks: {
								stepSize: 1
							},
							grid: {
								color: '#f2f2f2'
							}
						},
						x: {
							grid: {
								display: false
							}
						}
					}
				}
			});

			// 2. Grafik Donat (Status Peminjaman)
			var ctxDonat = document.getElementById('donatPeminjamanChart').getContext(
				'2d');
			var donatPeminjamanChart = new Chart(ctxDonat, {
				type: 'doughnut',
				data: {
					labels: ['Sudah Selesai', 'Sedang Dipinjam',
						'Terlambat (>3 Hari)'
					],
					datasets: [{
						data: [
							{{ $status_selesai_count ?? 0 }},
							{{ $status_dipinjam_count ?? 0 }},
							{{ $alat_terlambat_count ?? 0 }}
						],
						backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
						borderWidth: 2
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							position: 'bottom'
						}
					}
				}
			});
		</script>
	@endpush
@endsection
