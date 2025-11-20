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
            <a href="{{ url('/mbkm/pendaftaran-nonmitra-mhs') }}" class="kp-tab active">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-mhs')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-mhs')}}" class="kp-tab">Seminar MBKM</a>
        </div>
    </div>
</div>

<div class="row">
    <!-- mbkm non mitra -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
            <h4 class="fw-bold mb-4">MBKM Non-Mitra</h4>

            <div class="mb-3">
                <label class="fw-bold">Mitra</label>
                <select class="form-select">
                    <option>MBKM Non-Mitra</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Nama Perusahaan</label>
                <input type="text" class="form-control" placeholder="Ketik masa MBKM" disabled>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Posisi MBKM</label>
                <input type="text" class="form-control" placeholder="Posisi" disabled>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Unggah LOA</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="--Unggah file--" disabled>
                    <button class="btn btn-secondary" disabled>Unggah</button>
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Unggah Proposal</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="--Unggah file--" disabled>
                    <button class="btn btn-secondary" disabled>Unggah</button>
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Masa MBKM</label>
                <input type="text" class="form-control" placeholder="Ketik masa MBKM" disabled>
            </div>

            <div class="mb-4">
                <label class="fw-bold">Matakuliah Ekuivalensi</label><br>
                <label class="me-3"><input type="radio" name="ekuivalensi"> Ya</label>
                <label><input type="radio" name="ekuivalensi"> Tidak</label>
            </div>

            <button class="btn btn-dark px-4">Daftar</button>
        </div>
    </div>

    <!-- input konversi mk -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
            <h5 class="fw-bold mb-4 px-3 py-2 text-white"
                style="background:#1c1b3b; display:inline-block; border-radius:12px;">
                Konversi MK
            </h5>

            <div class="mb-3">
                <label class="fw-bold">Pilih MK</label>
                <select class="form-select" style="max-width: 200px;">
                    <option>MK Konversi</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold">CPMK 1</label>
                <p class="text-muted">Mahasiswa memahami cara untuk</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">CPMK 2</label>
                <p class="text-muted">Mahasiswa memahami cara untuk</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">CPMK 3</label>
                <p class="text-muted">Mahasiswa memahami cara untuk</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Deskripsi Kegiatan</label>
                <textarea class="form-control" rows="3" placeholder="Deskripsi kegiatan selama MBKM"></textarea>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Alokasi Waktu</label>
                <div class="input-group" style="max-width: 200px;">
                    <input type="text" class="form-control">
                    <span class="input-group-text">hr</span>
                </div>
            </div>

            <a href="#" class="small text-primary mb-2 d-block">+ Tambah MK</a>

            <button class="btn btn-dark px-4">Simpan</button>
        </div>
    </div>
</div>

<!-- konversi mk -->
<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">

            <h5 class="fw-bold mb-4 px-3 py-2 text-white"
                style="background:#1c1b3b; display:inline-block; border-radius:12px;">
                Konversi MK
            </h5>

            <table class="table align-middle">
                <tbody>
                    <tr>
                        <td>EKOTEK</td>
                        <td><span class="badge bg-secondary">Menunggu</span></td>
                        <td>WMS</td>
                    </tr>
                    <tr>
                        <td>PMB</td>
                        <td><span class="badge bg-success">Diterima</span></td>
                        <td>NSS</td>
                    </tr>
                    <tr>
                        <td>TEKKEU</td>
                        <td>
                            <span class="badge bg-danger">Ditolak</span>
                            <span class="badge bg-warning text-dark">Perbaiki</span>
                        </td>
                        <td>WMS</td>
                    </tr>
                </tbody>
            </table>

            <div class="fw-bold mt-3">
                Total SKS Konversi: <span class="text-dark">6 SKS</span>
            </div>

        </div>
    </div>
</div>
@endsection