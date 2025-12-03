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
                        <h6 class="fw-bold mb-0">{{ $user->nama }}</h6>
                        <small class="text-muted">Fakultas / {{ $user->nim }}</small>
                        <small class="text-muted d-block mt-1">Anggota Kelompok: -</small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <small class="text-muted">Posisi (Divisi)</small>
                        <h6 class="fw-bold mb-0">{{ $approvedMbkm->divisi ?? 'N/A' }}</h6>
                    </div>
                </div>
            </div>

            <!-- Info Kanan -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <small class="text-muted">Perusahaan</small>
                        <h6 class="fw-bold mb-0">{{ $approvedMbkm->mitra->nama_perusahaan ?? 'N/A' }}</h6>
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
                                <th>Deskripsi Kegiatan</th>
                                <th style="width: 120px;">Bimbingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelaksanaans as $pelaksanaan)
                            <tr>
                                <td>W{{ $pelaksanaan->minggu }}</td>
                                <td>{{ $pelaksanaan->matkul }}</td>
                                <td>{{ $pelaksanaan->deskripsi_kegiatan }}</td>
                                <td>{{ $pelaksanaan->bimbingan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada log-activity.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-end">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahLogModal">+ Tambah Log-Activity</button>
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
                        <div class="fw-bold fs-3 text-primary">{{ round($progressPercentage) }}%</div>
                    </div>
                </div>
                <p class="small text-muted mt-3">Minggu ke-{{ $completedWeeks }} dari {{ $totalWeeks }}</p>
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
            data: [{{ $progressPercentage }}, {{ 100 - $progressPercentage }}], // progress, remaining
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

<!-- Modal Tambah Log-Activity -->
<div class="modal fade" id="tambahLogModal" tabindex="-1" aria-labelledby="tambahLogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahLogModalLabel">Tambah Log-Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mbkm.store.pelaksanaan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="minggu" class="form-label">Minggu</label>
                        <input type="number" class="form-control" id="minggu" name="minggu" min="1" max="16" required>
                    </div>
                    <div class="mb-3">
                        <label for="matkul" class="form-label">Mata Kuliah</label>
                        <input type="text" class="form-control" id="matkul" name="matkul" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_kegiatan" class="form-label">Deskripsi Kegiatan</label>
                        <textarea class="form-control" id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="bimbingan" class="form-label">Bimbingan</label>
                        <textarea class="form-control" id="bimbingan" name="bimbingan" rows="3" placeholder="Opsional, diisi oleh pembimbing"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
