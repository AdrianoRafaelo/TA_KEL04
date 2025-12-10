@extends('layouts.app')

@section('title', 'Mahasiswa TA')

@section('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            icon: 'success',
            title: '{{ session("success") }}'
        });
    @endif
</script>
@endsection

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
                <a href="{{ route('koordinator.semhas') }}" class="kp-tab">Seminar Hasil</a>
                <a href="{{ route('koordinator.sidang') }}" class="kp-tab">Sidang Akhir</a>
                <a href="{{ url('/koordinator-skripsi') }}" class="kp-tab">Unggah Skripsi</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Mahasiswa Tugas Akhir 
            </button>
        </div>
    </div>

    <!-- Form untuk menyimpan data -->
    <form method="POST" action="{{ route('koordinator.save.mahasiswa.ta') }}">
        @csrf
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
                        <td class="fw-semibold">{{ $title->nama }} @if($title->nim) ({{ $title->nim }}) @endif</td>
                        <td>{{ $title->judul }}</td>

                        <!-- Pembimbing -->
                        <td>
                            <input type="hidden" name="titles[{{ $title->id }}][id]" value="{{ $title->id }}">
                            <select class="form-select form-select-sm" name="titles[{{ $title->id }}][pembimbing]">
                                <option value="">Pilih Dosen</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->nama }}" {{ $title->mahasiswaTugasAkhir ? ($title->mahasiswaTugasAkhir->pembimbing == $lecturer->nama ? 'selected' : '') : '' }}>
                                        {{ $lecturer->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <!-- Pengulas I -->
                        <td>
                            <select class="form-select form-select-sm" name="titles[{{ $title->id }}][pengulas1]">
                                <option value="">Pilih Dosen</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->nama }}" {{ $title->mahasiswaTugasAkhir ? ($title->mahasiswaTugasAkhir->pengulas_1 == $lecturer->nama ? 'selected' : '') : '' }}>
                                        {{ $lecturer->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <!-- Pengulas II -->
                        <td>
                            <select class="form-select form-select-sm" name="titles[{{ $title->id }}][pengulas2]">
                                <option value="">Pilih Dosen</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->nama }}" {{ $title->mahasiswaTugasAkhir ? ($title->mahasiswaTugasAkhir->pengulas_2 == $lecturer->nama ? 'selected' : '') : '' }}>
                                        {{ $lecturer->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
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
