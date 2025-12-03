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



<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Pelaksanaan MBKM Mitra</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Mahasiswa</th>
                                <th>Perusahaan MBKM</th>
                                <th>Minggu</th>
                                <th>Deskripsi Kegiatan</th>
                                <th>Bimbingan</th>
                                <th>Matakuliah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelaksanaans as $index => $pelaksanaan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pelaksanaan->mahasiswa->nama ?? 'N/A' }}</td>
                                <td>{{ $companies[$pelaksanaan->mahasiswa_id] ?? 'N/A' }}</td>
                                <td>{{ $pelaksanaan->minggu }}</td>
                                <td>{{ $pelaksanaan->deskripsi_kegiatan }}</td>
                                <td>{{ $pelaksanaan->bimbingan ?? '-' }}</td>
                                <td>{{ $pelaksanaan->matkul }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Data belum tersedia</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
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
                                <th>Minggu</th>
                                <th>Deskripsi Kegiatan</th>
                                <th>Bimbingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Data belum tersedia</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
