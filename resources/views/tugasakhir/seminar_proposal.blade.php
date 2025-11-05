@extends('layouts.app')

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
        <li class="breadcrumb-item active" aria-current="page">Seminar Proposal</li>
      </ol>
    </nav>
    <h4 class="mb-2">Seminar Proposal</h4>
  </div>
</div>

<div class="row mb-3">
  <div class="col-12">
    <div class="kp-tabs">
      <a href="{{ route('ta-dosen') }}" class="kp-tab">Pendaftaran TA</a>
      <button class="kp-tab active">Seminar Proposal</button>
      <button class="kp-tab" disabled>Seminar Hasil</button>
      <button class="kp-tab" disabled>Sidang Akhir</button>
      <button class="kp-tab" disabled>Bimbingan</button>
    </div>
  </div>
</div>

<div class="row mb-3">
  <div class="col-12">
    <img src="{{ asset('assets/images/banner-ta.jpg') }}" class="kp-banner img-fluid" alt="Banner TA">
  </div>
</div>

<!-- Jadwal Seminar Button -->
<div class="row mb-3">
  <div class="col-12 text-end">
    <div class="dropdown d-inline-block">
      <button class="btn btn-dark dropdown-toggle" type="button" id="jadwalSeminarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-calendar-alt me-2"></i> Jadwal Seminar
      </button>
      <ul class="dropdown-menu" aria-labelledby="jadwalSeminarDropdown">
        <li><a class="dropdown-item" href="#" onclick="alert('Fitur unduh jadwal sedang dikembangkan.')">Unduh Jadwal .pdf</a></li>
      </ul>
    </div>
  </div>
</div>

<!-- Dosen Pembimbing -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h6 class="kp-list-title mb-3 text-primary fw-bold">Dosen Pembimbing</h6>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col" width="5%">No.</th>
                <th scope="col">Mahasiswa</th>
                <th scope="col">Judul</th>
                <th scope="col">Dosen Pembimbing</th>
                <th scope="col">Dosen Penguji I</th>
                <th scope="col">Dosen Penguji II</th>
                <th scope="col" width="8%">Doc.</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td>Hansel Septiyan Pasaribu</td>
                <td>Perancangan Aplikasi Website Prodi MR</td>
                <td>Josua B. W. Jawak S.T., M.Ds.</td>
                <td>Dr. Fitriani T. R. Silalahi S.Si., M.Si.</td>
                <td>Hadi S. Saragi S.T., M.Sc</td>
                <td>
                  <a href="#" class="text-primary"><i class="fas fa-file-pdf"></i> Dokumen</a>
                </td>
              </tr>
              <tr>
                <td>2.</td>
                <td>Junaedy Siahaan</td>
                <td>Perancangan Website MR</td>
                <td>Josua B. W. Jawak S.T., M.Ds.</td>
                <td>Iswanti Silalahi S.Si., M.Sc</td>
                <td>Samuel Tampubolon S.T., M.</td>
                <td>
                  <a href="#" class="text-primary"><i class="fas fa-file-pdf"></i> Dokumen</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Dosen Penguji -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h6 class="kp-list-title mb-3 text-primary fw-bold">Dosen Penguji</h6>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col" width="5%">No.</th>
                <th scope="col">Mahasiswa</th>
                <th scope="col">Judul</th>
                <th scope="col">Dosen Pembimbing</th>
                <th scope="col">Dosen Penguji I</th>
                <th scope="col">Dosen Penguji II</th>
                <th scope="col" width="8%">Doc.</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td>Noramti M. Manurung</td>
                <td>Perancangan MOOCS untuk meningkatkan skills</td>
                <td>Dr. Fitriani T. R. Silalahi S.Si., M.Si.</td>
                <td>Mariana Simanjuntak, S.S., M.Sc.</td>
                <td>Josua B. W. Jawak S.T., M.Ds.</td>
                <td>
                  <a href="#" class="text-primary"><i class="fas fa-file-pdf"></i> Dokumen</a>
                </td>
              </tr>
              <tr>
                <td>2.</td>
                <td>Ria A. Sihotang</td>
                <td>Analisis Kerjasama DITerun dan Batik dalam penjualan baju</td>
                <td>Samuel Tampubolon S.T., M.</td>
                <td>Josua B. W. Jawak S.T., M.Ds.</td>
                <td>Hadi S. Saragi S.T., M.Sc</td>
                <td>
                  <a href="#" class="text-primary"><i class="fas fa-file-pdf"></i> Dokumen</a>
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