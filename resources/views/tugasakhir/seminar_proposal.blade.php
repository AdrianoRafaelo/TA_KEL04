@extends('layouts.app')

@section('title', 'Seminar Proposal')

@section('content')
<div class="container-fluid py-3">
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
            <h4 class="mb-0">Seminar Proposal</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ route('ta-dosen') }}" class="kp-tab">Pendaftaran TA</a>
                <button class="kp-tab active">Seminar Proposal</button>
                <a href="{{ route('seminar.hasil.dosen') }}" class="kp-tab">Seminar Hasil</a>
                <a href="{{ route('sidang.akhir.dosen') }}" class="kp-tab">Sidang Akhir</a>
                <a href="{{ route('bimbingan.dosen') }}" class="kp-tab">Bimbingan</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Seminar Proposal Mahasiswa
            </button>
        </div>
        <div>
            <div class="dropdown d-inline-block">
                <button class="btn btn-dark dropdown-toggle" type="button" id="jadwalSeminarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-calendar-alt me-2"></i> Jadwal Seminar
                </button>
                <ul class="dropdown-menu" aria-labelledby="jadwalSeminarDropdown">
                    <li><a class="dropdown-item text-dark" href="#" onclick="alert('Fitur unduh jadwal sedang dikembangkan.')" style="background-color: transparent !important;">Unduh Jadwal .pdf</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Dosen Pembimbing Table -->
    <div class="table-responsive bg-white shadow-sm rounded p-3 mb-4">
        <h6 class="mb-3 text-primary fw-bold">Dosen Pembimbing</h6>
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th style="width: 150px;">Mahasiswa</th>
                    <th style="width: 420px;">Judul</th>
                    <th style="width: 120px;">Pembimbing</th>
                    <th style="width: 130px;">Pengulas I</th>
                    <th style="width: 130px;">Pengulas II</th>
                    <th style="width: 100px;">Dokumen</th>
                </tr>
            </thead>
            <tbody style="font-size:14px; color:#111;">
                @forelse($pembimbingAssignments as $index => $assignment)
                <tr class="hover-row">
                    <td>{{ $index + 1 }}.</td>
                    <td class="fw-semibold">{{ $assignment->nama }} ({{ $assignment->nim }})</td>
                    <td>{{ $assignment->judul }}</td>
                    <td>{{ $assignment->pembimbing }}</td>
                    <td>{{ $assignment->pengulas_1 }}</td>
                    <td>{{ $assignment->pengulas_2 }}</td>
                    <td>
                        @if($assignment->seminarProposal && $assignment->seminarProposal->file_proposal)
                            <a href="{{ asset('storage/' . $assignment->seminarProposal->file_proposal) }}" target="_blank" class="text-primary"><i class="fas fa-file-pdf"></i> Dokumen</a>
                        @else
                            Belum upload
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada mahasiswa yang di-assign sebagai pembimbing.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Dosen Penguji Table -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <h6 class="mb-3 text-primary fw-bold">Dosen Penguji</h6>
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th style="width: 150px;">Mahasiswa</th>
                    <th style="width: 420px;">Judul</th>
                    <th style="width: 120px;">Pembimbing</th>
                    <th style="width: 130px;">Pengulas I</th>
                    <th style="width: 130px;">Pengulas II</th>
                    <th style="width: 100px;">Dokumen</th>
                </tr>
            </thead>
            <tbody style="font-size:14px; color:#111;">
                @forelse($pengujiAssignments as $index => $assignment)
                <tr class="hover-row">
                    <td>{{ $index + 1 }}.</td>
                    <td class="fw-semibold">{{ $assignment->mahasiswa }}</td>
                    <td>{{ $assignment->judul }}</td>
                    <td>{{ $assignment->pembimbing }}</td>
                    <td>{{ $assignment->pengulas_1 }}</td>
                    <td>{{ $assignment->pengulas_2 }}</td>
                    <td>
                        @if($assignment->seminarProposal && $assignment->seminarProposal->file_proposal)
                            <a href="{{ asset('storage/' . $assignment->seminarProposal->file_proposal) }}" target="_blank" class="text-primary"><i class="fas fa-file-pdf"></i> Dokumen</a>
                        @else
                            Belum upload
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada mahasiswa yang di-assign sebagai penguji.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tambahan CSS -->
<style>
    /* Hilangkan border bawaan */
    table, th, td {
        border: none !important;
    }

    /* Garis pembatas antar mahasiswa */
    tbody tr:not(:last-child) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    /* Hover efek lembut dan sedikit mengangkat */
    .hover-row {
        transition: all 0.25s ease;
    }

    .hover-row:hover {
        background-color: #f9fafb !important;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Supaya garis tidak sampai ujung */
    .table-responsive {
        padding-left: 15px;
        padding-right: 15px;
    }
</style>
@endsection