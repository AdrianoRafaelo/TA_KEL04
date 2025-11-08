@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Seminar Proposal</li>
                </ol>
            </nav>
            <h4 class="mb-0">Tugas Akhir</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ route('koordinator.pendaftaran') }}" class="kp-tab active">Pendaftaran Judul</a>
                <a href="{{ route('koordinator.mahasiswa.ta') }}" class="kp-tab">Mahasiswa TA</a>
                <a href="{{ route('koordinator.sempro') }}" class="kp-tab">Seminar Proposal</a>
                <button class="kp-tab" disabled>Seminar Hasil</button>
                <button class="kp-tab" disabled>Sidang Akhir</button>
                <button class="kp-tab" disabled>Unggah Skripsi</button>
            </div>
        </div>
    </div>

    <!-- INFORMASI UMUM -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-inline-flex align-items-center bg-white rounded shadow-sm px-3 py-2 border" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <span class="me-2 text-success">
                    <i class="fas fa-pencil-alt"></i>
                </span>
                <span class="fw-bold text-dark" style="font-size: 0.95rem;">Informasi Umum</span>
            </div>
        </div>
    </div>

    <!-- Batch I & Batch II -->
    <div class="row">
        <!-- Batch I -->
        <div class="col-lg-6 pe-lg-3 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Pengajuan Proposal Batch I</h6>
                        <button type="button" class="text-success small fw-medium" onclick="terimaJudulBatch1()">+ Terima Judul</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0" id="batch1-table">
                            <thead>
                                <tr class="text-muted small">
                                    <th style="width: 30px;">Pilih</th>
                                    <th style="width: 30px;">No.</th>
                                    <th>Dosen</th>
                                    <th>Judul</th>
                                    <th style="width: 100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($judul_dosen as $index => $jd)
                                <tr>
                                    <td><input type="checkbox" value="{{ $jd->id }}"></td>
                                    <td class="text-muted small">{{ $index + 1 }}.</td>
                                    <td class="small"><strong>{{ $jd->dosen }}</strong></td>
                                    <td class="small text-muted">{{ $jd->judul }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm w-100 rounded-pill"
                                                style="font-size: 0.765rem; padding: 0.25rem 0;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#mahasiswaModal"
                                                onclick="showMahasiswa({{ $jd->id }}, '{{ addslashes($jd->dosen) }}', '{{ addslashes($jd->judul) }}')">
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

        <!-- Batch II (tidak diubah) -->
        <div class="col-lg-6 ps-lg-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Pengajuan Proposal Batch II</h6>
                        <button type="button" class="text-success small fw-medium" onclick="terimaJudulBatch2()">+ Terima Judul</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" id="batch2-table">
                            <thead>
                                <tr class="text-muted small">
                                    <th style="width: 30px;">Pilih</th>
                                    <th style="width: 30px;">No.</th>
                                    <th>Mahasiswa</th>
                                    <th>Judul</th>
                                    <th>Dokumen</th>
                                    <th>Pendaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($judul_mahasiswa as $index => $jm)
                                <tr>
                                    <td><input type="checkbox" value="{{ $jm->id }}"></td>
                                    <td class="small">{{ $index + 1 }}.</td>
                                    <td class="small">{{ $jm->created_by }}</td>
                                    <td class="small">{{ $jm->judul }}</td>
                                    <td class="small">
                                        @if($jm->file)
                                            <a href="{{ asset('storage/' . $jm->file) }}" download>Download</a>
                                        @else
                                            Tidak ada
                                        @endif
                                    </td>
                                    <td class="small">{{ $jm->transaksi->count() }} Dosen</td>
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

<!-- MODAL: Mahasiswa Tertarik Pada Judul Dosen -->
<div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Mahasiswa Tertarik Pada Judul Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="px-4 py-3">No.</th>
                                <th class="px-4 py-3">Dosen</th>
                                <th class="px-4 py-3">Judul</th>
                                <th class="px-4 py-3 text-center">Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody id="modal-body">
                            <!-- Data diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showMahasiswa(judulId, dosen, judul) {
    // Ambil data mahasiswa tertarik dari data yang dikirim dari controller
    const mahasiswaData = @json($judul_dosen->pluck('interested_students', 'id'));

    const data = mahasiswaData[judulId] || [];

    let html = '';
    if (data.length === 0) {
        html = `<tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada mahasiswa yang mengambil judul ini.</td></tr>`;
    } else {
        data.forEach((m, i) => {
            html += `
                <tr>
                    <td class="px-4 py-3 small text-muted">${i + 1}.</td>
                    <td class="px-4 py-3 small"><strong>${dosen}</strong></td>
                    <td class="px-4 py-3 small text-muted">${judul}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="badge bg-light text-dark border">${m}</span>
                    </td>
                </tr>
            `;
        });
    }

    document.getElementById('modal-body').innerHTML = html;
}

function terimaJudulBatch1() {
    const checkboxes = document.querySelectorAll('#batch1-table input[type="checkbox"]:checked');
    if (checkboxes.length === 0) return alert('Pilih judul terlebih dahulu.');

    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    fetch('/koordinator/terima-judul-batch1', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ ids: selectedIds })
    }).then(r => r.json()).then(d => {
        alert(d.message);
        location.reload();
    });
}

function terimaJudulBatch2() {
    const checkboxes = document.querySelectorAll('#batch2-table input[type="checkbox"]:checked');
    if (checkboxes.length === 0) return alert('Pilih judul terlebih dahulu.');

    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    fetch('/koordinator/terima-judul-batch2', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ ids: selectedIds })
    }).then(r => r.json()).then(d => {
        alert(d.message);
        location.reload();
    });
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

    .card { border-radius: 12px; overflow: hidden; }
    .table-sm td, .table-sm th { padding: 0.4rem 0.5rem; vertical-align: middle; }
    .btn-sm { font-size: 0.765rem; padding: 0.25rem 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; }
    .d-inline-flex { box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: 1px solid #e0e0e0; }

    .badge {
        font-size: 0.7rem;
        padding: 0.35em 0.6em;
    }

    @media (max-width: 992px) {
        .pe-lg-3, .ps-lg-3 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
        .d-inline-flex { display: block; width: fit-content; margin: 0 auto 1.5rem; }
    }
</style>
@endsection