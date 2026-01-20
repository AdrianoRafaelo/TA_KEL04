@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Beranda</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Banner Section -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="gambar" style="background-image:url('/img/panel%20surya.jpeg')">
          <div class="banner-text">Peran Manajemen Rekayasa Dalam Peningkatan Energi Terbarukan</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="gambar" style="background-image:url('/img/panel%20surya.jpeg')">
          <div class="banner-text">Peraturan Pemerintah Melalui Gerakan Hijau</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="gambar" style="background-image:url('/img/wind turbine.jpg')">
          <div class="banner-text">Peningkatan Kualitas Pendidikan Teknik Mesin</div>
        </div>
      </div>
    </div>
    <div class="row">
        <!-- KOLOM KIRI: Informasi -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3">Informasi</h6>

                    @foreach($pengumuman as $kategori => $items)
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">{{ $kategori }}</h6>
                        @forelse($items as $item)
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <p class="small text-muted mb-1">
                                    <strong>{{ $item->judul }}</strong> {{ Str::limit($item->deskripsi, 100) }}
                                </p>
                            </div>
                            @if (session('role') === 'Koordinator')
                            <div class="btn-group btn-group-sm ms-2" role="group">
                                <button class="btn btn-outline-warning btn-sm" onclick="editInformasi({{ $item->id }}, '{{ $item->judul }}', '{{ $item->kategori }}', '{{ addslashes($item->deskripsi) }}')">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteInformasi({{ $item->id }}, '{{ $item->judul }}')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        @empty
                        <p class="small text-muted">Belum ada informasi {{ strtolower($kategori) }}.</p>
                        @endforelse
                    </div>
                    @if(!$loop->last)
                    <hr>
                    @endif
                    @endforeach

                    @if (session('role') === 'Koordinator')
                    <a href="#" class="text-success small fw-medium" data-bs-toggle="modal" data-bs-target="#tambahInformasiModal">+ Tambah Informasi</a>
                    @endif
                </div>
            </div>
        </div>

         @if (session('role') === 'Koordinator')
        <div class="col-lg-8">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius: 12px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#kpGroupsModal">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 10px;">
                            <i class="mdi mdi-account-group" style="font-size: 2rem; color: #007bff;"></i>
                            <h6 class="fw-bold mb-0">Kelompok Kerja Praktik Mahasiswa</h6>
                        </div>
                        <p class="small text-muted">Mahasiswa mendaftar pada perusahaan KP</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius: 12px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#perusahaanModal">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 10px;">
                            <i class="mdi mdi-office-building" style="font-size: 2rem; color: #007bff;"></i>
                            <h6 class="fw-bold mb-0">Perusahaan Kerja Praktik Mahasiswa</h6>
                        </div>
                        <p class="small text-muted">Daftar perusahaan KP mahasiswa</p>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="card-body">
                            <h6 class="fw-bold text-primary mb-4">Pendaftaran Mahasiswa Kerja Praktik</h6>
                            <div class="row align-items-center text-center">
                                <div class="col-md-4">
                                    <div class="position-relative d-inline-block" style="width: 130px; height: 130px;">
                                        <canvas id="chart-kp"></canvas>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <div class="fw-bold fs-4 text-success">60</div>
                                            <div class="small text-muted">Mahasiswa</div>
                                        </div>
                                    </div>
                                    <p class="small text-muted mt-2">CV Mahasiswa</p>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative d-inline-block" style="width: 130px; height: 130px;">
                                        <canvas id="chart-perusahaan"></canvas>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <div class="fw-bold fs-4 text-primary">62%</div>
                                        </div>
                                    </div>
                                    <p class="small text-muted mt-2">Pendaftaran Perusahaan</p>
                                </div>

                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                    <div class="text-center">
                                        <div class="fw-bold fs-3 text-info">100%</div>
                                        <div class="small text-muted">Perusahaan Final</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal for KP Groups -->
    <div class="modal fade" id="kpGroupsModal" tabindex="-1" aria-labelledby="kpGroupsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kpGroupsModalLabel">
                        <i class="fas fa-users me-2"></i>
                        Daftar Kelompok Kerja Praktik Mahasiswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="kpGroupsContent">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data kelompok...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Perusahaan KP -->
    <div class="modal fade" id="perusahaanModal" tabindex="-1" aria-labelledby="perusahaanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="perusahaanModalLabel">
                        <i class="fas fa-building me-2"></i>
                        Daftar Perusahaan Kerja Praktik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="perusahaanContent">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data perusahaan...</p>
                        </div>
                    </div>
                    <hr>
                    <h6>Tambah Perusahaan Baru</h6>
                    <form id="perusahaanForm">
                        <div class="mb-3">
                            <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="kontak" class="form-label">Kontak</label>
                            <input type="text" class="form-control" id="kontak" name="kontak">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Perusahaan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Tambah Informasi -->
