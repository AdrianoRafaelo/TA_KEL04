@extends('layouts.app')

@section('title', 'Detail Pelaksanaan KP')

@section('content')

<!-- Modal for Add/Edit Activity – VERSI SUPER MODERN -->
<div class="modal-overlay" id="aktivitasModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="bi bi-file-earmark-text-fill me-2"></i>
                <span id="aktivitasModalTitle">Tambah Log Aktivitas</span>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('#aktivitasModal')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="aktivitasForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="aktivitasId" name="aktivitas_id">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Judul Log</label>
                        <input type="text" class="form-control" id="judul" name="judul" placeholder="Contoh: Minggu ke-1" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Pilih File Dokumen (Opsional)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx">
                        <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB.</small>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsikan aktivitas yang dilakukan..."></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('#aktivitasModal')">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Bimbingan – VERSI SUPER MODERN -->
<div class="modal-overlay" id="bimbinganModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="bi bi-file-earmark-text-fill me-2"></i>
                Ajukan Bimbingan
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('#bimbinganModal')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="bimbinganForm">
            @csrf
            <input type="hidden" name="kp_request_id" value="{{ $kpRequest ? $kpRequest->id : '' }}">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Tanggal Bimbingan</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Topik Bimbingan</label>
                        <textarea class="form-control" id="topik" name="topik" rows="3" placeholder="Jelaskan topik yang ingin dibahas..." required></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Jenis Bimbingan</label>
                        <select class="form-control" id="jenis" name="jenis" required>
                            <option value="">Pilih Jenis Bimbingan</option>
                            <option value="sebelum_kp">Sebelum KP</option>
                            <option value="sewaktu_kp">Sewaktu KP</option>
                            <option value="sesudah_kp">Sesudah KP</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('#bimbinganModal')">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i> Ajukan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Topik Khusus – VERSI SUPER MODERN -->
