@extends('layouts.app')

@section('content')
<header class="mb-6">
  <nav class="text-sm font-medium text-gray-500 mb-2" aria-label="breadcrumb">
    <ol class="flex space-x-2">
      <li><a href="#" class="hover:text-blue-600">Page</a><span class="mx-2">/</span></li>
      <li><a href="#" class="hover:text-blue-600">Tugas Akhir</a></li>
    </ol>
  </nav>
  <h1 class="text-lg font-semibold text-gray-800">Bimbingan</h1>
</header>

<div class="kp-tabs">
    <nav class="kp-tabs">
      <a href="{{ url('/ta-mahasiswa') }}" class="kp-tab">Pendaftaran TA</a>
      <a href="{{ route('seminar.proposal.mahasiswa') }}" class="kp-tab ">Seminar Proposal</a>
      <a href="{{ route(name: 'seminar.hasil.mahasiswa') }}" class="kp-tab ">Seminar Hasil</a>
      <a href="{{ route('sidang.akhir.mahasiswa') }}" class="kp-tab ">Sidang Akhir</a>
      <a href="{{ route('bimbingan.mahasiswa') }}" class="kp-tab active">Bimbingan</a>
    </nav>
</div>

<div class="bimbingan-wrapper">
  {{-- Bagian Kiri --}}
  <div class="flex-grow-1" style="min-width: 600px;">
    {{-- Card Rekapitulasi --}}
    <div class="card">
      <div class="card-body">
        <h5 class="kp-title"><i class="bi bi-list-check"></i> Rekapitulasi Bimbingan Mahasiswa</h5>
        <div class="table-responsive">
          <table class="table table-bordered align-middle mb-4">
            <thead class="table-light">
              <tr>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 30%;">Topik Pembahasan</th>
                <th style="width: 30%;">Tugas Selanjutnya</th>
                <th style="width: 15%;">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>20 Apr 2024</td>
                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</td>
                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</td>
                <td>Disetujui</td>
              </tr>
            </tbody>
          </table>
        </div>

        <small class="text-primary d-block mb-3" style="cursor:pointer">+ Tambah log-activity</small>

        <div class="upload-section">
          <label class="me-2 fw-semibold">Unggah Form Bimbingan</label>
          <input type="file" class="form-control" style="max-width: 300px;">
          <button class="btn btn-primary px-4">Unggah</button>
        </div>
      </div>
    </div>

    {{-- Card Skripsi Akhir --}}
    <div class="card">
      <div class="card-body">
        <h6 class="kp-title">Skripsi Akhir</h6>
        <div class="upload-section mb-3">
          <label class="me-2 fw-semibold">Unggah Skripsi *Word</label>
          <input type="file" class="form-control" style="max-width: 300px;">
          <button class="btn btn-primary px-4">Unggah</button>
        </div>
        <div class="upload-section">
          <label class="me-2 fw-semibold">Unggah Skripsi *Pdf</label>
          <input type="file" class="form-control" style="max-width: 300px;">
          <button class="btn btn-primary px-4">Unggah</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Bagian Kanan --}}
  <div style="width: 300px;">
    <div class="card chart-card">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">Bimbingan Tugas Akhir</h6>
        <div class="chart-container">
          <canvas id="bimbinganChart"></canvas>
          <div class="chart-center">25%</div>
        </div>
        <a href="#" class="chart-link">+Lakukan Bimbingan</a>
        <div class="mt-3">
          <strong>Form Bimbingan</strong> <a href="#" class="chart-link">.pdf</a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  body {
    background-color: #f7f8fb;
  }

  /* Tabs */
  .kp-tabs {
    display: flex;
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
  .kp-tab:hover { background: #dcdcdc; }
  .kp-tab.active {
    background: white;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  }

  /* Layout Wrapper */
  .bimbingan-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 35px;
  }

  /* Card Styling */
  .card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    margin-bottom: 25px;
  }
  .card-body {
    padding: 35px; /* ✅ padding diperlebar agar tidak rapat */
  }

  .kp-title {
    font-weight: 600;
    color: #111;
    margin-bottom: 20px;
  }

  .table th {
    font-weight: 600;
    color: #333;
  }

  /* Upload Section */
  .upload-section {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .upload-section input[type=file] {
    flex: 1;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
  }

  /* Chart */
  .chart-card {
    text-align: center;
  }
  .chart-container {
    position: relative;
    width: 160px;
    height: 160px;
    margin: 0 auto;
  }
  .chart-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: 600;
  }
  .chart-link {
    color: #5a4fcf;
    text-decoration: none;
  }
  .chart-link:hover {
    text-decoration: underline;
  }

  @media (max-width: 992px) {
    .bimbingan-wrapper {
      flex-direction: column;
    }
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('bimbinganChart');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      datasets: [{
        data: [25, 75],
        backgroundColor: ['#6a5acd', '#e5e5e5'],
        borderWidth: 0
      }]
    },
    options: {
      cutout: '75%',
      plugins: { legend: { display: false } }
    }
  });
</script>
@endsection