<div class="modal fade" id="tambahInformasiModal" tabindex="-1" aria-labelledby="tambahInformasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahInformasiModalLabel">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Informasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tambahInformasiForm">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Masukkan kategori (contoh: Kompetisi, Magang, Info Umum)" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan deskripsi informasi..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Informasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Edit Informasi -->
<div class="modal fade" id="editInformasiModal" tabindex="-1" aria-labelledby="editInformasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInformasiModalLabel">
                    <i class="fas fa-edit me-2"></i>
                    Edit Informasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editInformasiForm">
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-3">
                        <label for="editJudul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="editJudul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="editKategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="editKategori" name="kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDeskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Informasi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart KP: 60 Mahasiswa (22% hijau)
    new Chart(document.getElementById('chart-kp'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [22, 78],
                backgroundColor: ['#28a745', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: { legend: { display: false } },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Chart Perusahaan: 62%
    new Chart(document.getElementById('chart-perusahaan'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [62, 38],
                backgroundColor: ['#007bff', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: { legend: { display: false } },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Show KP Groups modal
    document.querySelector('[data-bs-target="#kpGroupsModal"]').addEventListener('click', function () {
        fetch('/api/kp-groups')
            .then(response => response.json())
            .then(data => {
                let content = '';
                if (data.length === 0) {
                    content = '<div class="text-center text-muted"><p>Belum ada kelompok kerja praktik yang terdaftar.</p></div>';
                } else {
                    content = '<div class="kp-groups-grid">';
                    data.forEach(group => {
                        content += `
                            <div class="kp-group-card">
                                <div class="kp-group-title">
                                    <i class="fas fa-users"></i>
                                    ${group.nama_kelompok}
                                </div>
                                <div class="kp-group-info">
                                    <div class="kp-group-info-item">
                                        <span class="kp-group-info-label">Jumlah Mahasiswa</span>
                                        <span class="kp-group-info-value">${group.jumlah_mahasiswa}</span>
                                    </div>
                                </div>
                                <div class="kp-group-members">
                                    <div class="kp-group-members-title">Anggota Kelompok:</div>
                                    <div class="kp-group-members-list">
                                        ${group.mahasiswa.map(name => `<span class="kp-member-tag">${name}</span>`).join('')}
                                    </div>
                                </div>
                                <div class="kp-group-date">
                                    Dibuat: ${group.created_at}
                                </div>
                            </div>
                        `;
                    });
                    content += '</div>';
                }
                document.getElementById('kpGroupsContent').innerHTML = content;
            })
            .catch(error => {
                console.error('Error loading KP groups:', error);
                let errorMessage = '<div class="text-center text-danger"><p>Terjadi kesalahan saat memuat data kelompok.</p>';
                if (error.status === 401 || error.status === 403) {
                    errorMessage += '<p class="small">Silakan login terlebih dahulu untuk melihat data kelompok.</p>';
                } else {
                    errorMessage += '<p class="small">Coba refresh halaman atau hubungi administrator.</p>';
                }
                errorMessage += '</div>';
                document.getElementById('kpGroupsContent').innerHTML = errorMessage;
            });
    });

    // Show Perusahaan modal
    document.querySelector('[data-bs-target="#perusahaanModal"]').addEventListener('click', function () {
        loadPerusahaans();
    });

    function loadPerusahaans() {
        fetch('/api/perusahaans')
            .then(response => response.json())
            .then(data => {
                let content = '';
                if (data.length === 0) {
                    content = '<div class="text-center text-muted"><p>Belum ada perusahaan yang terdaftar.</p></div>';
                } else {
                    content = '<div class="row">';
                    data.forEach(perusahaan => {
                        content += `
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">${perusahaan.nama_perusahaan}</h6>
                                        <p class="card-text small">${perusahaan.alamat || 'Alamat tidak tersedia'}</p>
                                        <p class="card-text small"><strong>Kontak:</strong> ${perusahaan.kontak || 'Tidak tersedia'}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    content += '</div>';
                }
                document.getElementById('perusahaanContent').innerHTML = content;
            })
            .catch(error => {
                console.error('Error loading perusahaans:', error);
                document.getElementById('perusahaanContent').innerHTML = '<div class="text-center text-danger"><p>Terjadi kesalahan saat memuat data perusahaan.</p></div>';
            });
    }

    // Handle perusahaan form submission
    document.getElementById('perusahaanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/kerja-praktik/store-perusahaan', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPerusahaans(); // reload the list
                this.reset(); // reset form
                alert('Perusahaan berhasil ditambahkan!');
            } else {
                alert('Error: ' + (data.message || 'Gagal menambahkan perusahaan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan perusahaan.');
        });
    });

    // Handle tambah informasi form submission
    document.getElementById('tambahInformasiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/pengumuman', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Response is not JSON');
            }
        })
        .then(data => {
            if (data.success) {
                this.reset(); // reset form
                alert('Informasi berhasil ditambahkan!');
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('tambahInformasiModal'));
                modal.hide();
                location.reload(); // reload to show new data
            } else {
                let errorMessage = data.message || 'Gagal menambahkan informasi';
                if (data.errors) {
                    errorMessage += '\n' + Object.values(data.errors).flat().join('\n');
                }
                alert('Error: ' + errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message === 'Response is not JSON') {
                alert('Sesi login telah berakhir. Silakan login kembali.');
            } else {
                alert('Terjadi kesalahan saat menyimpan informasi.');
            }
        });
    });

    // Function to edit informasi
    function editInformasi(id, judul, kategori, deskripsi) {
        document.getElementById('editId').value = id;
        document.getElementById('editJudul').value = judul;
        document.getElementById('editKategori').value = kategori;
        document.getElementById('editDeskripsi').value = deskripsi;

        const modal = new bootstrap.Modal(document.getElementById('editInformasiModal'));
        modal.show();
    }

    // Function to delete informasi
    function deleteInformasi(id, judul) {
        if (confirm('Apakah Anda yakin ingin menghapus informasi: ' + judul + '?')) {
            fetch('/pengumuman/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    throw new Error('Response is not JSON');
                }
            })
            .then(data => {
                if (data.success) {
                    alert('Informasi berhasil dihapus!');
                    location.reload(); // reload to update list
                } else {
                    alert('Error: ' + (data.message || 'Gagal menghapus informasi'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message === 'Response is not JSON') {
                    alert('Sesi login telah berakhir. Silakan login kembali.');
                } else {
                    alert('Terjadi kesalahan saat menghapus informasi.');
                }
            });
        }
    }

    // Handle edit informasi form submission
    document.getElementById('editInformasiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('editId').value;

        fetch('/pengumuman/' + id, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'PUT', // for Laravel PUT
                'Accept': 'application/json'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Response is not JSON');
            }
        })
        .then(data => {
            if (data.success) {
                alert('Informasi berhasil diperbarui!');
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editInformasiModal'));
                modal.hide();
                location.reload(); // reload to show updated data
            } else {
                let errorMessage = data.message || 'Gagal memperbarui informasi';
                if (data.errors) {
                    errorMessage += '\n' + Object.values(data.errors).flat().join('\n');
                }
                alert('Error: ' + errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message === 'Response is not JSON') {
                alert('Sesi login telah berakhir. Silakan login kembali.');
            } else {
                alert('Terjadi kesalahan saat memperbarui informasi.');
            }
        });
    });

</script>
@endsection


<style>
    /* ========================================================================= */
    /* 1. BANNER SECTION */
    /* ========================================================================= */
    .gambar {
    height: 300px !important;
    background-size: cover !important;
    background-position: center !important;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .banner-text {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        color: #ffff;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px;
        border-radius: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    }

    /* ========================================================================= */
    /* 2. INFORMASI CARD (KOLOM KIRI BESAR) */
    /* ========================================================================= */
    .info-card {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      border: 1px solid #e0e0e0;
      min-height: 500px;
    }

    .info-section {
      position: relative;
      padding-top: 10px;
      padding-bottom: 10px;
    }

    .btn-filter {
      position: absolute;
      top: 5px;
      right: 0;
      background: none;
      border: 1px solid #ccc;
      color: #666;
      padding: 2px 8px;
      border-radius: 4px;
      font-size: 12px;
      cursor: pointer;
    }

    .btn-filter:hover {
      background-color: #f5f5f5;
    }

    .info-item {
      padding-top: 5px;
      padding-bottom: 5px;
    }

    .info-item b {
      display: block;
      margin-bottom: 2px;
      color: #333;
      font-size: 14px;
    }

    .info-item p {
      margin-bottom: 0;
      font-size: 13px;
    }

    /* ========================================================================= */
    /* 3. SMALL CARDS (MBKM, TUGAS AKHIR, KERJA PRAKTIK) */
    /* ========================================================================= */
    .info-small-card {
      background-color: #ffffff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      border: 1px solid #e0e0e0;
      margin-bottom: 20px;
    }

    .item-icon {
      display: flex;
      align-items: center;
      padding: 8px 0;
      margin-bottom: 5px;
    }

    .item-icon i {
      font-size: 24px;
      margin-right: 15px;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
    }

    .item-icon div p {
      line-height: 1.2;
    }

    .item-icon.orange i {
      background-color: #fff1e5;
      color: #ff9933;
    }

    .item-icon.blue i {
      background-color: #e5f4ff;
      color: #3399ff;
    }

    /* ========================================================================= */
    /* 4. DONUT CHART (KERJA PRAKTIK STATISTIK) */
    /* ========================================================================= */
    .progress-pie-chart {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      position: relative;
      background: #e9ecef;
    }

    .ppc-progress {
      content: "";
      position: absolute;
      border-radius: 50%;
      left: calc(50% - 30px);
      top: calc(50% - 30px);
      width: 60px;
      height: 60px;
      clip: rect(0, 60px, 60px, 30px);
    }

    .ppc-progress .ppc-progress-fill {
      content: "";
      position: absolute;
      border-radius: 50%;
      left: calc(50% - 30px);
      top: calc(50% - 30px);
      width: 60px;
      height: 60px;
      clip: rect(0, 30px, 60px, 0);
      transform: rotate(0deg);
    }

    .ppc-percents {
      position: absolute;
      border-radius: 50%;
      left: calc(50% - 25px);
      top: calc(50% - 25px);
      width: 50px;
      height: 50px;
      background: #fff;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 13px;
      color: #333;
    }

    .percent {
      font-size: 12px;
      font-weight: bold;
    }

    /* Dynamic fill untuk tiap persen */
    [data-percent="22"] .ppc-progress-fill {
      background: conic-gradient(#28a745 0% 22%, #e9ecef 22% 100%);
    }

    [data-percent="62"] .ppc-progress-fill {
      background: conic-gradient(#007bff 0% 62%, #e9ecef 62% 100%);
    }

    [data-percent="62"].final .ppc-progress-fill {
      background: conic-gradient(#17a2b8 0% 62%, #e9ecef 62% 100%);
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .carousel-caption {
        background: rgba(0,0,0,0.5);
        border-radius: 8px;
        padding: 8px 16px;
    }

    .form-select-sm {
        font-size: 0.8rem;
    }

    canvas {
        max-width: 100%;
    }

    .rounded-circle {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Modal Styles */
    .modal-lg {
        max-width: 1000px;
    }

    /* KP Groups specific styles */
    .kp-groups-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .kp-group-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .kp-group-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .kp-group-title {
        font-size: 18px;
        font-weight: 600;
        color: #1E3A8A;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kp-group-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }

    .kp-group-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .kp-group-info-label {
        font-weight: 500;
        color: #6b7280;
        font-size: 14px;
    }

    .kp-group-info-value {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .kp-group-members {
        margin-top: 16px;
    }

    .kp-group-members-title {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .kp-group-members-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .kp-member-tag {
        background: #f3f4f6;
        color: #374151;
        padding: 4px 8px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
    }

    .kp-group-date {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 12px;
        text-align: right;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
    }


    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }


    /* Responsive */
    @media (max-width: 768px) {
        .carousel-caption {
            display: none !important;
        }
        .row > .col-md-4 {
            margin-bottom: 1.5rem;
        }

        .modal-container {
            width: 98%;
            margin: 10px;
        }

        .modal-body {
            padding: 16px;
        }

        .kp-groups-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