<div class="modal-overlay" id="topikKhususModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="bi bi-file-earmark-text-fill me-2"></i>
                Ajukan Topik Khusus
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('#topikKhususModal')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="topikKhususForm">
            @csrf
            <input type="hidden" name="kp_request_id" value="{{ $kpRequest ? $kpRequest->id : '' }}">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Topik Khusus</label>
                        <textarea class="form-control" id="topik_khusus" name="topik" rows="3" placeholder="Jelaskan topik khusus yang ingin diajukan..." required></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('#topikKhususModal')">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i> Ajukan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container-fluid px-4 py-3">

    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Halaman</a></li>
                    <li class="breadcrumb-item"><a href="#">Kerja Praktik</a></li>
                    <li class="breadcrumb-item"><a href="#">Pelaksanaan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="kp-tabs">
                <a href="{{ url('/kerja-praktik') }}" class="kp-tab">Informasi Umum</a>
                <a href="{{ url('/pendaftaran-kp') }}" class="kp-tab">Pendaftaran KP</a>
                <a href="{{ url('/kerja-praktik-mahasiswa-pelaksanaan') }}" class="kp-tab active">Pelaksanaan KP</a>
            <a href="{{ url('/kerja-praktik-mahasiswa-seminar')}}" class="kp-tab">Seminar KP</a>
            </div>
        </div>
    </div>

    <!-- INFO MAHASISWA FULL WIDTH -->
    <div class="cardd border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="car p-4">
            <div class="row g-4">
                <!-- Info Kiri -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">

                        <div>
                            <small class="text-muted">Nama/NIM</small>
                            <h6 class="fw-bold mb-0">{{ $student->nama ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ $student->fakultas ?? 'Universitas' }} / {{ $student->nim ?? $user['username'] }}</small>
                            @if(isset($groupMembers) && $groupMembers->count() > 0)
                                <small class="text-muted d-block mt-1">
                                    Anggota Kelompok: {{ $groupMembers->pluck('nama')->join(', ') }}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center">

                        <div>
                            <small class="text-muted">Posisi (Divisi)</small>
                            <h6 class="fw-bold mb-0">{{ $kpRequest->divisi ?? 'Belum ditentukan' }}</h6>
                        </div>
                    </div>
                </div>

                <!-- Info Kanan -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">

                        <div>
                            <small class="text-muted">Perusahaan</small>
                            <h6 class="fw-bold mb-0">{{ $kpRequest->company->nama_perusahaan ?? 'Belum ditentukan' }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">

                        <div>
                            <small class="text-muted">Pembimbing</small>
                            <h6 class="fw-bold mb-0">{{ $kpRequest->dosen->nama ?? 'Belum ditentukan' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD UTAMA -->
    <div class="row g-4">
        <!-- KOLOM KIRI: Log Activity -->
        <div class="col-12">

<!-- CARD LOG ACTIVITY MAHASISWA -->
<div class="cardd border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
        <i class="fas fa-list me-2"></i>
        <h6 class="fw-bold mb-0">Log Aktivitas Mahasiswa</h6>
    </div>
    <div class="card-body p-4">
        <div class="row g-3" id="aktivitasContainer">
<!-- Ganti bagian log aktivitas dengan card yang lebih modern -->
@forelse($aktivitas as $aktivitasItem)
<div class="col-12 mb-3">
    <div class="activity-card">
        <div class="activity-content">
            <div class="activity-header">
                <div class="activity-icon">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="activity-details">
                    <h6 class="activity-title">{{ $aktivitasItem->judul }}</h6>
                    <p class="activity-description">{{ $aktivitasItem->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    <span class="activity-time">
                        <i class="bi bi-clock"></i> {{ $aktivitasItem->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="activity-actions">
            @if($aktivitasItem->file_path)
            <a href="{{ route('kerja-praktik.view-log-activity', $aktivitasItem->id) }}" 
               class="btn-action btn-view" target="_blank">
                <i class="bi bi-eye"></i>
                <span>Lihat</span>
            </a>
            @endif
            <button class="btn-action btn-edit" 
                    onclick="editAktivitas({{ $aktivitasItem->id }}, '{{ addslashes($aktivitasItem->judul) }}', '{{ addslashes($aktivitasItem->deskripsi ?? '') }}')">
                <i class="bi bi-pencil"></i>
                <span>Edit</span>
            </button>
        </div>
    </div>
</div>
@empty
<!-- Empty state dengan ilustrasi -->
<div class="col-12">
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-inbox"></i>
        </div>
        <h6>Belum Ada Log Aktivitas</h6>
        <p>Mulai dokumentasikan kegiatan KP Anda dengan menambahkan log aktivitas pertama</p>
        <button class="btn btn-primary mt-3" onclick="openModal('#aktivitasModal'); resetModal();">
            <i class="bi bi-plus-circle me-2"></i> Tambah Log Pertama
        </button>
    </div>
</div>
@endforelse    

        <!-- Tombol Tambah -->
        <div class="mt-4 text-center">
            <button class="btn btn-outline-primary" onclick="openModal('#aktivitasModal'); resetModal();">
                <i class="fas fa-plus me-1"></i> Tambah Log Aktivitas
            </button>
        </div>
    </div>

    <!-- Modal for Add/Edit Activity – VERSI SUPER MODERN -->
    <div class="modal-overlay" id="aktivitasModal">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="bi bi-file-earmark-text-fill me-2"></i>
                    <span id="aktivitasModalTitle">Tambah Log Aktivitas</span>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeModal('#aktivitasModal')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="aktivitasForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="aktivitasId" name="aktivitas_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Judul Log</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Contoh: Minggu ke-1" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Pilih File Dokumen (Opsional)</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB.</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsikan aktivitas yang dilakukan..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('#aktivitasModal')">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div>

    <!-- SECOND ROW: Bimbingan & Topik Khusus -->
    <div class="row g-4 mb-4">
        <div class="col-8">
            <!-- CARD BIMBINGAN -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Bimbingan</h6>
                </div>
                <div class="card-body p-4">
                    <!-- Card Bimbingan -->
                    <div class="bimbingan-list">
                        @forelse($bimbingan as $bimbinganItem)
                        <div class="bimbingan-card">
                            <div class="bimbingan-header">
                                <div class="bimbingan-date">
                                    <i class="bi bi-calendar3"></i>
                                    <span>{{ $bimbinganItem->tanggal->format('d M Y') }}</span>
                                </div>
                                <span class="badge-jenis {{ $bimbinganItem->jenis }}">
                                    @if($bimbinganItem->jenis == 'sebelum_kp')
                                        Sebelum KP
                                    @elseif($bimbinganItem->jenis == 'sewaktu_kp')
                                        Sewaktu KP
                                    @else
                                        Sesudah KP
                                    @endif
                                </span>
                            </div>
                            <div class="bimbingan-body">
                                <p class="bimbingan-topik">{{ $bimbinganItem->topik }}</p>
                            </div>
                            <div class="bimbingan-footer">
                                <span class="badge-status status-{{ $bimbinganItem->status }}">
                                    <i class="bi bi-circle-fill"></i>
                                    {{ ucfirst($bimbinganItem->status) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state-small">
                            <i class="bi bi-chat-dots"></i>
                            <p>Belum ada jadwal bimbingan</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Tombol Tambah -->
                    @if($kpRequest)
                    <div class="mt-4">
                        <a href="#" class="text-primary text-decoration-none small" onclick="openModal('#bimbinganModal');">
                            <i class="bi bi-plus-circle me-1"></i> + Tambah
                        </a>
                    </div>
                    @else
                    <div class="mt-4">
                        <small class="text-muted">Permohonan KP harus disetujui terlebih dahulu untuk dapat mengajukan bimbingan</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Topik Khusus -->
        <div class="col-4">
            <!-- Card Topik Khusus -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                    <h6 class="fw-bold mb-0 d-flex align-items-center">
                        <span class="me-2">📌</span> Topik Khusus
                    </h6>
                </div>
                <div class="card-body p-4">
                    <!-- Tombol Ajukan Topik -->
                    @if($kpRequest)
                    <div class="mb-4 text-center">
                        <button class="btn btn-primary btn-sm px-4" onclick="openModal('#topikKhususModal');">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Topik Khusus
                        </button>
                    </div>
                    @else
                    <div class="mb-4 text-center">
                        <small class="text-muted">Permohonan KP harus disetujui terlebih dahulu untuk dapat mengajukan topik khusus</small>
                    </div>
                    @endif

                    <!-- Daftar Topik yang Diajukan -->
                    @forelse($topikKhusus as $topik)
                    <div class="border rounded-3 p-3 bg-light {{ !$loop->first ? 'mt-2' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <p class="mb-0 small text-dark">
                                {{ $topik->topik }}
                            </p>
                            <span class="badge
                                @if($topik->status == 'diterima') bg-success
                                @elseif($topik->status == 'ditolak') bg-danger
                                @else bg-warning @endif
                                text-white px-3 py-2 rounded-pill small ms-3">
                                {{ ucfirst($topik->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="border rounded-3 p-3 bg-light text-center text-muted">
                        Belum ada topik khusus yang diajukan
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- THIRD ROW: Progress KP & Bimbingan Summary -->
<div class="row g-4">
    <div class="col-6">
        <!-- CARD PROGRESS KP -->
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 text-center">
                <h6 class="fw-bold mb-3">Progress KP</h6>
                <div class="position-relative d-inline-block" style="width: 140px; height: 140px;">
                    <canvas id="progressChart"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="fw-bold fs-3 text-primary">25%</div>
                    </div>
                </div>
                <p class="small text-muted mt-3">Minggu ke-1 dari 16</p>
            </div>
        </div>
    </div>
    <div class="col-6">
    </div>
</div>
</div>


<!-- Modal for Topik Khusus – VERSI SUPER MODERN -->
<div class="modal-overlay" id="topikKhususModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="bi bi-file-earmark-text-fill me-2"></i>
                Ajukan Topik Khusus
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('#topikKhususModal')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="topikKhususForm">
            @csrf
            <input type="hidden" name="kp_request_id" value="{{ $kpRequest ? $kpRequest->id : '' }}">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Topik Khusus</label>
                        <textarea class="form-control" id="topik_khusus" name="topik" rows="3" placeholder="Jelaskan topik khusus yang ingin diajukan..." required></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('#topikKhususModal')">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i> Ajukan
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Progress Chart
    new Chart(document.getElementById('progressChart'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $progressPercentage }}, {{ 100 - $progressPercentage }}],
                backgroundColor: ['#6f42c1', '#e9ecef'],
                borderWidth: 0,
                borderRadius: 8
            }]
        },
        options: {
            cutout: '75%',
            plugins: { legend: { display: false } },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Modal functions
    function openModal(modalId) {
        $(modalId).addClass('show');
    }

    function closeModal(modalId) {
        $(modalId).removeClass('show');
    }

    // Aktivitas functions
    function resetModal() {
        document.getElementById('aktivitasModalTitle').textContent = 'Tambah Log Aktivitas';
        document.getElementById('aktivitasForm').reset();
        document.getElementById('aktivitasId').value = '';
    }

    // Click outside to close modal
    $('#aktivitasModal, #bimbinganModal, #topikKhususModal').click(function(e) {
        if (e.target === this) {
            closeModal('#' + this.id);
        }
    });

    function editAktivitas(id, judul, deskripsi) {
        document.getElementById('aktivitasModalTitle').textContent = 'Edit Log Aktivitas';
        document.getElementById('aktivitasId').value = id;
        document.getElementById('judul').value = judul;
        document.getElementById('deskripsi').value = deskripsi;
        // Note: File cannot be pre-filled for security reasons

        openModal('#aktivitasModal');
    }

    // Handle form submission
    document.getElementById('aktivitasForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("kerja-praktik.store-log-activity") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                closeModal('#aktivitasModal');

                // Reload page to show new data
                location.reload();
            } else {
                alert('Kesalahan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        });
    });

    // Handle bimbingan form submission
    document.getElementById('bimbinganForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("kp.bimbingan.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                closeModal('#bimbinganModal');
    
                // Reload page to show new data
                location.reload();
            } else {
                alert('Kesalahan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        });
    });

    // Handle topik khusus form submission
    document.getElementById('topikKhususForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("kp.topik-khusus.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                closeModal('#topikKhususModal');

                // Reload page to show new data
                location.reload();
            } else {
                alert('Kesalahan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        });
    });
</script>
@endsection


<style>
    /* === ACTIVITY CARD MODERN === */
.activity-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    border: 1px solid #e9ecef;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.activity-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.activity-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    border-color: #667eea;
}

.activity-card:hover::before {
    opacity: 1;
}

.activity-content {
    flex: 1;
}

.activity-header {
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.activity-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.activity-details {
    flex: 1;
}

.activity-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.activity-description {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 8px 0;
    line-height: 1.5;
}

.activity-time {
    font-size: 0.75rem;
    color: #9ca3af;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.activity-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 120px;
    text-decoration: none;
}

.btn-action i {
    font-size: 16px;
}

.btn-action.btn-view {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    color: white;
}

.btn-action.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
}

.btn-action.btn-edit {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
}

.btn-action.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
}

/* === EMPTY STATE === */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    border: 2px dashed #dee2e6;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
}

.empty-state h6 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 8px;
}

.empty-state p {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

/* === BIMBINGAN CARD === */
.bimbingan-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.bimbingan-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.bimbingan-card:hover {
    border-color: #667eea;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    transform: translateX(4px);
}

.bimbingan-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.bimbingan-date {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.bimbingan-date i {
    color: #667eea;
}

.badge-jenis {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    background: #f3f4f6;
    color: #4b5563;
}

.badge-jenis.sebelum_kp {
    background: #dbeafe;
    color: #1e40af;
}

.badge-jenis.sesudah_kp {
    background: #fef3c7;
    color: #92400e;
}

.bimbingan-body {
    margin-bottom: 12px;
}

.bimbingan-topik {
    font-size: 0.9375rem;
    color: #1f2937;
    line-height: 1.6;
    margin: 0;
}

.bimbingan-footer {
    display: flex;
    justify-content: flex-end;
}

.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-status i {
    font-size: 8px;
}

.badge-status.status-menunggu {
    background: #fef3c7;
    color: #92400e;
}

.badge-status.status-diterima {
    background: #d1fae5;
    color: #065f46;
}

.badge-status.status-ditolak {
    background: #fee2e2;
    color: #991b1b;
}

.empty-state-small {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
}

.empty-state-small i {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.empty-state-small p {
    font-size: 0.875rem;
    margin: 0;
}

/* === TOPIK KHUSUS CARD === */
.topik-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 16px;
    border: 1px solid #e5e7eb;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.topik-card:hover {
    border-color: #f59e0b;
    box-shadow: 0 4px 16px rgba(245, 158, 11, 0.1);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .activity-card {
        flex-direction: column;
        align-items: stretch;
    }
    
    .activity-actions {
        flex-direction: row;
        justify-content: stretch;
    }
    
    .btn-action {
        flex: 1;
    }
}
    /* Import Modern Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    /* Global Typography */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        font-size: 14px;
        line-height: 1.6;
        color: #374151;
    }

    /* Typography Hierarchy */
    h4, h5, h6 {
        font-weight: 600 !important;
        color: #1f2937 !important;
        letter-spacing: -0.025em !important;
    }

    h4 {
        font-size: 2rem !important;
        margin-bottom: 1rem !important;
    }

    h5 {
        font-size: 1.5rem !important;
        margin-bottom: 1rem !important;
    }

    h6 {
        font-size: 1.125rem !important;
        margin-bottom: 0.75rem !important;
    }

    p, .form-group label, .kp-list-item {
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        color: #6b7280 !important;
    }

    .form-control, .form-select {
        font-size: 0.875rem !important;
        font-weight: 400 !important;
    }

    .btn {
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        letter-spacing: 0.025em !important;
    }

    /* Glassmorphism Card Styles */
    .car {
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 4px 16px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s ease !important;
    }

    .card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15), 0 6px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Form Controls with Rounded Corners */
    .form-control, .form-select {
        border-radius: 12px !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(5px) !important;
        transition: all 0.3s ease !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        background: rgba(255, 255, 255, 0.95) !important;
    }

    /* Buttons with Rounded Corners */
    .btn {
        border-radius: 12px !important;
    }

    /* Custom Modal Styles like koordinator_sempro */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.show {
        opacity: 1;
        visibility: visible;
        display: flex;
    }

    .modal-container {
        background: white;
        border-radius: 16px;
        width: 95%;
        max-width: 600px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        color: white;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-bottom: none;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        margin: 0;
    }

    .modal-close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        opacity: 1;
    }

    .modal-close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
        max-height: calc(90vh - 140px);
    }

    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
        text-align: right;
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
    }

    .kp-tab {
        font-weight: 700 !important;
        font-size: 1.1rem !important;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #F59E0B 0%, #FCD34D 100%) !important;
        color: #111 !important;
        border: none !important;
        padding: 12px 24px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .btn-primary:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3) !important;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #545b62 100%) !important;
        color: white !important;
        border: none !important;
        padding: 12px 24px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .btn-secondary:hover {
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3) !important;
    }

    .btn-view {
        background: linear-gradient(135deg, #10B981 0%, #34D399 100%) !important;
        color: white !important;
        border: none !important;
        padding: 0.375rem 0.75rem !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        min-width: 130px !important;
        justify-content: center !important;
    }

    .btn-view:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3) !important;
    }

    .card {
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-4px);
    }

    .rounded-circle {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    canvas {
        max-width: 100%;
    }

    /* Hover efek untuk log activity */
    .bg-light:hover {
        background-color: #e9ecef !important;
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    @media (max-width: 992px) {
        .card-body {
            padding: 1rem;
        }
        .fs-3 {
            font-size: 1.5rem !important;
        }
    }

    /* === JUDUL + DESKRIPSI SATU BARIS, TANGGAL LEBIH LEGA === */
    .log-item {
        border: 1px solid #e0e0e0 !important;
        transition: all 0.2s ease;
        min-height: 76px;
        padding: 0.75rem 1rem !important;
    }

    .log-item:hover {
        background-color: #f9f9f9 !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
    }

    /* Judul */
    .log-item h6 {
        font-size: 0.95rem;
        margin: 0 !important;
        font-weight: 600;
        color: #333;
    }

    /* Deskripsi */
    .log-item .small {
        font-size: 0.85rem;
        color: #555;
        line-height: 1.4;
        margin: 0;
    }

    /* Jarak antara baris judul/deskripsi ke tanggal: 8px (lega tapi rapi) */
    .log-item .mb-2 {
        margin-bottom: 0.5rem !important; /* 8px */
    }

    /* Tanggal: lebih lega dari atas */
    .log-item small.text-muted {
        font-size: 0.75rem;
        color: #888;
        line-height: 1.3;
        margin-top: 0.25rem !important; /* tambah jarak atas */
        display: block;
    }

    /* Tombol */
    .log-item .btn-sm {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        min-width: 92px;
        text-align: center;
    }

    /* === BIMBINGAN CARDS === */
    .bimbingan-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .bimbingan-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .bimbingan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .bimbingan-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0.8;
    }

    .bimbingan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .bimbingan-date {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .bimbingan-date i {
        color: #9ca3af;
    }

    .badge-jenis {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-jenis.sebelum_kp {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .badge-jenis.sewaktu_kp {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        color: #166534;
        border: 1px solid #16a34a;
    }

    .badge-jenis.sesudah_kp {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border: 1px solid #3b82f6;
    }

    .bimbingan-body {
        margin-bottom: 16px;
    }

    .bimbingan-topik {
        font-size: 1rem;
        font-weight: 500;
        color: #374151;
        line-height: 1.5;
        margin: 0;
    }

    .bimbingan-footer {
        display: flex;
        justify-content: flex-end;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-status.status-diterima {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #10b981;
    }

    .badge-status.status-ditolak {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    .badge-status.status-menunggu {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .badge-status i {
        font-size: 8px;
    }

    .empty-state-small {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }

    .empty-state-small i {
        font-size: 3rem;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state-small p {
        margin: 0;
        font-size: 0.875rem;
        font-weight: 500;
    }
</style>
@endsection