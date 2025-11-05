@extends('layouts.app')

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">Kerja Praktik</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pelaksanaan KP</li>
      </ol>
    </nav>
    <h4 class="mb-2">Pelaksanaan KP</h4>
  </div>
</div>

<!-- Tabs -->
<div class="row mb-3">
  <div class="col-12">
    <div class="kp-tabs">
      <button class="kp-tab active">Informasi Umum</button>
      <a href="{{ url('/kerja-praktik-dosen') }}" class="kp-tab">Pelaksanaan KP</a>
      <button class="kp-tab" disabled>Seminar KP</button>
    </div>
  </div>
</div>

<!-- Banner Horizontal Scroll -->
<div class="row mb-3">
  <div class="col-12">
    <div class="d-flex overflow-auto gap-3 p-2" style="scrollbar-width: none; -ms-overflow-style: none;">
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-1.jpg') }}" class="img-fluid rounded" alt="Banner 1">
        <div class="position-absolute bottom-0 start-0 end-0 text-center text-white small fw-bold p-2" style="background: rgba(0,0,0,0.6); border-radius: 0 0 8px 8px;">
          Peran Manajemen Rekayasa Dalam Peningkatan Energi Terbarukan
        </div>
      </div>
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-2.jpg') }}" class="img-fluid rounded" alt="Banner 2">
        <div class="position-absolute bottom-0 start-0 end-0 text-center text-white small fw-bold p-2" style="background: rgba(0,0,0,0.6); border-radius: 0 0 8px 8px;">
          Peraturan Pemerintah Melalui Gerakan Hijau
        </div>
      </div>
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-3.jpg') }}" class="img-fluid rounded" alt="Banner 3">
        <div class="position-absolute bottom-0 start-0 end-0 text-center text-white small fw-bold p-2" style="background: rgba(0,0,0,0.6); border-radius: 0 0 8px 8px;">
          Peran Manajemen Rekayasa Dalam Peningkatan Energi Terbarukan
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tabel Mahasiswa Bimbingan -->
<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="kp-list-title mb-3 text-primary fw-bold">Mahasiswa Bimbingan</h6>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th scope="col" width="5%">No.</th>
                <th scope="col">Mahasiswa</th>
                <th scope="col">Perusahaan KP</th>
                <th scope="col" width="15%">Selengkapnya</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td>
                  <div>Hansel Septiyan Pasaribu</div>
                  <div class="text-muted small">Junaedy Siahaan</div>
                </td>
                <td>PT. MRT Jakarta Perseroda</td>
                <td>
                  <button class="btn btn-success btn-sm rounded-pill px-4 py-1 shadow-sm">Lihat</button>
                </td>
              </tr>
              <tr>
                <td>2.</td>
                <td>
                  <div>Yuni Magdalena Sinaga</div>
                  <div class="text-muted small">Esti Sormin</div>
                  <div class="text-muted small">Natalia Hutagaol</div>
                  <div class="text-muted small">Agnes Fransiska Lisnawati</div>
                </td>
                <td>PT. DanLiris</td>
                <td>
                  <button class="btn btn-success btn-sm rounded-pill px-4 py-1 shadow-sm">Lihat</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection