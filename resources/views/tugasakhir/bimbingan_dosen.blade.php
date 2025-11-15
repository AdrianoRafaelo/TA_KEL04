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
                                                onclick="showDetail('{{ $m->nama }}', '{{ addslashes($m->judul) }}', '{{ $m->nim }}', '{{ $m->prodi }}')">
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
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Detail Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small text-muted">Nama</label>
                        <p class="mb-0 fw-medium" id="modal-nama">-</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-muted">NIM</label>
                        <p class="mb-0" id="modal-nim">-</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-muted">Program Studi</label>
                        <p class="mb-0" id="modal-prodi">-</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-muted">Judul TA</label>
                        <p class="mb-0 small" id="modal-judul">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showDetail(nama, judul, nim, prodi) {
    document.getElementById('modal-nama').textContent = nama;
    document.getElementById('modal-nim').textContent = nim;
    document.getElementById('modal-prodi').textContent = prodi;
    document.getElementById('modal-judul').textContent = judul;
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