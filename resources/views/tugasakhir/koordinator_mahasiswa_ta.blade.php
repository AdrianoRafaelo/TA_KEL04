@extends('layouts.app')

@section('title', 'Mahasiswa TA')

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mahasiswa TA</li>
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
                <a href="{{ route('koordinator.mahasiswa.ta') }}" class="kp-tab active">Mahasiswa TA</a>
                <a href="{{ route('koordinator.sempro') }}" class="kp-tab">Seminar Proposal</a>
                <button class="kp-tab" disabled>Seminar Hasil</button>
                <button class="kp-tab" disabled>Sidang Akhir</button>
                <button class="kp-tab" disabled>Unggah Skripsi</button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Mahasiswa Tugas Akhir Angkatan 2020
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th style="width: 150px;">Mahasiswa</th>
                    <th style="width: 420px;">Judul</th>
                    <th style="width: 120px;">Pembimbing</th>
                    <th style="width: 130px;">Pengulas I</th>
                    <th style="width: 130px;">Pengulas II</th>
                </tr>
            </thead>
            <tbody style="font-size:14px; color:#111;">
                @foreach($accepted_titles as $index => $title)
                <tr class="hover-row">
                    <td>{{ $loop->iteration }}.</td>
                    <td class="fw-semibold">{{ $title->created_by }}</td>
                    <td>{{ $title->judul }}</td>

                    <!-- Pembimbing -->
                    <td>
                        @if($title->pembimbing)
                            <span>{{ $title->pembimbing }}</span>
                        @else
                            <select class="form-select form-select-sm">
                                <option value="">Pilih Dosen</option>
                                <option>ISW</option>
                                <option>SHT</option>
                                <option>ANA</option>
                            </select>
                        @endif
                    </td>

                    <!-- Pengulas I -->
                    <td>
                        @if($title->pengulas1)
                            <span>{{ $title->pengulas1 }}</span>
                        @else
                            <select class="form-select form-select-sm">
                                <option value="">Pilih Dosen</option>
                                <option>HSS</option>
                                <option>JBJ</option>
                                <option>ANA</option>
                            </select>
                        @endif
                    </td>

                    <!-- Pengulas II -->
                    <td>
                        @if($title->pengulas2)
                            <span>{{ $title->pengulas2 }}</span>
                        @else
                            <select class="form-select form-select-sm">
                                <option value="">Pilih Dosen</option>
                                <option>SAM</option>
                                <option>WMS</option>
                                <option>NSS</option>
                            </select>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-success">Simpan</button>
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

    /* Dropdown styling */
    select.form-select-sm {
        font-size: 13px;
        border: 1px solid #ddd;
    }
</style>
@endsection
