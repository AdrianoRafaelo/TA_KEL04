@extends('layouts.app')

@section('title', 'Detail Pelaksanaan KP')

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
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
                <button class="kp-tab" disabled>Seminar KP</button>
            </div>
        </div>
    </div>

    <!-- CARD UTAMA -->
    <div class="row g-4">
        <!-- KOLOM KIRI: Info Mahasiswa & Progress -->
        <div class="col-lg-8">
            <!-- CARD INFO -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Info Kiri -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Nama/NIM</small>
                                    <h6 class="fw-bold mb-0">{{ $student->nama ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $student->fakultas ?? 'Universitas' }} / {{ $student->nim ?? $user['username'] }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Posisi</small>
                                    <h6 class="fw-bold mb-0">{{ $kpRequest->supervisor->nama_supervisor ?? 'Belum ditentukan' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Info Kanan -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Perusahaan</small>
                                    <h6 class="fw-bold mb-0">{{ $kpRequest->company->nama_perusahaan ?? 'Belum ditentukan' }}</h6>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Pembimbing</small>
                                    <h6 class="fw-bold mb-0">{{ $kpRequest->dosen->nama ?? 'Belum ditentukan' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD LOG ACTIVITY -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Log Activity Mahasiswa</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div>
                                    <small class="text-muted">Kerja Lapangan</small>
                                    <h6 class="fw-bold mb-0">8 Jam</h6>
                                </div>
                                <span class="badge bg-primary rounded-pill">W1</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div>
                                    <small class="text-muted">Meeting</small>
                                    <h6 class="fw-bold mb-0">2 Jam</h6>
                                </div>
                                <span class="badge bg-success rounded-pill">W1</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div>
                                    <small class="text-muted">Laporan</small>
                                    <h6 class="fw-bold mb-0">4 Jam</h6>
                                </div>
                                <span class="badge bg-warning rounded-pill">W1</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div>
                                    <small class="text-muted">Lainnya</small>
                                    <h6 class="fw-bold mb-0">3 Jam</h6>
                                </div>
                                <span class="badge bg-secondary rounded-pill">W1</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD TOPIK KHUSUS -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Topik Khusus</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Judul Topik</label>
                        <input type="text" class="form-control" value="Studi Pengaruh Lengan X, Berat, dan Kecepatan Terhadap Kestabilan" readonly>
                    </div>
                    <button class="btn btn-outline-primary btn-sm">Detail</button>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Progress & Bimbingan -->
        <div class="col-lg-4">
            <!-- CARD PROGRESS KP -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
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

            <!-- CARD BIMBINGAN -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Bimbingan</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <small class="text-muted">Total Bimbingan</small>
                            <h6 class="fw-bold mb-0">6 Kali</h6>
                        </div>
                        <span class="badge bg-success rounded-pill px-3">Jadwal</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">Terakhir</small>
                            <h6 class="fw-bold mb-0">10 Nov 2025</h6>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill px-3">Selesai</span>
                    </div>
                </div>
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
                data: [25, 75],
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
</style>
@endsection