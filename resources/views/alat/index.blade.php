@extends('template.master')

@section('content')
      <div class="page-inner">

         <!-- NOTIFIKASI SUKSES -->
         @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               {{ session('success') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
         @endif

         <!-- NOTIFIKASI ERROR -->
         @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
               <strong>Terjadi kesalahan!</strong>
               <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                  @endforeach
               </ul>
               <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
         @endif

         <!-- TABEL DATA ALAT -->
         <div class="row">
            <div class="col-12">
               <div class="card card-round shadow-sm">
                  <div class="card-header bg-white py-3">
                     <!-- D-flex dengan gap rapat & justify-content-between agar posisinya rapi ke kiri -->
                     <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        
                        <!-- KIRI: Judul & Search Bar Berdampingan Rapat -->
                        <div class="d-flex align-items-center flex-wrap gap-3">
                           <h4 class="card-title fw-bold text-dark m-0">
                              <i class="fas fa-tools text-primary me-2"></i> Daftar Sparepart & Alat Gudang
                           </h4>

                           <!-- FORM PENCARIAN REAL-TIME -->
                           <div class="input-group" style="width: 240px;">
                              <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                              <input type="text" id="inputSearchAlat" class="form-control bg-light border-start-0 ps-0" placeholder="Cari kode / nama alat...">
                           </div>
                        </div>

                        <!-- KANAN: Tombol Tambah Alat -->
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal"
                           data-bs-target="#modalTambahAlat">
                           <i class="fa fa-plus me-1"></i> Tambah Alat
                        </button>
                     </div>
                  </div>

                  <div class="card-body px-0 pb-0">
                     <div class="table-responsive">
                        <table class="table-striped table-hover mb-0 table align-middle" id="tabelAlat">
                           <thead class="table-dark">
                              <tr>
                                 <th class="ps-4" style="width: 5%">No</th>
                                 <th>Kode Alat</th>
                                 <th>Nama Alat</th>
                                 <th>Kategori</th>
                                 <th>Lokasi Penyimpanan</th>
                                 <th class="text-center">Stok Total</th>
                                 <th class="text-center">Stok Hilang</th>
                                 <th class="text-center">Stok Tersedia</th>
                                 <th style="width: 15%" class="pe-4 text-center">Aksi</th>
                              </tr>
                           </thead>
                           <tbody id="tbodyAlat">
                              @forelse($alat as $index => $item)
                                 <tr data-alat-row class="searchable-row">
                                    <td class="ps-4 nomor-urut">{{ $index + 1 }}</td>
                                    <td><span class="badge bg-dark">{{ $item->kode_alat }}</span></td>
                                    <td class="fw-bold text-primary">{{ $item->nama_alat }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->kategori }}</span></td>
                                    <td><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $item->lokasi_penyimpanan }}</td>
                                    <td class="fw-bold text-center">{{ $item->stok_total }}</td>
                                    <td class="text-danger fw-bold text-center">{{ $item->stok_hilang }}</td>
                                    <td class="text-center"><span class="badge bg-success">{{ $item->stok_tersedia }}</span></td>
                                    <td class="pe-4 text-center">
                                       <!-- TOMBOL EDIT -->
                                       <button class="btn btn-warning btn-sm text-white"
                                          data-bs-toggle="modal"
                                          data-bs-target="#modalEditAlat{{ $item->id }}" title="Edit">
                                          <i class="fa fa-edit"></i>
                                       </button>
                                    </td>
                                 </tr>

                                 <!-- MODAL EDIT ALAT -->
                                 <div class="modal fade" id="modalEditAlat{{ $item->id }}"
                                    tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                       <div class="modal-content">
                                          <div class="modal-header bg-warning text-white">
                                             <h5 class="modal-title fw-bold">Edit Data Alat</h5>
                                             <button type="button" class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                          </div>
                                          <form action="{{ route('alat.update', $item->id) }}"
                                             method="POST">
                                             @csrf
                                             @method('PUT')
                                             <div class="modal-body text-start">
                                                <div class="mb-3">
                                                   <label class="form-label fw-bold">Kode Alat / SKU</label>
                                                   <input type="text" name="kode_alat" class="form-control"
                                                      value="{{ $item->kode_alat }}" required>
                                                </div>
                                                <div class="mb-3">
                                                   <label class="form-label fw-bold">Nama Alat</label>
                                                   <input type="text" name="nama_alat" class="form-control"
                                                      value="{{ $item->nama_alat }}" required>
                                                </div>
                                                <div class="mb-3">
                                                   <label class="form-label fw-bold">Kategori</label>
                                                   <select name="kategori" class="form-select" required>
                                                      <option value="">-- Pilih Kategori --</option>
                                                      @foreach ($kategoriList as $kat)
                                                         <option value="{{ $kat }}"
                                                             {{ $item->kategori == $kat ? 'selected' : '' }}>
                                                             {{ $kat }}
                                                         </option>
                                                      @endforeach
                                                   </select>
                                                </div>
                                                <div class="mb-3">
                                                   <label class="form-label fw-bold">Lokasi Penyimpanan</label>
                                                   <input type="text" name="lokasi_penyimpanan"
                                                      class="form-control" value="{{ $item->lokasi_penyimpanan }}"
                                                      required>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6 mb-3">
                                                      <label class="form-label fw-bold">Stok Total</label>
                                                      <input type="number" name="stok_total" class="form-control"
                                                         value="{{ $item->stok_total }}" min="0" required>
                                                   </div>
                                                   <div class="col-md-6 mb-3">
                                                      <label class="form-label fw-bold">Stok Hilang</label>
                                                      <input type="number" name="stok_hilang"
                                                         class="form-control" value="{{ $item->stok_hilang }}"
                                                         min="0" required>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                   data-bs-dismiss="modal">Batal</button>
                                                <button type="submit"
                                                   class="btn btn-warning fw-bold text-white">Simpan Perubahan</button>
                                             </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              @empty
                                 <tr id="emptyRowDefault">
                                    <td colspan="9" class="text-muted py-5 text-center">
                                       <i class="fas fa-info-circle me-1"></i> Belum ada data alat yang terdaftar.
                                    </td>
                                 </tr>
                              @endforelse

                              <!-- BARIS NOT FOUND JIKA DATA PENCARIAN TIDAK KETEMU -->
                              <tr id="rowNotFound" style="display: none;">
                                 <td colspan="9" class="text-muted py-5 text-center">
                                    <i class="fas fa-search text-warning fs-3 mb-2 d-block"></i>
                                    Pencarian tidak ditemukan. Data alat yang kamu cari tidak ada.
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>

                     <!-- PAGINATION & INFO -->
                     <div class="d-flex justify-content-between align-items-center flex-wrap px-4 py-3 border-top">
                        <div class="info-data text-muted small" id="infoDataAlat">
                           Menampilkan 0 data
                        </div>
                        <nav>
                           <ul class="pagination pagination-sm mb-0" id="paginationAlat">
                           </ul>
                        </nav>
                     </div>

                  </div>
               </div>
            </div>
         </div>

      </div>

   <!-- MODAL TAMBAH ALAT -->
   <div class="modal fade" id="modalTambahAlat" tabindex="-1"
      aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header bg-primary text-white">
               <h5 class="modal-title fw-bold">Tambah Alat Baru</h5>
               <button type="button" class="btn-close"
                  data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('alat.store') }}" method="POST">
               @csrf
               <div class="modal-body text-start">
                  <div class="mb-3">
                     <label class="form-label fw-bold">Kode Alat / SKU</label>
                     <input type="text" name="kode_alat" class="form-control"
                         placeholder="Contoh: ALT-001" required>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-bold">Nama Alat</label>
                     <input type="text" name="nama_alat" class="form-control"
                         placeholder="Contoh: Kunci Pas 12mm" required>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-bold">Kategori</label>
                     <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoriList as $kat)
                           <option value="{{ $kat }}">{{ $kat }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-bold">Lokasi Penyimpanan</label>
                     <input type="text" name="lokasi_penyimpanan" class="form-control"
                         placeholder="Contoh: Rak A-01 / Lemari Besi" required>
                  </div>
                  <div class="row">
                     <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Stok Total</label>
                        <input type="number" name="stok_total" class="form-control"
                            placeholder="0" min="0" required>
                     </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Stok Hilang</label>
                        <input type="number" name="stok_hilang" class="form-control"
                            placeholder="0" min="0" value="0" required>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary"
                     data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
               </div>
            </form>
         </div>
      </div>
   </div>

   <!-- SCRIPT LIVE SEARCH & PAGINATION -->
   <script>
      document.addEventListener('DOMContentLoaded', function () {
          const searchInput = document.getElementById('inputSearchAlat');
          const tbody = document.getElementById('tbodyAlat');
          const pagination = document.getElementById('paginationAlat');
          const infoData = document.getElementById('infoDataAlat');
          const rowNotFound = document.getElementById('rowNotFound');
          const emptyRowDefault = document.getElementById('emptyRowDefault');

          if (!searchInput || !tbody || !pagination || !infoData || !rowNotFound) {
              return;
          }

          const semuaBaris = Array.from(tbody.querySelectorAll('tr[data-alat-row]'));
          const perHalaman = 10; // Batas 10 data per halaman
          let halamanAktif = 1;
          let dataFilter = [...semuaBaris];

          function tampilkanData() {
              const totalData = dataFilter.length;
              const totalHalaman = Math.ceil(totalData / perHalaman);

              if (halamanAktif > totalHalaman && totalHalaman > 0) {
                  halamanAktif = totalHalaman;
              }

              const awal = (halamanAktif - 1) * perHalaman;
              const akhir = awal + perHalaman;

              // Sembunyikan semua baris terlebih dahulu
              semuaBaris.forEach(row => {
                  row.style.display = 'none';
              });

              // Tampilkan data sesuai jangkauan halaman aktif
              const dataTampil = dataFilter.slice(awal, akhir);

              dataTampil.forEach((row, index) => {
                  row.style.display = '';
                  const nomor = row.querySelector('.nomor-urut');
                  if (nomor) {
                      nomor.textContent = awal + index + 1; // Penomoran urut dinamis
                  }
              });

              // Pesan jika pencarian kosong
              rowNotFound.style.display = (totalData === 0 && semuaBaris.length > 0) ? '' : 'none';

              if (emptyRowDefault) {
                  emptyRowDefault.style.display = (semuaBaris.length === 0) ? '' : 'none';
              }

              // Informasi jumlah data
              if (totalData > 0) {
                  infoData.textContent = `Menampilkan ${awal + 1} - ${Math.min(akhir, totalData)} dari ${totalData} data alat`;
              } else {
                  infoData.textContent = 'Menampilkan 0 data alat';
              }

              buatPagination(totalHalaman);
          }

          function buatPagination(totalHalaman) {
              pagination.innerHTML = '';
              if (totalHalaman <= 1) return;

              // Tombol Previous (<)
              tambahTombol('<i class="fas fa-chevron-left"></i>', halamanAktif - 1, halamanAktif === 1);

              // Tombol Angka Halaman
              for (let i = 1; i <= totalHalaman; i++) {
                  tambahTombol(i, i, false, i === halamanAktif);
              }

              // Tombol Next (>)
              tambahTombol('<i class="fas fa-chevron-right"></i>', halamanAktif + 1, halamanAktif === totalHalaman);
          }

          function tambahTombol(label, halaman, disabled, active = false) {
              const li = document.createElement('li');
              li.className = 'page-item';
              if (disabled) li.classList.add('disabled');
              if (active) li.classList.add('active');

              const button = document.createElement('button');
              button.type = 'button';
              button.className = 'page-link';

              if (String(label).includes('fas fa-chevron')) {
                  button.innerHTML = label;
              } else {
                  button.textContent = label;
              }

              button.disabled = disabled;
              button.addEventListener('click', function () {
                  halamanAktif = halaman;
                  tampilkanData();
              });

              li.appendChild(button);
              pagination.appendChild(li);
          }

          // Live Search Event Listener
          searchInput.addEventListener('input', function () {
              const keyword = this.value.toLowerCase().trim();
              dataFilter = semuaBaris.filter(row => {
                  return row.textContent.toLowerCase().includes(keyword);
              });
              halamanAktif = 1; // Kembali ke halaman 1 saat mengetik
              tampilkanData();
          });

          // Inisialisasi awal saat halaman dimuat
          tampilkanData();
      });
   </script>
@endsection