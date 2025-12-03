@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">MBKM</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pelaksanaan</li>
                </ol>
            </nav>
            <h4 class="mb-0">Pelaksanaan MBKM</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/mbkm/dosen-konversi-matkul') }}" class="kp-tab">Konversi Matakuliah</a>
                <a href="{{ url('/mbkm/dosen-pelaksanaan')}}" class="kp-tab active">Pelaksanaan MBKM</a>
                <a href="{{ url('/mbkm/dosen-seminar')}}" class="kp-tab">Seminar MBKM</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Pelaksanaan MBKM Dosen
            </button>
        </div>
    </div>

    <div class="container-fluid py-4">

    <!-- MBKM non-Pertukaran Pelajar / Konversi Matakuliah -->
    <div class="table-responsive bg-white shadow-sm rounded p-3 mb-4">
        <h6 class="mb-3 text-primary fw-bold">MBKM non-Pertukaran Pelajar / Konversi Matakuliah</h6>
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;" class="text-center">No.</th>
                    <th style="width: 200px;">Mahasiswa</th>
                    <th style="width: 250px;">Perusahaan MBKM</th>
                    <th style="width: 100px;" class="text-center">Minggu</th>
                    <th style="width: 250px;">Deskripsi Kegiatan</th>
                    <th style="width: 150px;">Bimbingan</th>
                </tr>
            </thead>
            <tbody style="font-size:14px; color:#111;">
                <tr class="hover-row">
                    <td class="text-center">1.</td>
                    <td class="fw-semibold">Sahalis Simboling</td>
                    <td>PT. Kineva Systrans Media</td>
                    <td class="text-center">W1</td>
                    <td>Lorem ipsum</td>
                    <td>Lorem ipsum</td>
                </tr>
                <tr class="hover-row">
                    <td class="text-center">2.</td>
                    <td class="fw-semibold">Willy Silaen</td>
                    <td>PT. Kineva Systrans Media</td>
                    <td class="text-center">W1</td>
                    <td>Lorem ipsum</td>
                    <td>Lorem ipsum</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mahasiswa Bimbingan -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <h6 class="mb-3 text-primary fw-bold">Mahasiswa Bimbingan</h6>
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;" class="text-center">No.</th>
                    <th style="width: 250px;">Mahasiswa</th>
                    <th style="width: 350px;">Lokasi MBKM</th>
                    <th style="width: 150px;" class="text-center">Selengkapnya</th>
                </tr>
            </thead>
            <tbody style="font-size:14px; color:#111;">
                <tr class="hover-row">
                    <td class="text-center">1.</td>
                    <td class="fw-semibold">Hansel Septiyan Pasaribu</td>
                    <td>PT. MRT Jakarta Perseroda</td>
                    <td class="text-center">
                        <button class="btn btn-success btn-sm rounded-pill px-4 shadow-sm"
                                style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
                                       border: none; font-weight: 500;">
                            Lihat
                        </button>
                    </td>
                </tr>
                <tr class="hover-row">
                    <td class="text-center">2.</td>
                    <td class="fw-semibold">Yuni Magdalena Sinaga</td>
                    <td>PT. DanLiris</td>
                    <td class="text-center">
                        <button class="btn btn-success btn-sm rounded-pill px-4 shadow-sm"
                                style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
                                       border: none; font-weight: 500;">
                            Lihat
                        </button>
                    </td>
                </tr>
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