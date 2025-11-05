<!-- filepath: resources/views/kerja_praktik.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">Kerja Praktik</a></li>
        <li class="breadcrumb-item active" aria-current="page">Informasi Umum</li>
      </ol>
    </nav>
    <h4 class="mb-2">Kerja Praktik</h4>
  </div>
</div>

<div class="row mb-3">
  <div class="col-12">
    <div class="kp-tabs">
      <button class="kp-tab active">Informasi Umum</button>
      <a href="{{ url('/pendaftaran-kp') }}" class="kp-tab">Pendaftaran KP</a>      <button class="kp-tab">Pelaksanaan KP</button>
      <button class="kp-tab">Seminar KP</button>
    </div>
  </div>
</div>

<div class="row mb-3">
  <div class="col-12">
    <img src="assets/images/banner-kp.jpg" class="kp-banner img-fluid" alt="Banner KP">
    <div class="card">
      <div class="card-body p-3">
        <p>
          <strong>Kerja Praktik</strong> - Merupakan matakuliah wajib di MR. Matakuliah ini akan memberikan kesempatan bagi setiap mahasiswa untuk mengasah kemampuan pembelajaran selama ini melalui kegiatan aktual di lapangan.
        </p>
      </div>
    </div>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <h5 class="font-weight-bold text-center mb-4">Alur Kegiatan Kerja Praktik</h5>
        <div class="kp-stepper">
          <div class="kp-step active">
            <span class="kp-circle">1</span>
            <div class="kp-label font-weight-bold">INFORMASI UMUM</div>
          </div>
          <div class="kp-step">
            <span class="kp-circle">2</span>
            <div class="kp-label text-primary font-weight-bold">PENDAFTARAN KP</div>
          </div>
          <div class="kp-step">
            <span class="kp-circle">3</span>
            <div class="kp-label text-secondary">PELAKSANAAN KP</div>
          </div>
          <div class="kp-step">
            <span class="kp-circle">4</span>
            <div class="kp-label text-secondary">SEMINAR KP</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="kp-action-card mb-3">
      <span class="kp-icon" style="color:#f9b115;"><i class="mdi mdi-account-multiple-plus"></i></span>
      <div>
        <div class="kp-title"><a href="#" class="text-primary">Daftar Kelompok KP</a></div>
        <div class="kp-desc">+ Kelompok KP</div>
      </div>
    </div>
    <div class="kp-action-card">
      <span class="kp-icon" style="color:#b16cf9;"><i class="mdi mdi-fingerprint"></i></span>
      <div>
        <div class="kp-title"><a href="#" class="text-primary">Unggah CV Kelompok</a></div>
        <div class="kp-desc">+Unggah CV</div>
      </div>
    </div>
  </div>
</div>
@endsection