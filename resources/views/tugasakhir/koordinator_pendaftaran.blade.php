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
                <a href="{{ url('/koordinator-pendaftaran') }}" class="kp-tab">Pendaftaran Judul</a>
                <a href="{{ route('koordinator.mahasiswa.ta') }}" class="kp-tab">Mahasiswa TA</a>
                <a href="{{ route(name: 'koordinator.sempro') }}" class="kp-tab">Seminar Proposal</a>
                <button class="kp-tab" disabled>Seminar Hasil</button>
                <button class="kp-tab" disabled>Sidang Akhir</button>
                <button class="kp-tab" disabled>Unggah Skripsi</button>
            </div>
        </div>
    </div>

    <!-- INFORMASI UMUM — KOTAK KECIL + JARAK KE BAWAH -->
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

    <!-- Batch I & Batch II — BERDAMPINGAN -->
    <div class="row">
        <!-- Batch I -->
        <div class="col-lg-6 pe-lg-3 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Pengajuan Proposal Batch I</h6>
                        <a href="#" class="text-success small fw-medium">+ Terima Judul</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th style="width: 30px;">No.</th>
                                    <th>Dosen</th>
                                    <th>Judul</th>
                                    <th style="width: 100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($judul_dosen as $index => $jd)
                                <tr>
                                    <td class="text-muted small">{{ $index + 1 }}.</td>
                                    <td class="small">
                                        <strong>{{ $jd->dosen }}</strong>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $jd->judul }}
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm w-100 rounded-pill" style="font-size: 0.765rem; padding: 0.25rem 0;">Selengkapnya</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batch II -->
        <div class="col-lg-6 ps-lg-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Pengajuan Proposal Batch II</h6>
                        <a href="#" class="text-success small fw-medium">+ Terima Judul</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr class="text-muted small">
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
                                    <td class="small">{{ $index + 1 }}.</td>
                                    <td class="small">{{ $jm->created_by }}</td>
                                    <td class="small">
                                        {{ $jm->judul }}
                                    </td>
                                    <td class="small">
                                        @if($jm->file)
                                            <a href="{{ asset('storage/' . $jm->file) }}" download>Download</a>
                                        @else
                                            Tidak ada
                                        @endif
                                    </td>
                                    <td class="small">
                                        {{ $jm->transaksi->count() }} Dosen
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
        padding: 0.4rem 0.5rem;
        vertical-align: middle;
    }

    .btn-sm {
        font-size: 0.765rem;
        padding: 0.25rem 0.5rem;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .d-inline-flex {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
    }

    @media (max-width: 992px) {
        .pe-lg-3, .ps-lg-3 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        .d-inline-flex {
            display: block;
            width: fit-content;
            margin: 0 auto 1.5rem;
        }
    }
</style>
@endsection