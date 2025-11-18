@extends('layouts.app')

@section('title', 'Detail Pelaksanaan KP')

@section('content')
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
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
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
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
        <i class="fas fa-list me-2"></i>
        <h6 class="fw-bold mb-0">Log Aktivitas Mahasiswa</h6>
    </div>
    <div class="card-body p-4">
        <div class="row g-3" id="aktivitasContainer">
@forelse($aktivitas as $aktivitasItem)
<div class="col-12 mb-3">
    <div class="d-flex align-items-start justify-content-between p-3 bg-white rounded-3 shadow-sm log-item">

        <!-- KIRI: Judul + Deskripsi (satu baris) + Tanggal (baris bawah) -->
        <div class="flex-grow-1 me-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <h6 class="fw-bold mb-0 text-primary" style="min-width: 80px; font-size: 0.95rem;">
                    {{ $aktivitasItem->judul }}
                </h6>
                <span class="small text-muted flex-grow-1" style="font-size: 0.85rem;">
                    {{ $aktivitasItem->deskripsi ?? 'tidak ada' }}
                </span>
            </div>
            <small class="text-muted d-block" style="font-size: 0.75rem; margin-top: -2px;">
                {{ $aktivitasItem->created_at->format('d/m/Y H:i') }}
            </small>
        </div>

        <!-- KANAN: Tombol (rapat vertikal) -->
        <div class="d-flex flex-column gap-1">
            @if($aktivitasItem->file_path)
            <a href="{{ route('kerja-praktik.view-log-activity', $aktivitasItem->id) }}"
               class="btn btn-sm btn-info text-white px-3" target="_blank">
                Lihat Dokumen
            </a>
            @else
            <button class="btn btn-sm btn-secondary text-white px-3" disabled>
                Lihat Dokumen
            </button>
            @endif
            <button class="btn btn-sm btn-primary px-3"
                    onclick="editAktivitas({{ $aktivitasItem->id }}, '{{ addslashes($aktivitasItem->judul) }}', '{{ addslashes($aktivitasItem->deskripsi ?? '') }}')">
                Edit
            </button>
        </div>
    </div>
</div>
@empty
<div class="col-12">
    <div class="text-center py-4">
        <i class="fas fa-list fa-3x text-muted mb-3"></i>
        <p class="text-muted">Belum ada log aktivitas yang dibuat.</p>
    </div>
</div>
@endforelse        </div>

        <!-- Tombol Tambah -->
        <div class="mt-4 text-center">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#aktivitasModal" onclick="resetModal()">
                <i class="fas fa-plus me-1"></i> Tambah Log Aktivitas
            </button>
        </div>
    </div>

    <!-- Modal for Add/Edit Activity -->
    <div class="modal fade" id="aktivitasModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="aktivitasModalTitle">Tambah Log Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="aktivitasForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="aktivitasId" name="aktivitas_id">
                        <div class="mb-3">
                            <label class="form-label">Judul Log</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Contoh: Minggu ke-1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih File Dokumen (Opsional)</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsikan aktivitas yang dilakukan..."></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
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
                    <!-- Tabel Bimbingan -->
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th class="ps-0" style="width: 15%;">Tanggal</th>
                                    <th class="ps-0">Topik Bimbingan</th>
                                    <th class="ps-0 text-center" style="width: 15%;">Jenis</th>
                                    <th class="ps-0 text-center" style="width: 15%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bimbingan as $bimbinganItem)
                                <tr>
                                    <td class="ps-0 text-muted small">{{ $bimbinganItem->tanggal->format('d M Y') }}</td>
                                    <td class="ps-0">
                                        <p class="mb-0 text-dark">
                                            {{ $bimbinganItem->topik }}
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light px-3 py-2 rounded-pill small text-dark">
                                            {{ $bimbinganItem->jenis == 'sebelum_kp' ? 'Sebelum KP' : 'Sesudah KP' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge
                                            @if($bimbinganItem->status == 'diterima') bg-success
                                            @elseif($bimbinganItem->status == 'ditolak') bg-danger
                                            @else bg-warning @endif
                                            text-white px-3 py-2 rounded-pill small">
                                            {{ ucfirst($bimbinganItem->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada data bimbingan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Tambah -->
                    <div class="mt-4">
                        <a href="#" class="text-primary text-decoration-none small" data-bs-toggle="modal" data-bs-target="#bimbinganModal">
                            <i class="bi bi-plus-circle me-1"></i> + Tambah
                        </a>
                    </div>
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
                    <div class="mb-4 text-center">
                        <button class="btn btn-primary btn-sm px-4" data-bs-toggle="modal" data-bs-target="#topikKhususModal">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Topik Khusus
                        </button>
                    </div>

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

<!-- Modal for Bimbingan -->
<div class="modal fade" id="bimbinganModal" tabindex="-1">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ajukan Bimbingan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="bimbinganForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tanggal Bimbingan</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Topik Bimbingan</label>
                    <textarea class="form-control" id="topik" name="topik" rows="3" placeholder="Jelaskan topik yang ingin dibahas..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Bimbingan</label>
                    <select class="form-control" id="jenis" name="jenis" required>
                        <option value="">Pilih Jenis Bimbingan</option>
                        <option value="sebelum_kp">Sebelum KP</option>
                        <option value="sesudah_kp">Sesudah KP</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Modal for Topik Khusus -->
<div class="modal fade" id="topikKhususModal" tabindex="-1">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ajukan Topik Khusus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="topikKhususForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Topik Khusus</label>
                    <textarea class="form-control" id="topik_khusus" name="topik" rows="3" placeholder="Jelaskan topik khusus yang ingin diajukan..." required></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@endsection

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

    // Aktivitas functions
    function resetModal() {
        document.getElementById('aktivitasModalTitle').textContent = 'Tambah Log Aktivitas';
        document.getElementById('aktivitasForm').reset();
        document.getElementById('aktivitasId').value = '';
    }

    function editAktivitas(id, judul, deskripsi) {
        document.getElementById('aktivitasModalTitle').textContent = 'Edit Log Aktivitas';
        document.getElementById('aktivitasId').value = id;
        document.getElementById('judul').value = judul;
        document.getElementById('deskripsi').value = deskripsi;
        // Note: File cannot be pre-filled for security reasons

        const modal = new bootstrap.Modal(document.getElementById('aktivitasModal'));
        modal.show();
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('aktivitasModal'));
                modal.hide();

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
                const modal = bootstrap.Modal.getInstance(document.getElementById('bimbinganModal'));
                modal.hide();

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
                const modal = bootstrap.Modal.getInstance(document.getElementById('topikKhususModal'));
                modal.hide();

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

@section('styles')
<style>
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
</style>
@endsection