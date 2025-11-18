@extends('layouts.app')

@section('title', 'Seminar KP')

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Kerja Praktik</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Seminar KP</li>
                </ol>
            </nav>
            <h4 class="mb-0">Seminar KP</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/kerja-praktik-dosen') }}" class="kp-tab">Pelaksanaan KP</a>
                <a href="{{ url('/kerja-praktik-dosen-seminar') }}" class="kp-tab active">Seminar KP</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Seminar KP Mahasiswa
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

    <!-- Table -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th style="width: 200px;">Mahasiswa (Anggota Kelompok)</th>
                    <th style="width: 200px;">Perusahaan KP</th>
                    <th style="width: 250px;">Topik Khusus</th>
                    <th style="width: 120px;">Dosen Pembimbing</th>
                    <th style="width: 120px;">Dosen Penguji</th>
                    <th style="width: 150px;">Jadwal Seminar</th>
                </tr>
            </thead>
            <tbody style="font-size:14px; color:#111;">
                @forelse($seminarKp as $index => $seminar)
                <tr class="hover-row">
                    <td>{{ $index + 1 }}.</td>
                    <td>
                        <div>{{ $seminar->nama }} ({{ $seminar->nim }})</div>
                        @if(!empty($seminar->anggota_kelompok))
                            @foreach($seminar->anggota_kelompok as $member)
                                <div>{{ $member }}</div>
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $seminar->perusahaan }}</td>
                    <td>{{ $seminar->topik_khusus }}</td>
                    <td>{{ $seminar->pembimbing }}</td>
                    <td>{{ $seminar->penguji }}</td>
                    <td>
                        @if($seminar->jadwal_seminar)
                            <a href="{{ route('kp.seminar.download', basename($seminar->jadwal_seminar)) }}" target="_blank" class="text-primary"><i class="fas fa-file-pdf"></i> Lihat Jadwal</a>
                        @else
                            Belum ada jadwal
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada mahasiswa seminar KP.</td>
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

    .kp-tabs .kp-tab {
        background: #e9ecef;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 14px;
        color: #555;
    }

    .kp-tabs .kp-tab.active {
        background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        color: white;
        font-weight: 600;
    }
</style>
@endsection