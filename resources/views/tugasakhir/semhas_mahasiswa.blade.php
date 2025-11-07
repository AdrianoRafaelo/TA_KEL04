@extends('layouts.app')

@section('content')
<style>
  body {
    background-color: #f7f8fb;
  }

  /* Tabs */
  .kp-tabs {
    display: flex;
    justify-content: flex-start;
    gap: 10px;
    margin-bottom: 25px;
  }
  .kp-tab {
    padding: 10px 18px;
    background: #eee;
    color: #444;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
  }
  .kp-tab:hover {
    background: #dcdcdc;
  }
  .kp-tab.active {
    background: white;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  }
  .kp-tab.disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Header Info */
  .info-header {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 20px 24px;
    margin-bottom: 25px;
  }
  .info-header th {
    width: 160px;
    color: #444;
  }

  /* Container utama */
  .section-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  .section-left {
    flex: 1;
    min-width: 380px;
  }
  .section-right {
    flex: 1;
    min-width: 380px;
  }

  /* Card */
  .card {
    background: #fff !important;
    border: none !important;
    border-radius: 12px !important;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    margin-bottom: 20px;
  }
  .card-body {
    padding: 20px;
  }

  /* Judul section */
  .kp-list-title {
    font-weight: 600;
    color: #111;
    background-color: #f1f2f6;
    border-radius: 8px;
    padding: 8px 14px;
    display: inline-block;
    margin-bottom: 15px;
  }

  /* Form */
  .form-control {
    border-radius: 8px;
    border-color: #ccc;
  }

  /* Tombol */
  .btn {
    border-radius: 8px;
    font-weight: 600;
  }
  .btn-warning {
    background-color: #f2cb3d;
    border: none;
    color: #111;
  }
  .btn-warning:hover {
    background-color: #ddb731;
  }

  /* Teks link PDF */
  .text-primary {
    color: #1a73e8 !important;
  }

  /* Breadcrumb */
  nav[aria-label="breadcrumb"] a {
    color: #6b7280;
    text-decoration: none;
  }
  nav[aria-label="breadcrumb"] a:hover {
    color: #2563eb;
  }

  /* Responsive fix */
  @media (max-width: 992px) {
    .section-container {
      flex-direction: column;
    }
  }
</style>

{{-- =======================
BREADCRUMB
======================= --}}
<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
        <li class="breadcrumb-item active" aria-current="page">Seminar Hasil</li>
      </ol>
    </nav>
    <h4 class="mb-2">Seminar Hasil</h4>
  </div>
</div>

{{-- =======================
NAV TABS
======================= --}}
<div class="mb-3">
  <nav class="kp-tabs">
    <a href="{{ url('/ta-mahasiswa') }}" class="kp-tab">Pendaftaran TA</a>
    <a href="{{ route('seminar.proposal.mahasiswa') }}" class="kp-tab">Seminar Proposal</a>
    <a href="{{ route('seminar.hasil.mahasiswa') }}" class="kp-tab active">Seminar Hasil</a>
    <a href="{{ route('sidang.akhir.mahasiswa') }}" class="kp-tab ">Sidang Akhir</a>
    <a href="{{ route('bimbingan.mahasiswa') }}" class="kp-tab ">Bimbingan</a>
  </nav>
</div>

{{-- =======================
INFORMASI MAHASISWA
======================= --}}
<div class="info-header">
  <div class="row">
    <div class="col-md-6">
      <table class="table table-borderless mb-0">
        <tr><th>Nama/NIM</th><td>Hansel Septiyan Pasaribu / 21S20023</td></tr>
        <tr><th>Judul Penelitian</th><td>Desain Sistem Informasi Pengelolaan Informasi dan Administrasi Program Studi Manajemen Rekayasa</td></tr>
      </table>
    </div>
    <div class="col-md-6">
      <table class="table table-borderless mb-0">
        <tr><th>Pembimbing</th><td>Josua B. W. Jawak S.T., M.Ds.</td></tr>
        <tr><th>Pendaftaran Semhas</th><td><span class="btn btn-warning btn-sm">Diterima</span></td></tr>
      </table>
    </div>
  </div>
</div>

{{-- =======================
KONTEN DUA KOLOM
======================= --}}
<div class="section-container">
  {{-- KIRI --}}
  <div class="section-left">
    <div class="card">
      <div class="card-body">
        <span class="kp-list-title">Unggah Hasil</span>
        <form>
          <div class="mb-3">
            <label class="form-label">Unggah Dokumen TA</label>
            <input type="file" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Unggah Log-Activity</label>
            <input type="file" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Unggah Form Persetujuan</label>
            <input type="file" class="form-control">
          </div>
          <button class="btn btn-warning">Daftar</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <span class="kp-list-title">Informasi Seminar</span>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Unduh Jadwal <a href="#" class="text-primary">.pdf</a>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Rubrik Penilaian <a href="#" class="text-primary">.pdf</a>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Form Review <a href="#" class="text-primary">.pdf</a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  {{-- KANAN --}}
  <div class="section-right">
    <div class="card">
      <div class="card-body">
        <span class="kp-list-title">Unggah Perbaikan</span>
        <form>
          <div class="mb-3">
            <label class="form-label">Unggah Poin Perbaikan</label>
            <input type="file" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Unggah Dokumen Perbaikan</label>
            <input type="file" class="form-control">
          </div>
          <button class="btn btn-warning">Kirim</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
