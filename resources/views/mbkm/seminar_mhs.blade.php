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
            <button class="kp-tab">Informasi Umum</button>
            <a href="{{ url('/mbkm/pendaftaran-mhs') }}" class="kp-tab">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-mhs')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-mhs')}}" class="kp-tab active">Seminar MBKM</a>
        </div>
    </div>
</div>

<!-- informasi mbkm mahasiswa -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-4">
            <!-- Info Kiri -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <small class="text-muted">Nama/NIM</small>
                        <h6 class="fw-bold mb-0">Nama Mahasiswa</h6>
                        <small class="text-muted">Fakultas / 123456789</small>
                        <small class="text-muted d-block mt-1">Anggota Kelompok: Anggota 1, Anggota 2</small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <small class="text-muted">Posisi (Divisi)</small>
                        <h6 class="fw-bold mb-0">Divisi Contoh</h6>
                    </div>
                </div>
            </div>

            <!-- Info Kanan -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <small class="text-muted">Perusahaan</small>
                        <h6 class="fw-bold mb-0">Nama Perusahaan</h6>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <small class="text-muted">Pembimbing</small>
                        <h6 class="fw-bold mb-0">Nama Pembimbing</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- laporan mbkm -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-4">
                    <i class="mdi mdi-upload"></i> Unggah Laporan MBKM
                </h5>

                <div class="mb-4">
                    <label class="fw-bold">Apakah kegiatan termasuk magang?</label><br>
                    <label class="me-3"><input type="radio" name="magang"> Ya</label>
                    <label><input type="radio" name="magang"> Tidak</label>
                </div>

                <!-- ===================== -->
                <!-- Laporan EKOTEK -->
                <!-- ===================== -->
                <div class="mb-5">
                    <h6 class="fw-bold">Laporan EKOTEK</h6>
                    <small class="text-muted d-block mb-2">CPMK</small>

                    <textarea class="form-control mb-3" rows="4" disabled>
Perhitungan biaya aset produk dengan metode Job Analysis
                    </textarea>

                    <label class="fw-bold d-block mb-1">Unggah laporan MK 1</label>
                    <div class="input-group mb-2" style="max-width: 350px;">
                        <input type="text" class="form-control" placeholder="Pilih laporan anda" disabled>
                        <span class="input-group-text">Browse</span>
                    </div>

                    <a href="#" class="small text-primary d-block mb-3">Template laporan</a>

                    <button class="btn btn-dark px-4">Submit</button>
                </div>

                <hr>

                <!-- ===================== -->
                <!-- Laporan PMB -->
                <!-- ===================== -->
                <div class="mt-4 mb-5">
                    <h6 class="fw-bold">Laporan PMB</h6>
                    <small class="text-muted d-block mb-2">CPMK</small>

                    <textarea class="form-control mb-3" rows="4" disabled>
Perhitungan biaya aset produk dengan metode Job Analysis
                    </textarea>

                    <label class="fw-bold d-block mb-1">Unggah laporan MK 2</label>
                    <div class="input-group mb-2" style="max-width: 350px;">
                        <input type="text" class="form-control" placeholder="Pilih laporan anda" disabled>
                        <span class="input-group-text">Browse</span>
                    </div>

                    <a href="#" class="small text-primary d-block mb-3">Template laporan</a>

                    <button class="btn btn-dark px-4">Submit</button>
                </div>

                <div class="text-end">
                    <button class="btn btn-dark px-5">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    <!-- unduh jadwal -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <span class="px-4 py-2 text-white fw-bold"
                          style="background:#1c1b3b; border-radius: 12px;">
                        Jadwal Seminar <i class="mdi mdi-calendar"></i>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Unduh Jadwal</span>
                    <a href="#" class="text-primary">Doc</a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection








