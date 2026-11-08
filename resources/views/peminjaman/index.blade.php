@extends('template.master')

@section('content')
	<div class="page-inner">

		<!-- NOTIFIKASI -->
		@if (session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		@if ($errors->any())
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<ul class="mb-0">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<!-- KOTAK UTAMA -->
		<div class="row">
			<div class="col-12">
				<div class="card card-round shadow-sm">
					<div class="card-header bg-white py-3">
						<div
							class="d-flex align-items-center justify-content-between flex-wrap gap-3">

							<!-- KIRI: Judul, Tombol Filter & Search Bar Berdampingan -->
							<div class="d-flex align-items-center flex-wrap gap-2">
								<h4 class="card-title fw-bold text-dark m-0 me-2">
									<i class="fas fa-dolly-flatbed text-primary me-2"></i> Peminjaman Alat
								</h4>

								<!-- TOMBOL FILTER STATUS -->
								<div class="btn-group btn-group-sm" role="group"
									id="filterStatusGroup">
									<a href="{{ route('peminjaman.index') }}"
										class="btn {{ request('status') == '' ? 'btn-primary active' : 'btn-outline-primary' }} px-3 py-1">Semua</a>

									<a href="{{ route('peminjaman.index', ['status' => 'Dipinjam']) }}"
										class="btn {{ request('status') == 'Dipinjam' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }} px-3 py-1">Masih
										Dipinjam</a>

									<a href="{{ route('peminjaman.index', ['status' => 'Selesai']) }}"
										class="btn {{ request('status') == 'Selesai' ? 'btn-success text-white' : 'btn-outline-success' }} px-3 py-1">Dikembalikan</a>
								</div>

								<!-- FORM PENCARIAN (Bisa dikembangkan / disesuaikan dengan pencarian server jika diperlukan) -->
								<div class="input-group input-group-sm" style="width: 220px;">
									<span class="input-group-text bg-light border-end-0 py-1"><i
											class="fas fa-search text-muted"></i></span>
									<input type="text" id="searchPeminjaman"
										class="form-control bg-light border-start-0 py-1 ps-0"
										placeholder="Cari teks...">
								</div>
							</div>

							<!-- KANAN: Tombol Transaksi Baru -->
							<button class="btn btn-primary btn-round" data-bs-toggle="modal"
								data-bs-target="#modalTambahPinjam">
								<i class="fa fa-plus me-1"></i> Transaksi Baru
							</button>
						</div>
					</div>

					<div class="card-body px-0 pb-0">
						<div class="table-responsive">
							<table class="table-hover tabel-peminjaman mb-0 table align-middle"
								id="tabelPeminjaman">
								<thead class="table-dark text-center">
									<tr>
										<th>NO</th>
										<th>INFORMASI TEKNISI (PEMINJAM)</th>
										<th>ALAT (ITEM)</th>
										<th>WAKTU PINJAM</th>
										<th>WAKTU KEMBALI</th>
										<th>STATUS & CATATAN</th>
										<th>PETUGAS</th>
										<th>AKSI</th>
									</tr>
								</thead>
								<tbody id="tbodyPeminjaman">
									@php
										// Menggunakan nomor urut yang sinkron dengan halaman pagination Laravel
										$no = $peminjaman->firstItem() ?? 1;
									@endphp
									@forelse($peminjaman as $index => $item)
										@php
											$jumlahDetail = $item->details->count();
											$warnaKelompok =
											    $index % 2 == 0 ? 'kelompok-genap' : 'kelompok-ganjil';
										@endphp

										@if ($jumlahDetail > 0)
											@foreach ($item->details as $detail)
												<tr class="kelompok-teknisi {{ $warnaKelompok }} searchable-row"
													data-status="{{ $detail->status_pinjam }}"
													data-search-text="{{ strtolower($item->kode_teknisi . ' ' . $item->nama_teknisi . ' ' . $item->department . ' ' . $item->no_hp . ' ' . $detail->nama_alat . ' ' . $detail->status_pinjam) }}">

													@if ($loop->first)
														<td rowspan="{{ $jumlahDetail }}"
															class="fw-bold info-teknisi nomor-urut text-center">
															{{ $no++ }}
														</td>
														<td rowspan="{{ $jumlahDetail }}" class="info-teknisi align-top">
															<h6 class="fw-bold text-primary mb-2">
																{{ $item->kode_teknisi }} - {{ $item->nama_teknisi }}
															</h6>
															<span
																class="badge {{ $item->jk == 'LK' ? 'bg-info' : 'bg-warning text-dark' }} mb-2">
																{{ $item->jk == 'LK' ? 'Laki-laki' : 'Perempuan' }}
															</span>
															<div class="text-muted"
																style="font-size: 0.85em; line-height: 1.8;">
																<div><i class="fas fa-phone-alt me-1"></i> {{ $item->no_hp }}
																</div>
																<div><i class="fas fa-map-marker-alt me-1"></i>
																	{{ $item->alamat }}</div>
																<div><i class="fas fa-building me-1"></i>
																	{{ $item->department }}</div>
															</div>
															<hr class="my-2">
															<small class="text-muted">
																<b>Petugas Peminjam:</b> {{ $item->nama_petugas ?? 'Admin' }}
															</small>
														</td>
													@endif

													<td class="detail-barang fw-bold">
														{{ $loop->iteration }}. {{ $detail->nama_alat }}
													</td>
													<td class="detail-barang">
														{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y H:i') }}
													</td>
													<td class="detail-barang">
														{{ $detail->tanggal_kembali ? \Carbon\Carbon::parse($detail->tanggal_kembali)->format('d/m/Y H:i') : '-' }}
													</td>
													<td class="detail-barang">
														@if ($detail->status_pinjam == 'Dipinjam')
															<span class="badge bg-warning text-dark">Dipinjam</span>
														@else
															<span
																class="badge {{ $detail->kondisi_kembali == 'Baik' ? 'bg-success' : 'bg-danger' }}">
																Selesai ({{ $detail->kondisi_kembali }})
															</span>
															@if (!empty($detail->catatan))
																<div class="mt-1">
																	<small class="text-muted">
																		<b>Catatan:</b> <span
																			class="fst-italic">{{ $detail->catatan }}</span>
																	</small>
																</div>
															@endif
														@endif
													</td>
													<td class="detail-barang">
														{{ $detail->petugas_kembali ?? '-' }}
													</td>
													<td class="detail-barang text-center">
														@if ($detail->status_pinjam == 'Dipinjam')
															<div class="d-flex justify-content-center gap-2">
																<button type="button"
																	class="btn btn-primary btn-sm fw-bold d-flex flex-column align-items-center px-3 py-1 shadow-sm"
																	data-bs-toggle="modal"
																	data-bs-target="#modalKembali{{ $detail->id }}"
																	title="Kembalikan Alat">
																	<i class="fas fa-undo-alt mb-1" style="font-size: 1.1rem;"></i>
																	<span style="font-size: 0.75rem;">Kembali</span>
																</button>
																<form action="{{ route('peminjaman.destroy', $detail->id) }}"
																	method="POST" class="m-0 p-0"
																	onsubmit="return confirm('Gak jadi pinjam alat ini? Stok akan dikembalikan otomatis.')">
																	@csrf
																	@method('DELETE')
																	<button type="submit"
																		class="btn btn-danger btn-sm fw-bold d-flex flex-column align-items-center px-3 py-1 shadow-sm"
																		title="Batal Pinjam">
																		<i class="fas fa-times mb-1" style="font-size: 1.2rem;"></i>
																		<span style="font-size: 0.75rem;">Batal</span>
																	</button>
																</form>
															</div>
														@else
															<button type="button"
																class="btn btn-warning btn-sm text-dark fw-bold d-inline-flex flex-column align-items-center px-4 py-1 shadow-sm"
																data-bs-toggle="modal"
																data-bs-target="#modalEditKembali{{ $detail->id }}"
																title="Revisi Pengembalian">
																<i class="fas fa-edit mb-1" style="font-size: 1.1rem;"></i>
																<span style="font-size: 0.75rem;">Edit</span>
															</button>
														@endif
													</td>
												</tr>
											@endforeach
										@else
											<tr
												class="kelompok-teknisi {{ $warnaKelompok }} group-start group-end searchable-row"
												data-status="Kosong"
												data-search-text="{{ strtolower($item->kode_teknisi . ' ' . $item->nama_teknisi . ' ' . $item->department) }}">
												<td class="fw-bold info-teknisi nomor-urut text-center">
													{{ $no++ }}
												</td>
												<td class="info-teknisi" colspan="7">
													<h6 class="fw-bold text-primary mb-2">
														{{ $item->kode_teknisi }} - {{ $item->nama_teknisi }}
													</h6>
													<span
														class="badge {{ $item->jk == 'LK' ? 'bg-info' : 'bg-warning text-dark' }}">
														{{ $item->jk == 'LK' ? 'Laki-laki' : 'Perempuan' }}
													</span>
													<div class="text-muted mt-2" style="font-size: 0.85em;">
														<div><i class="fas fa-phone-alt me-1"></i> {{ $item->no_hp }}
														</div>
														<div><i class="fas fa-map-marker-alt me-1"></i>
															{{ $item->alamat }}</div>
														<div><i class="fas fa-building me-1"></i> {{ $item->department }}
														</div>
													</div>
													<hr class="my-2">
													<small class="text-muted">
														<b>Petugas Peminjam:</b> {{ $item->nama_petugas ?? 'Admin' }}
													</small>
													<div class="text-muted mt-2 text-center">Belum ada rincian barang.
													</div>
												</td>
											</tr>
										@endif
									@empty
										<tr id="barisKosong">
											<td colspan="8" class="text-muted py-5 text-center">
												Belum ada transaksi peminjaman.
											</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div>

						<!-- PAGINATION LARAVEL & INFO -->
						<div
							class="d-flex justify-content-between align-items-center border-top flex-wrap px-4 py-3">
							<!-- Info buatan kamu di sebelah kiri (Biarkan tetap ada) -->
							<div class="info-data text-muted small">
								Menampilkan {{ $peminjaman->firstItem() ?? 0 }} -
								{{ $peminjaman->lastItem() ?? 0 }} dari {{ $peminjaman->total() }} data
								peminjaman
							</div>

							<!-- Bagian kanan murni hanya tombol pagination-nya saja -->
							<nav class="pagination-teknisi">
								{{ $peminjaman->links('pagination::bootstrap-5') }}
							</nav>
						</div>

					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- MODAL KEMBALIKAN & EDIT DI LUAR TABEL -->
	@foreach ($peminjaman as $item)
		@foreach ($item->details as $detail)
			@if ($detail->status_pinjam == 'Dipinjam')
				<div class="modal fade" id="modalKembali{{ $detail->id }}"
					tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-sm modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header bg-success text-white">
								<h6 class="modal-title fw-bold">Kembalikan Alat</h6>
								<button type="button" class="btn-close"
									data-bs-dismiss="modal"></button>
							</div>
							<form action="{{ route('peminjaman.update', $detail->id) }}"
								method="POST">
								@csrf
								@method('PUT')
								<input type="hidden" name="jenis_aksi" value="kembalikan">
								<div class="modal-body text-start">
									<p class="mb-2 text-center">Alat: <b>{{ $detail->nama_alat }}</b></p>
									<label class="form-label fw-bold" style="font-size: 0.9em;">Kondisi
										Barang:</label>
									<select name="kondisi_kembali" class="form-select form-select-sm mb-3"
										required>
										<option value="Baik">Baik (Masuk Stok Tersedia)</option>
										<option value="Rusak/Hilang">Rusak/Hilang (Masuk Stok Hilang)</option>
									</select>
									<label class="form-label fw-bold" style="font-size: 0.9em;">Catatan
										(Opsional)
										:</label>
									<textarea name="catatan" class="form-control form-control-sm" rows="2"
									 placeholder="Contoh: Terbakar, patah, jatuh..."></textarea>
								</div>
								<div class="modal-footer justify-content-center p-2">
									<button type="button" class="btn btn-secondary btn-sm"
										data-bs-dismiss="modal">Batal</button>
									<button type="submit"
										class="btn btn-success btn-sm fw-bold">Konfirmasi</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			@endif

			@if ($detail->status_pinjam == 'Selesai')
				<div class="modal fade" id="modalEditKembali{{ $detail->id }}"
					tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-sm modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header bg-warning text-dark">
								<h6 class="modal-title fw-bold">Revisi Pengembalian</h6>
								<button type="button" class="btn-close"
									data-bs-dismiss="modal"></button>
							</div>
							<form action="{{ route('peminjaman.update', $detail->id) }}"
								method="POST">
								@csrf
								@method('PUT')
								<input type="hidden" name="jenis_aksi" value="revisi">
								<div class="modal-body text-start">
									<p class="mb-2 text-center">Alat: <b>{{ $detail->nama_alat }}</b></p>
									<label class="form-label fw-bold" style="font-size: 0.9em;">Revisi
										Kondisi:</label>
									<select name="kondisi_kembali" class="form-select form-select-sm mb-3"
										required>
										<option value="Baik"
											{{ $detail->kondisi_kembali == 'Baik' ? 'selected' : '' }}>Baik
											(Masuk Stok Tersedia)</option>
										<option value="Rusak/Hilang"
											{{ $detail->kondisi_kembali == 'Rusak/Hilang' ? 'selected' : '' }}>
											Rusak/Hilang (Masuk Stok Hilang)</option>
									</select>
									<label class="form-label fw-bold" style="font-size: 0.9em;">Revisi
										Catatan:</label>
									<textarea name="catatan" class="form-control form-control-sm" rows="2">{{ $detail->catatan }}</textarea>
								</div>
								<div class="modal-footer justify-content-center p-2">
									<button type="button" class="btn btn-secondary btn-sm"
										data-bs-dismiss="modal">Tutup</button>
									<button type="submit"
										class="btn btn-warning btn-sm fw-bold text-dark">Simpan
										Revisi</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			@endif
		@endforeach
	@endforeach

	<!-- MODAL TAMBAH TRANSAKSI -->
	<div class="modal fade" id="modalTambahPinjam" tabindex="-1"
		aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title fw-bold">Tambah Peminjaman Baru</h5>
					<button type="button" class="btn-close"
						data-bs-dismiss="modal"></button>
				</div>
				<form action="{{ route('peminjaman.store') }}" method="POST">
					@csrf
					<div class="modal-body text-start">

						<div class="mb-4">
							<label class="form-label fw-bold text-primary">1. Pilih Teknisi
								Peminjam</label>
							<select name="id_teknisi" id="selectTeknisi"
								class="form-select border-primary" required>
								<option value="">-- Ketik / Pilih Nama Teknisi --</option>
								@foreach ($teknisi as $t)
									<option value="{{ $t->id }}">
										{{ $t->id_teknisi }} - {{ $t->nama }} ({{ $t->department }})
									</option>
								@endforeach
							</select>
						</div>

						<div class="mb-3">
							<label class="form-label fw-bold text-primary">2. Pilih Alat (Centang
								yang dipinjam)</label>
							<div class="input-group mb-2">
								<span class="input-group-text bg-white"><i
										class="fas fa-search text-muted"></i></span>
								<input type="text" id="searchAlat" class="form-control"
									placeholder="Cari nama barang atau kategori...">
							</div>
							<div class="table-responsive rounded border"
								style="max-height: 250px; overflow-y: auto;">
								<table class="table-hover table-sm mb-0 table">
									<thead class="table-light sticky-top" style="z-index: 1;">
										<tr>
											<th class="text-center" style="width: 10%">Pilih</th>
											<th>Nama Alat</th>
											<th>Kategori</th>
											<th class="text-center">Sisa Stok</th>
										</tr>
									</thead>
									<tbody id="listAlat">
										@forelse ($alat as $a)
											<tr>
												<td class="text-center align-middle">
													<input class="form-check-input" type="checkbox" name="id_alat[]"
														value="{{ $a->id }}" id="alat{{ $a->id }}"
														style="transform: scale(1.3);">
												</td>
												<td class="align-middle">
													<label for="alat{{ $a->id }}"
														style="cursor: pointer; width: 100%; margin:0;">
														{{ $a->nama_alat }}
													</label>
												</td>
												<td class="text-muted align-middle">
													<small>{{ $a->kategori }}</small>
												</td>
												<td class="fw-bold text-success text-center align-middle">
													{{ $a->stok_tersedia }}</td>
											</tr>
										@empty
											<tr>
												<td colspan="4" class="text-danger py-3 text-center">Semua stok
													alat sedang habis atau dipinjam.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary"
							data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary fw-bold"><i
								class="fas fa-save me-1"></i> Simpan Transaksi</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- CSS & JS Tom Select -->
	<link
		href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css"
		rel="stylesheet">
	<script
		src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js">
	</script>

	<!-- SCRIPT SEDERHANA UNTUK PENCARIAN ALAT DI MODAL -->
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Aktifkan TomSelect untuk Teknisi
			if (document.getElementById('selectTeknisi')) {
				new TomSelect("#selectTeknisi", {
					create: false,
					placeholder: "-- Ketik / Pilih Nama Teknisi --"
				});
			}

			// Search Alat di dalam Modal Tambah
			const searchAlat = document.getElementById('searchAlat');
			if (searchAlat) {
				searchAlat.addEventListener('keyup', function() {
					const searchValue = this.value.toLowerCase();
					document.querySelectorAll('#listAlat tr').forEach(
						row => {
							row.style.display = row.textContent
								.toLowerCase().includes(
									searchValue) ? '' : 'none';
						});
				});
			}
		});
	</script>
@endsection
