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

<style>
/* KP Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
    animation: fadeIn 0.3s ease-out;
}
.modal-container {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s ease-out;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #dee2e6;
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
    border-radius: 12px 12px 0 0;
}
.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}
.modal-close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background 0.2s;
}
.modal-close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
}
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
.proposal-info-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}
.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.info-item.full-width {
    grid-column: 1 / -1;
}
.info-icon {
    color: #1E3A8A;
    font-size: 1.2rem;
}
.info-content label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}
.tabs-container {
    border: 1px solid #dee2e6;
    border-radius: 8px;
}
.tabs-header {
    display: flex;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
.tab-btn {
    flex: 1;
    padding: 12px;
    border: none;
    background: none;
    cursor: pointer;
}
.tab-btn.active {
    background: white;
    border-bottom: 2px solid #1E3A8A;
}
.tab-content {
    padding: 20px;
    display: none;
}
.tab-content.active {
    display: block;
}
.doc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}
.upload-item {
    border: 1px solid #dee2e6;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}
.upload-label {
    margin-bottom: 10px;
    font-weight: bold;
}
.btn-upload {
    background: #1E3A8A;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}
.kp-members-section h6 {
    margin-bottom: 15px;
}
.kp-members-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.kp-member-item {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slideIn {
    from { opacity: 0; transform: scale(0.9) translateY(-20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

/* Perusahaan Table Styles */
.table th {
    vertical-align: middle !important;
    font-weight: 600;
}

.table td {
    vertical-align: middle !important;
    padding: 10px !important;
}
</style>
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
                    content = '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>No.</th><th>Nama Kelompok</th><th>Jumlah Mahasiswa</th><th>Aksi</th></tr></thead><tbody>';
                    data.forEach((group, index) => {
                        content += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${group.nama_kelompok}</td>
                                <td>${group.jumlah_mahasiswa}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-kp-details"
                                            data-group='${JSON.stringify(group).replace(/'/g, "'")}'>
                                        <i class="fas fa-eye"></i> Details
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    content += '</tbody></table></div>';
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

    // Handle KP Group Details Modal
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-kp-details')) {
            const button = e.target.closest('.btn-kp-details');
            const group = JSON.parse(button.getAttribute('data-group').replace(/'/g, "'"));

            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            modal.innerHTML = `
                <div class="modal-container">
                    <div class="modal-header">
                        <div class="modal-title">
                            <i class="fas fa-users me-2"></i>
                            Detail Kelompok: ${group.nama_kelompok}
                        </div>
                        <button class="modal-close-btn" onclick="this.parentElement.parentElement.remove()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="proposal-info-card">
                            <div class="info-grid">
                                <div class="info-item">
                                    <i class="fas fa-users info-icon"></i>
                                    <div class="info-content">
                                        <label>Nama Kelompok</label>
                                        <span>${group.nama_kelompok}</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-user-friends info-icon"></i>
                                    <div class="info-content">
                                        <label>Jumlah Mahasiswa</label>
                                        <span>${group.jumlah_mahasiswa}</span>
                                    </div>
                                </div>
                                <div class="info-item full-width">
                                    <i class="fas fa-calendar info-icon"></i>
                                    <div class="info-content">
                                        <label>Tanggal Dibuat</label>
                                        <span>${group.created_at}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs-container">
                            <div class="tabs-header">
                                <button class="tab-btn active" data-tab="anggota">
                                    <i class="fas fa-user-graduate me-1"></i>Anggota Kelompok
                                </button>
                                <button class="tab-btn" data-tab="supervisor">
                                    <i class="fas fa-user-tie me-1"></i>Supervisor
                                </button>
                                <button class="tab-btn" data-tab="dokumen">
                                    <i class="fas fa-file-alt me-1"></i>Dokumen
                                </button>
                            </div>
                            <div class="tabs-content">
                                <div id="tab-anggota" class="tab-content active">
                                    <div class="kp-members-section">
                                        <h6>Anggota Kelompok:</h6>
                                        <div class="kp-members-list">
                                            ${group.mahasiswa.map(name => `<div class="kp-member-item"><i class="fas fa-user"></i> ${name}</div>`).join('')}
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-supervisor" class="tab-content">
                                    <div class="text-center text-muted">
                                        <p>Informasi supervisor akan ditampilkan di sini.</p>
                                    </div>
                                </div>
                                <div id="tab-dokumen" class="tab-content">
                                    <div class="doc-grid">
                                        <div class="upload-item">
                                            <div class="upload-label"><i class="bi bi-file-earmark-text" style="margin-right:8px;color:#1E3A8A;"></i>Laporan KP</div>
                                            <button class="btn-upload btn-sm">Upload</button>
                                            <input type="file" class="file-input" accept=".pdf,.doc,.docx" style="display:none;">
                                        </div>
                                        <div class="upload-item">
                                            <div class="upload-label"><i class="bi bi-file-earmark-text" style="margin-right:8px;color:#1E3A8A;"></i>Form Penilaian</div>
                                            <button class="btn-upload btn-sm">Upload</button>
                                            <input type="file" class="file-input" accept=".pdf,.doc,.docx" style="display:none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-primary" onclick="this.parentElement.parentElement.remove()">
                            <i class="bi bi-check-circle me-2"></i>Tutup
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Tab functionality
            modal.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    modal.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    modal.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    modal.querySelector('#tab-' + this.getAttribute('data-tab')).classList.add('active');
                });
            });

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) modal.remove();
            });
        }
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
                    content = '<div class="text-center text-muted py-4"><i class="fas fa-building fa-3x text-muted mb-3"></i><p>Belum ada perusahaan yang terdaftar.</p></div>';
                } else {
                    content = '<div class="table-responsive"><table class="table table-striped table-bordered"><thead class="table-dark"><tr><th class="text-center" width="5%">No.</th><th width="30%">Nama Perusahaan</th><th width="45%">Alamat</th><th width="20%">Kontak</th></tr></thead><tbody>';
                    data.forEach((perusahaan, index) => {
                        content += `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="fw-semibold">${perusahaan.nama_perusahaan}</td>
                                <td class="small">${perusahaan.alamat || '<em class="text-muted">Alamat tidak tersedia</em>'}</td>
                                <td class="small">${perusahaan.kontak || '<em class="text-muted">Tidak tersedia</em>'}</td>
                            </tr>
                        `;
                    });
                    content += '</tbody></table></div>';
                }
                document.getElementById('perusahaanContent').innerHTML = content;
            })
            .catch(error => {
                console.error('Error loading perusahaans:', error);
                document.getElementById('perusahaanContent').innerHTML = '<div class="text-center text-danger py-4"><i class="fas fa-exclamation-triangle fa-3x mb-3"></i><p>Terjadi kesalahan saat memuat data perusahaan.</p></div>';
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

