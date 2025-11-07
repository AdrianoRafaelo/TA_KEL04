@extends('layouts.app')

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
                <a href="{{ route('koordinator.mahasiswa.ta') }}" class="kp-tab">Mahasiswa TA</a>
                <a href="{{ route('koordinator.sempro') }}" class="kp-tab active">Seminar Proposal</a>
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
                Seminar Proposal
            </button>
            <a href="#" class="text-primary small ms-2">+ Terima Seminar Proposal</a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th style="width: 130px;">Mahasiswa</th>
                    <th style="width: 420px;">Judul</th>
                    <th style="width: 120px;">Dokumen</th>
                    <th style="width: 90px;">Pembimbing</th>
                    <th style="width: 130px;">Pengulas I</th>
                    <th style="width: 130px;">Pengulas II</th>
                    <th style="width: 150px;">Jadwal Seminar</th>
                    <th style="width: 100px;">Hasil</th>
                </tr>
            </thead>
            <tbody style="font-size:14px;">
                @php
                    $data = [
                        ['Kevin Pakpahan', 'Analisis Pengaruh Electronic Word of Mouth di daerah Pariwisata Kawasan Danau Toba', 'Doc A', 'ISW'],
                        ['Yosef Pakpahan', 'Perancangan sistem pendukung keputusan pemilihan mata kuliah pilihan program studi sarjana Institut Teknologi Del dengan pengimplementasian berbasis website/database', 'Doc B', 'ISW'],
                        ['Sharon Ruth Esterina Simanjuntak', 'Consumer Behavior in Digital Marketing', 'Doc A', 'SHT'],
                        ['Grace Vitani Pardosi', 'Strategic Innovation in Social Media Marketing', 'Doc B', 'SHT'],
                        ['Gabriel Sahat Nicolas', 'Analisis Faktor Adopsi Kendaraan Listrik di Sumatera Utara dengan menggunakan pemodelan UTAUT/TEM', 'Doc A', 'ISW'],
                        ['Gomgom G. S. Tua Marpaung', 'Analisis Hubungan Storescape (Faktor Lingkungan Fisik dan Social Retail’s) dengan Loyalitas Pelanggan', 'Doc B', 'ANA'],
                    ];
                @endphp

                @foreach($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $row[0] }}</td>
                    <td>{{ $row[1] }}</td>
                    <td><i class="bi bi-file-earmark-text me-1"></i>{{ $row[2] }}</td>
                    <td>{{ $row[3] }}</td>
                    <td>
                        <select class="form-select form-select-sm">
                            <option selected>Pilih Dosen</option>
                            <option>Dosen A</option>
                            <option>Dosen B</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-select form-select-sm">
                            <option selected>Pilih Dosen</option>
                            <option>Dosen A</option>
                            <option>Dosen B</option>
                        </select>
                    </td>
                    <td><span class="text-muted">Unggah dokumen</span></td>
                    <td><button class="btn btn-sm btn-success">Selengkapnya</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer Button -->
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-success">Simpan</button>
    </div>
</div>
@endsection
