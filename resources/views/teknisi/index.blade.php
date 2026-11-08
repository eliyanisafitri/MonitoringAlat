@extends('template.master')

@section('content')
    <div class="page-inner">

        {{-- NOTIFIKASI SUKSES --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- NOTIFIKASI ERROR VALIDASI --}}
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

        {{-- TABEL DATA TEKNISI --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-round shadow-sm">

                    <div class="card-header bg-white py-3">
                        <!-- D-flex dengan gap rapat & justify-content-between agar posisinya rapi ke kiri -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            
                            <!-- KIRI: Judul & Search Bar Berdampingan Rapat -->
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <h4 class="card-title fw-bold text-dark m-0">
                                    <i class="fas fa-users-cog text-primary me-2"></i> Daftar Teknisi Gudang
                                </h4>

                                <!-- FORM PENCARIAN REAL-TIME -->
                                <div class="input-group" style="width: 240px;">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" id="searchTeknisi" class="form-control bg-light border-start-0 ps-0" placeholder="Cari ID, nama, department...">
                                </div>
                            </div>

                            <!-- KANAN: Tombol Tambah Teknisi -->
                            <button class="btn btn-primary btn-round" data-bs-toggle="modal"
                                data-bs-target="#modalTambah">
                                <i class="fa fa-plus me-1"></i> Tambah Teknisi
                            </button>
                        </div>
                    </div>

                    <div class="card-body px-0 pb-0">
                        {{-- TABEL --}}
                        <div class="table-responsive">
                            <table class="table-striped table-hover mb-0 table align-middle table-teknisi" id="tabelTeknisi">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="ps-4" style="width: 5%">No</th>
                                        <th>ID Teknisi</th>
                                        <th>Nama Teknisi</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Department</th>
                                        <th>No HP</th>
                                        <th>Alamat</th>
                                        <th style="width: 15%" class="pe-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyTeknisi">
                                    @forelse($teknisi as $index => $item)
                                        <tr data-teknisi-row class="searchable-row">
                                            <td class="ps-4 nomor-urut">{{ $index + 1 }}</td>
                                            <td class="fw-bold text-primary">{{ $item->id_teknisi }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>
                                                <span class="badge {{ $item->jk == 'LK' ? 'bg-info' : 'bg-warning text-dark' }}">
                                                    {{ $item->jk == 'LK' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>
                                            </td>
                                            <td>{{ $item->department }}</td>
                                            <td>{{ $item->no_hp }}</td>
                                            <td>{{ $item->alamat }}</td>
                                            <td class="pe-4 text-center">
                                                {{-- TOMBOL EDIT --}}
                                                <button class="btn btn-warning btn-sm text-white"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEdit{{ $item->id }}"
                                                    title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                {{-- FORM HAPUS --}}
                                                <form action="{{ route('teknisi.destroy', $item->id) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus data teknisi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="barisKosong">
                                            <td colspan="8" class="text-muted py-5 text-center">
                                                <i class="fas fa-info-circle me-1"></i> Belum ada data teknisi yang terdaftar.
                                            </td>
                                        </tr>
                                    @endforelse

                                    {{-- PESAN DATA TIDAK DITEMUKAN (Not Found) --}}
                                    <tr id="tidakAdaHasil" style="display: none;">
                                        <td colspan="8" class="text-muted py-5 text-center">
                                            <i class="fas fa-search text-warning fs-3 mb-2 d-block"></i>
                                            Pencarian tidak ditemukan. Data teknisi yang kamu cari tidak ada.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION & INFO --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap px-4 py-3 border-top">
                            <div class="info-data text-muted small" id="infoDataTeknisi">
                                Menampilkan 0 data
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="paginationTeknisi">
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT TEKNISI --}}
    @foreach($teknisi as $item)
        <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title fw-bold">Edit Data Teknisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('teknisi.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body text-start">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ID Teknisi</label>
                                <input type="text" name="id_teknisi" class="form-control" value="{{ $item->id_teknisi }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Teknisi</label>
                                <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <select name="jk" class="form-select" required>
                                    <option value="LK" {{ $item->jk == 'LK' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="PR" {{ $item->jk == 'PR' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Department</label>
                                <input type="text" name="department" class="form-control" value="{{ $item->department }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">No HP</label>
                                <input type="text" name="no_hp" class="form-control" value="{{ $item->no_hp }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <input type="text" name="alamat" class="form-control" value="{{ $item->alamat }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning fw-bold text-white">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL TAMBAH TEKNISI --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Tambah Teknisi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('teknisi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ID Teknisi</label>
                            <input type="text" name="id_teknisi" class="form-control" placeholder="Contoh: TKN-001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Teknisi</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Teknisi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jk" class="form-select" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="LK">Laki-laki</option>
                                <option value="PR">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Department Teknisi</label>
                            <input type="text" name="department" class="form-control" placeholder="Masukkan Department" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <input type="text" name="alamat" class="form-control" placeholder="Masukkan Alamat Teknisi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LIVE SEARCH & PAGINATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchTeknisi');
            const tbody = document.getElementById('tbodyTeknisi');
            const pagination = document.getElementById('paginationTeknisi');
            const infoData = document.getElementById('infoDataTeknisi');
            const tidakAdaHasil = document.getElementById('tidakAdaHasil');
            const barisKosong = document.getElementById('barisKosong');

            if (!searchInput || !tbody || !pagination || !infoData || !tidakAdaHasil) {
                return;
            }

            const semuaBaris = Array.from(tbody.querySelectorAll('tr[data-teknisi-row]'));
            const perHalaman = 10; // 5 data per halaman
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
                        nomor.textContent = awal + index + 1; // Nomor urut dinamis
                    }
                });

                // Tampilkan pesan "Tidak Ditemukan" jika hasil pencarian kosong
                tidakAdaHasil.style.display = (totalData === 0 && semuaBaris.length > 0) ? '' : 'none';

                if (barisKosong) {
                    barisKosong.style.display = (semuaBaris.length === 0) ? '' : 'none';
                }

                // Teks informasi jumlah data
                if (totalData > 0) {
                    infoData.textContent = `Menampilkan ${awal + 1} - ${Math.min(akhir, totalData)} dari ${totalData} data teknisi`;
                } else {
                    infoData.textContent = 'Menampilkan 0 data teknisi';
                }

                buatPagination(totalHalaman);
            }

            function buatPagination(totalHalaman) {
                pagination.innerHTML = '';
                if (totalHalaman <= 1) return;

                // Tombol Previous
                tambahTombol('<i class="fas fa-chevron-left"></i>', halamanAktif - 1, halamanAktif === 1);

                // Tombol Angka Halaman
                for (let i = 1; i <= totalHalaman; i++) {
                    tambahTombol(i, i, false, i === halamanAktif);
                }

                // Tombol Next
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

            // Fitur Live Search
            searchInput.addEventListener('input', function () {
                const keyword = this.value.toLowerCase().trim();
                dataFilter = semuaBaris.filter(row => {
                    return row.textContent.toLowerCase().includes(keyword);
                });
                halamanAktif = 1; // Kembali ke halaman 1 setiap kali mengetik
                tampilkanData();
            });

            // Inisialisasi pertama kali
            tampilkanData();
        });
    </script>
@endsection