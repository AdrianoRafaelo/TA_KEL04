@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Page</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">MBKM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Informasi Umum</li>
            </ol>
        </nav>
        <h4 class="mb-2">MBKM</h4>
    </div>
</div>


<div class="row mb-3">
    <div class="col-12">
        <div class="kp-tabs">
            <button class="kp-tab">Informasi Umum</button>
            <a href="{{ url('/mbkm/pendaftaran-mhs') }}" class="kp-tab">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-mhs')}}" class="kp-tab active">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-mhs')}}" class="kp-tab">Seminar MBKM</a>
        </div>
    </div>
</div>

<!-- informasi mbkm mahasiswa -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- Info Kiri -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <small class="text-muted">Nama/NIM</small>
                        <h6 class="fw-bold mb-0">Nama Mahasiswa</h6>
                        <small class="text-muted">Fakultas / 123456789</small>
                        <small class="text-muted d-block mt-1">Anggota Kelompok: Anggota 1, Anggota 2</small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <small class="text-muted">Posisi (Divisi)</small>
                        <h6 class="fw-bold mb-0">Divisi Contoh</h6>
                    </div>
                </div>
            </div>

            <!-- Info Kanan -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <small class="text-muted">Perusahaan</small>
                        <h6 class="fw-bold mb-0">Nama Perusahaan</h6>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <small class="text-muted">Pembimbing</small>
                        <h6 class="fw-bold mb-0">Nama Pembimbing</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- log activity -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-4">
                    <i class="mdi mdi-format-list-bulleted"></i> Log-Activity Mahasiswa
                </h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">Week</th>
                                <th style="width: 120px;">Matkul</th>
                                <th>Log-Activity Mahasiswa</th>
                                <th style="width: 120px;">Bimbingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>W1</td>
                                <td>Relog</td>
                                <td>
                                    Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor
                                    incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                    nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.
                                </td>
                                <td>-</td>
                            </tr>

                            <tr>
                                <td>W1</td>
                                <td>Remu</td>
                                <td>
                                    Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor
                                    incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                    nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.
                                </td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-end">
                    <a href="#" class="text-primary small">+ Tambah log-activity</a>
                </div>

            </div>
        </div>
    </div>

    <!-- progress -->
    <div class="col-md-4">
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
</div>
@endsection

@section('scripts')
<script>
// Progress Chart
new Chart(document.getElementById('progressChart'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [25, 75], // 25% progress, 75% remaining
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
