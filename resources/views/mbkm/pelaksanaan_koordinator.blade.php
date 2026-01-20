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
            <a href="{{ url('/mbkm/pendaftaran-koordinator') }}" class="kp-tab ">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-koordinator')}}" class="kp-tab active">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-koordinator')}}" class="kp-tab">Seminar MBKM</a>
        </div>
    </div>
</div>



<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
            Pelaksanaan MBKM Koordinator
        </button>
    </div>
</div>

<div class="container-fluid py-4">

<!-- Pelaksanaan MBKM Non-Mitra Table -->
<div class="table-responsive bg-white shadow-sm rounded p-3 mb-4">
    <h6 class="mb-3 text-primary fw-bold">Pelaksanaan MBKM Non-Mitra</h6>
    <table class="table align-middle mb-0">
        <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
            <tr>
                <th style="width: 40px;" class="text-center">No.</th>
                <th style="width: 200px;">Mahasiswa</th>
                <th style="width: 250px;">Perusahaan MBKM</th>
                <th style="width: 80px;" class="text-center">Minggu</th>
                <th style="width: 300px;">Deskripsi Kegiatan</th>
                <th style="width: 150px;">Bimbingan</th>
                <th style="width: 150px;">Matakuliah</th>
            </tr>
        </thead>
        <tbody style="font-size:14px; color:#111;">
            @forelse($pelaksanaansNonmitra as $index => $pelaksanaan)
            <tr>
                <td class="text-center">{{ $index + 1 }}.</td>
                <td>{{ $pelaksanaan->mahasiswa->nama ?? 'N/A' }}</td>
                <td>{{ $companiesNonmitra[$pelaksanaan->mahasiswa_id] ?? 'N/A' }}</td>
                <td class="text-center">{{ $pelaksanaan->minggu }}</td>
                <td>{{ $pelaksanaan->deskripsi_kegiatan }}</td>
                <td>{{ $pelaksanaan->bimbingan ?? '-' }}</td>
                <td>{{ $pelaksanaan->matkul }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data pelaksanaan non-mitra.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pelaksanaan MBKM Mitra Table -->
<div class="table-responsive bg-white shadow-sm rounded p-3 mb-4">
    <h6 class="mb-3 text-primary fw-bold">Pelaksanaan MBKM Mitra</h6>
    <table class="table align-middle mb-0">
        <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
            <tr>
                <th style="width: 40px;" class="text-center">No.</th>
                <th style="width: 200px;">Mahasiswa</th>
                <th style="width: 250px;">Perusahaan MBKM</th>
                <th style="width: 80px;" class="text-center">Minggu</th>
                <th style="width: 300px;">Deskripsi Kegiatan</th>
                <th style="width: 150px;">Bimbingan</th>
            </tr>
        </thead>
        <tbody style="font-size:14px; color:#111;">
            @forelse($pelaksanaansMitra as $index => $pelaksanaan)
            <tr>
                <td class="text-center">{{ $index + 1 }}.</td>
                <td>{{ $pelaksanaan->mahasiswa->nama ?? 'N/A' }}</td>
                <td>{{ $companiesMitra[$pelaksanaan->mahasiswa_id] ?? 'N/A' }}</td>
                <td class="text-center">{{ $pelaksanaan->minggu }}</td>
                <td>{{ $pelaksanaan->deskripsi_kegiatan }}</td>
                <td>{{ $pelaksanaan->bimbingan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data pelaksanaan mitra.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>

@endsection
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

    .btn-success {
        transition: all 0.25s ease;
    }
    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4) !important;
    }
</style>
