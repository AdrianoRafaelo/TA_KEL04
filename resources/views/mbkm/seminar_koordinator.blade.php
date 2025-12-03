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
            <a href="{{ url('/mbkm/pendaftaran-koordinator') }}" class="kp-tab">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-koordinator')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-koordinator')}}" class="kp-tab active">Seminar MBKM</a>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Pelaksanaan MBKM</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Mahasiswa</th>
                                <th>Perusahaan MBKM</th>
                                <th>Laporan MBKM</th>
                                <th>Matakuliah Konversi</th>
                                <th>Jadwal Seminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seminars as $index => $seminar)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $seminar->mahasiswa->nama ?? 'N/A' }} ({{ $seminar->mahasiswa->username ?? 'N/A' }})</td>
                                <td>{{ $companies[$seminar->mahasiswa_id] ?? 'N/A' }}</td>
                                <td>
                                    @if($seminar->laporan_ekotek_file || $seminar->laporan_pmb_file)
                                        <span class="badge bg-success">Ada</span>
                                    @else
                                        <span class="badge bg-warning">Belum</span>
                                    @endif
                                </td>
                                <td>{{ $konversiCounts[$seminar->mahasiswa_id] ?? 0 }}</td>
                                <td>
                                    @if($seminar->jadwal_seminar_file)
                                        <a href="#" class="text-primary">Download</a>
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Data belum tersedia</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
