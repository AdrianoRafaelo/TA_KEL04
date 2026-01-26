@extends('layouts.app')

@section('title', 'Bimbingan')

@section('content')
<div class="container-fluid px-4 pt-3">

    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
                    <li class="breadcrumb-item active">Bimbingan</li>
                </ol>
            </nav>
            <h4 class="mb-0">Tugas Akhir</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ route('ta-dosen') }}" class="kp-tab">Pendaftaran TA</a>
                <a href="{{ route('seminar.proposal') }}" class="kp-tab">Seminar Proposal</a>
                <a href="{{ route('seminar.hasil.dosen') }}" class="kp-tab">Seminar Hasil</a>
                <a href="{{ route('sidang.akhir.dosen') }}" class="kp-tab">Sidang Akhir</a>
                <button class="kp-tab active">Bimbingan</button>
            </div>
        </div>
    </div>

    <!-- CARD: Mahasiswa Bimbingan -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0 fw-bold">Mahasiswa Bimbingan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3" style="width: 50px;">No.</th>
                                    <th class="px-4 py-3">Mahasiswa</th>
                                    <th class="px-4 py-3">Judul</th>
                                    <th class="px-4 py-3" style="width: 120px;">Selengkapnya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mahasiswa_bimbingan as $index => $m)
                                <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-4 py-3 text-muted small">{{ $index + 1 }}.</td>
                                    <td class="px-4 py-3">
                                        <div class="fw-medium">{{ $m->nama }} ({{ $m->nim }})</div>
                                    </td>
                                    <td class="px-4 py-3 text-muted small">
                                        {{ $m->judul }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button"
                                                class="btn btn-success btn-sm rounded-pill px-3"
                                                style="font-size: 0.765rem;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal"
                                                onclick="showDetail('{{ $m->nama }}', '{{ addslashes($m->judul) }}', '{{ $m->nim }}', '{{ $m->prodi }}', '{{ $m->mahasiswa }}')">
                                            Selengkapnya
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Detail Mahasiswa -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-circle me-2"></i>Detail Mahasiswa & Log Bimbingan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Student Info Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">
                            <i class="bi bi-info-circle text-primary me-2"></i>Informasi Mahasiswa
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Nama</label>
                                <p class="mb-0 fw-medium fs-6" id="modal-nama">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">NIM</label>
                                <p class="mb-0 fs-6" id="modal-nim">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Program Studi</label>
                                <p class="mb-0 fs-6" id="modal-prodi">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Judul TA</label>
                                <p class="mb-0 small" id="modal-judul">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bimbingan Logs Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">
                            <i class="bi bi-journal-text text-primary me-2"></i>Log Bimbingan
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="bimbingan-table">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="fw-semibold">Tanggal</th>
                                        <th class="fw-semibold">Topik Pembahasan</th>
                                        <th class="fw-semibold">Tugas Selanjutnya</th>
                                        <th class="fw-semibold text-center">Status</th>
                                        <th class="fw-semibold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="bimbingan-logs">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-hourglass-split fs-1 text-muted mb-2"></i>
                                                <span>Memuat data bimbingan...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showDetail(nama, judul, nim, prodi, mahasiswa) {
    document.getElementById('modal-nama').textContent = nama;
    document.getElementById('modal-nim').textContent = nim;
    document.getElementById('modal-prodi').textContent = prodi;
    document.getElementById('modal-judul').textContent = judul;

    // Fetch bimbingan logs from server
    const logsContainer = document.getElementById('bimbingan-logs');
    logsContainer.innerHTML = '<p class="text-muted">Memuat data...</p>';

    fetch(`/bimbingan-dosen/get-logs/${mahasiswa}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(bimbingans => {
        logsContainer.innerHTML = '';

        if (bimbingans && bimbingans.length > 0) {
            bimbingans.forEach(function(bimbingan) {
                const row = document.createElement('tr');
                const statusBadge = bimbingan.status === 'approved' ? '<span class="badge bg-success">Disetujui</span>' :
                                   bimbingan.status === 'rejected' ? '<span class="badge bg-danger">Ditolak</span>' :
                                   '<span class="badge bg-warning">Menunggu</span>';

                const actionButtons = bimbingan.status === 'pending' ?
                    `<button class="btn btn-success btn-sm me-1" onclick="approveBimbingan(${bimbingan.id})">Setujui</button>
                     <button class="btn btn-danger btn-sm" onclick="rejectBimbingan(${bimbingan.id})">Tolak</button>` :
                    '-';

                row.innerHTML = `
                    <td>${bimbingan.tanggal}</td>
                    <td>${bimbingan.topik_pembahasan}</td>
                    <td>${bimbingan.tugas_selanjutnya || '-'}</td>
                    <td>${statusBadge}</td>
                    <td>${actionButtons}</td>
                `;
                logsContainer.appendChild(row);
            });
        } else {
            logsContainer.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada log bimbingan.</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error fetching bimbingan logs:', error);
        logsContainer.innerHTML = '<p class="text-danger">Gagal memuat data bimbingan.</p>';
    });
}

function approveBimbingan(id) {
    if (confirm('Apakah Anda yakin ingin menyetujui log bimbingan ini?')) {
        // Send AJAX request to approve
        fetch(`/bimbingan-dosen/approve/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Log bimbingan berhasil disetujui');
                location.reload();
            } else {
                alert('Gagal menyetujui log bimbingan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }
}

function rejectBimbingan(id) {
    if (confirm('Apakah Anda yakin ingin menolak log bimbingan ini?')) {
        // Send AJAX request to reject
        fetch(`/bimbingan-dosen/reject/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Log bimbingan berhasil ditolak');
                location.reload();
            } else {
                alert('Gagal menolak log bimbingan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }
}
</script>
@endsection

@section('styles')
<style>
    .kp-tabs .kp-tab {
        padding: 8px 16px;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
        color: #495057;
        font-size: 0.9rem;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .kp-tabs .kp-tab.active {
        background: #6c5ce7;
        color: white;
        border-color: #6c5ce7;
    }
    .kp-tabs .kp-tab:hover:not(.active) {
        background: #e9ecef;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .table-sm td, .table-sm th {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }

    .btn-sm {
        font-size: 0.765rem;
        padding: 0.25rem 0.75rem;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .bg-primary {
        background-color: #2E2A78 !important;
    }

    .text-white {
        color: white !important;
    }

    @media (max-width: 992px) {
        .table-sm td, .table-sm th {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        .kp-tabs {
            justify-content: center;
        }
    }
</style>
@endsection