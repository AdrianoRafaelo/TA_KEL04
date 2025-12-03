@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  body {
    background: linear-gradient(135deg, #f7f8fb 0%, #e2e8f0 100%);
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  }

  /* Add subtle animation for page load */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .card, .info-header {
    animation: fadeInUp 0.6s ease-out;
  }

  .card:nth-child(1) { animation-delay: 0.1s; }
  .card:nth-child(2) { animation-delay: 0.2s; }
  .card:nth-child(3) { animation-delay: 0.3s; }
  .info-header { animation-delay: 0s; }

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
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
  }

  .kp-tab.disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Header Info */
  .info-header {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    padding: 20px 24px;
    margin-bottom: 25px;
    transition: all 0.3s ease;
  }

  .info-header:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
  }

  .info-header th {
    width: 160px;
    color: #444;
    padding: 8px 16px 8px 0;
  }

  .info-header td {
    color: #1f2937;
    font-weight: 500;
    padding: 8px 0 8px 16px;
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
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9)) !important;
    border: none !important;
    border-radius: 16px !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 4px 16px rgba(0, 0, 0, 0.05) !important;
    margin-bottom: 48px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease !important;
    position: relative;
    overflow: hidden;
  }

  .card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 8px 32px rgba(0, 0, 0, 0.1) !important;
  }

  .card:hover::before {
    opacity: 1;
  }

  .card-body {
    padding: 24px;
  }

  /* Judul section */
  .kp-list-title {
    font-weight: 700;
    color: #1f2937;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px 20px;
    display: inline-block;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    position: relative;
    transition: all 0.3s ease;
  }

  .kp-list-title:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
  }

  .kp-list-title::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    border-radius: 12px 12px 0 0;
  }

  /* Form */
  .form-control {
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(5px);
    transition: all 0.3s ease;
    padding: 12px 16px;
    font-size: 0.9rem;
  }

  .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: rgba(255, 255, 255, 0.95);
    transform: translateY(-1px);
  }

  .form-control:hover {
    border-color: #9ca3af;
  }

  /* Tombol */
  .btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 12px 24px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: none;
  }

  .btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .btn:hover::before {
    width: 300px;
    height: 300px;
  }

  .btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
  }

  .btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    color: #fff;
  }

  /* Teks link PDF */
  .text-primary {
    color: #1a73e8 !important;
  }

  /* Upload Area Styles */
  .upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(5px);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .upload-area:hover {
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.05);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
  }

  .upload-area.dragover {
    border-color: #10b981;
    background: rgba(16, 185, 129, 0.1);
    transform: scale(1.02);
  }

  .upload-icon {
    font-size: 3rem;
    color: #9ca3af;
    margin-bottom: 15px;
    transition: all 0.3s ease;
  }

  .upload-area:hover .upload-icon {
    color: #3b82f6;
    transform: scale(1.1);
  }

  .upload-area.dragover .upload-icon {
    color: #10b981;
  }

  .upload-content p {
    margin: 0 0 10px 0;
    color: #6b7280;
    font-size: 1rem;
  }

  .upload-link {
    color: #3b82f6;
    text-decoration: underline;
    cursor: pointer;
    font-weight: 500;
  }

  .upload-section {
    border: 3px solid #64748b;
    border-radius: 12px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
    margin-bottom: 20px;
    position: relative;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .upload-section:not(:last-child)::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 1px;
    background: linear-gradient(90deg, transparent, #d1d5db, transparent);
  }

  .upload-link:hover {
    color: #1d4ed8;
  }

  /* Modern Gradient Buttons */
  .btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
  }

  .btn-submit:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.4);
  }

  /* Nilai TA lingkaran hijau */
  .bg-success {
    background: linear-gradient(135deg, #1cc88a, #16a085) !important;
    box-shadow: 0 8px 20px rgba(28, 200, 138, 0.3);
  }

  /* List group items */
  .list-group-item {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
  }

  .list-group-item:hover {
    background: rgba(59, 130, 246, 0.05);
    border-color: #3b82f6;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
  }

  .list-group-item:last-child {
    margin-bottom: 0;
  }

  /* Hover untuk tombol */
  .btn-primary:hover {
    background-color: #2c3e50 !important;
    border-color: #2c3e50 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25) !important;
  }

  .btn-outline-primary:hover {
    background-color: #5a67d8 !important;
    color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(90, 103, 216, 0.4) !important;
  }

  .btn-warning:hover, .btn-danger:hover, .btn-success:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25) !important;
  }

  /* Alert enhancements */
  .alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(5px);
  }

  .alert-success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1));
    color: #166534;
  }

  .alert-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
    color: #92400e;
  }

  .alert-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.1));
    color: #1e40af;
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
      <h4 class="mb-2">Seminar Hasil </h4>
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
        <tr>
          <th>Nama/NIM</th>
          <td>
@if($mahasiswaTa)
                @php
                  $ftiData = \App\Models\FtiData::where('username', $mahasiswaTa->mahasiswa)->first();
                  $nama = $ftiData ? $ftiData->nama : $mahasiswaTa->mahasiswa;
                  $nim = $ftiData ? $ftiData->nim : '';
                @endphp
                {{ $nama }} / {{ $nim }}
              @else
                Data tidak ditemukan
@endif
          </td>
        </tr>
        <tr>
          <th>Judul Penelitian</th>
          <td>{{ $mahasiswaTa ? $mahasiswaTa->judul : 'Data tidak ditemukan' }}</td>
        </tr>
      </table>
    </div>
    <div class="col-md-6">
      <table class="table table-borderless mb-0">
        <tr>
          <th>Pembimbing</th>
          <td>{{ $mahasiswaTa ? $mahasiswaTa->pembimbing : 'Data tidak ditemukan' }}</td>
        </tr>
        <tr>
          <th>Pendaftaran Semhas</th>
            <td>
              @if($mahasiswaTa && $mahasiswaTa->seminarhasil && $mahasiswaTa->seminarhasil->status == 'approved')
                <span class="btn btn-success btn-sm">Diterima</span>
              @elseif($mahasiswaTa && $mahasiswaTa->seminarhasil && $mahasiswaTa->seminarhasil->status == 'rejected')
                <span class="btn btn-danger btn-sm">Ditolak</span>
              @elseif($mahasiswaTa && $mahasiswaTa->seminarhasil)
                <span class="btn btn-warning btn-sm">Menunggu</span>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
        </tr>
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
        <form action="{{ route('seminar.hasil.mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Unggah Dokumen TA</label>
            <input type="file" class="form-control" name="file_dokumen_ta" accept=".pdf,.doc,.docx">
            @if($mahasiswaTa && $mahasiswaTa->seminarHasil && $mahasiswaTa->seminarHasil->file_dokumen_ta)
              <div class="d-flex align-items-center mt-1">
                <small class="text-success me-2">
                  <i class="bi bi-check-circle"></i> File tersedia
                </small>
                <button class="btn btn-sm btn-outline-primary view-file-btn me-1" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarHasil->file_dokumen_ta]) }}" data-file-name="Dokumen TA">
                  <i class="bi bi-eye"></i> Lihat
                </button>
                <button class="btn btn-sm btn-outline-warning edit-file-btn me-1" data-field="file_dokumen_ta" data-file-name="Dokumen TA">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger delete-file-btn" data-field="file_dokumen_ta" data-file-name="Dokumen TA">
                  <i class="bi bi-trash"></i> Hapus
                </button>
              </div>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label">Unggah Log-Activity</label>
            <input type="file" class="form-control" name="file_log_activity" accept=".pdf,.doc,.docx">
            @if($mahasiswaTa && $mahasiswaTa->seminarHasil && $mahasiswaTa->seminarHasil->file_log_activity)
              <div class="d-flex align-items-center mt-1">
                <small class="text-success me-2">
                  <i class="bi bi-check-circle"></i> File tersedia
                </small>
                <button class="btn btn-sm btn-outline-primary view-file-btn me-1" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarHasil->file_log_activity]) }}" data-file-name="Log Activity">
                  <i class="bi bi-eye"></i> Lihat
                </button>
                <button class="btn btn-sm btn-outline-warning edit-file-btn me-1" data-field="file_log_activity" data-file-name="Log Activity">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger delete-file-btn" data-field="file_log_activity" data-file-name="Log Activity">
                  <i class="bi bi-trash"></i> Hapus
                </button>
              </div>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label">Unggah Form Persetujuan</label>
            <input type="file" class="form-control" name="file_persetujuan" accept=".pdf,.doc,.docx">
            @if($mahasiswaTa && $mahasiswaTa->seminarHasil && $mahasiswaTa->seminarHasil->file_persetujuan)
              <div class="d-flex align-items-center mt-1">
                <small class="text-success me-2">
                  <i class="bi bi-check-circle"></i> File tersedia
                </small>
                <button class="btn btn-sm btn-outline-primary view-file-btn me-1" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarHasil->file_persetujuan]) }}" data-file-name="Form Persetujuan">
                  <i class="bi bi-eye"></i> Lihat
                </button>
                <button class="btn btn-sm btn-outline-warning edit-file-btn me-1" data-field="file_persetujuan" data-file-name="Form Persetujuan">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger delete-file-btn" data-field="file_persetujuan" data-file-name="Form Persetujuan">
                  <i class="bi bi-trash"></i> Hapus
                </button>
              </div>
            @endif
          </div>
          <button type="submit" class="btn btn-warning">Daftar</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <span class="kp-list-title">Informasi Seminar</span>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>Unduh Jadwal</span>
            @if($mahasiswaTa && $mahasiswaTa->seminarHasil && $mahasiswaTa->seminarHasil->jadwal_seminar_file)
              <button class="btn btn-sm btn-outline-primary view-file-btn" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarHasil->jadwal_seminar_file]) }}" data-file-name="Jadwal Seminar">
                <i class="bi bi-file-pdf"></i> Lihat
              </button>
            @else
              <span class="text-muted">Belum tersedia</span>
            @endif
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>Rubrik Penilaian</span>
            @if($mahasiswaTa && $mahasiswaTa->seminarHasil && $mahasiswaTa->seminarHasil->rubrik_penilaian)
              <button class="btn btn-sm btn-outline-primary view-file-btn" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarHasil->rubrik_penilaian]) }}" data-file-name="Rubrik Penilaian">
                <i class="bi bi-file-pdf"></i> Lihat
              </button>
            @else
              <span class="text-muted">Belum tersedia</span>
            @endif
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>Form Review</span>
            @if($mahasiswaTa && $mahasiswaTa->seminarHasil && $mahasiswaTa->seminarHasil->form_review)
              <button class="btn btn-sm btn-outline-primary view-file-btn" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarHasil->form_review]) }}" data-file-name="Form Review">
                <i class="bi bi-file-pdf"></i> Lihat
              </button>
            @else
              <span class="text-muted">Belum tersedia</span>
            @endif
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
        <form action="{{ route('seminar.hasil.mahasiswa.upload.revisi') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Unggah Poin Perbaikan</label>
            <input type="file" class="form-control" name="form_revisi" required>
            @if($mahasiswaTa && $mahasiswaTa->seminarHasil && $mahasiswaTa->seminarHasil->form_revisi)
              <div class="d-flex align-items-center mt-1">
                <small class="text-success me-2">
                  <i class="bi bi-check-circle"></i> File tersedia
                </small>
                <button class="btn btn-sm btn-outline-primary view-file-btn me-1" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarHasil->form_revisi]) }}" data-file-name="Form Revisi">
                  <i class="bi bi-eye"></i> Lihat
                </button>
                <button class="btn btn-sm btn-outline-warning edit-file-btn me-1" data-field="form_revisi" data-file-name="Form Revisi">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger delete-file-btn" data-field="form_revisi" data-file-name="Form Revisi">
                  <i class="bi bi-trash"></i> Hapus
                </button>
              </div>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label">Unggah Dokumen Perbaikan</label>
            <input type="file" class="form-control" name="revisi_dokumen" required>
            @if($mahasiswaTa && $mahasiswaTa->seminarHasil && $mahasiswaTa->seminarHasil->revisi_dokumen)
              <div class="d-flex align-items-center mt-1">
                <small class="text-success me-2">
                  <i class="bi bi-check-circle"></i> File tersedia
                </small>
                <button class="btn btn-sm btn-outline-primary view-file-btn me-1" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarHasil->revisi_dokumen]) }}" data-file-name="Dokumen Revisi">
                  <i class="bi bi-eye"></i> Lihat
                </button>
                <button class="btn btn-sm btn-outline-warning edit-file-btn me-1" data-field="revisi_dokumen" data-file-name="Dokumen Revisi">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger delete-file-btn" data-field="revisi_dokumen" data-file-name="Dokumen Revisi">
                  <i class="bi bi-trash"></i> Hapus
                </button>
              </div>
            @endif
          </div>
          <button type="submit" class="btn btn-warning">Kirim</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit File -->
<div class="modal fade" id="editFileModal" tabindex="-1" aria-labelledby="editFileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFileModalLabel">Edit File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editFileForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label for="editFileInput" class="form-label" id="editFileLabel">Pilih file baru</label>
            <input type="file" class="form-control" id="editFileInput" name="file" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Delete File -->
<div class="modal fade" id="deleteFileModal" tabindex="-1" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteFileModalLabel">Hapus File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus file <strong id="deleteFileName"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
  $('.view-file-btn').click(function(e) {
    e.preventDefault();
    const fileUrl = $(this).data('file-url');
    const fileName = $(this).data('file-name');

    const modalHtml = '<div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-labelledby="filePreviewLabel" aria-hidden="true">' +
      '<div class="modal-dialog modal-lg" role="document">' +
      '<div class="modal-content">' +
      '<div class="modal-header">' +
      '<h5 class="modal-title" id="filePreviewLabel">' + fileName + '</h5>' +
      '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
      '<span aria-hidden="true">&times;</span>' +
      '</button>' +
      '</div>' +
      '<div class="modal-body" style="min-height: 600px;">' +
      '<iframe src="' + fileUrl + '#toolbar=0" style="width: 100%; height: 600px; border: none;"></iframe>' +
      '</div>' +
      '<div class="modal-footer">' +
      '<a href="' + fileUrl + '" download class="btn btn-primary">' +
      '<i class="bi bi-download"></i> Unduh' +
      '</a>' +
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>';

    const $modal = $(modalHtml);
    $('body').append($modal);
    $modal.modal('show');

    // Handle close button click
    $modal.find('.close, .btn-secondary').click(function() {
      $modal.modal('hide');
    });

    // Handle modal hidden event
    $modal.on('hidden.bs.modal', function() {
      $modal.remove();
    });

    // Handle click outside modal
    $modal.click(function(e) {
      if (e.target === this) {
        $modal.modal('hide');
      }
    });
  });

  // Edit file button
  $('.edit-file-btn').click(function(e) {
    e.preventDefault();
    const field = $(this).data('field');
    const fileName = $(this).data('file-name');

    $('#editFileModalLabel').text('Edit ' + fileName);
    $('#editFileLabel').text('Pilih ' + fileName.toLowerCase() + ' baru');
    $('#editFileForm').attr('action', '/seminar-hasil-mahasiswa/update-file/' + field);
    $('#editFileModal').modal('show');
  });

  // Delete file button
  $('.delete-file-btn').click(function(e) {
    e.preventDefault();
    const field = $(this).data('field');
    const fileName = $(this).data('file-name');

    $('#deleteFileName').text(fileName);
    $('#confirmDeleteBtn').data('field', field);
    $('#deleteFileModal').modal('show');
  });

  // Confirm delete
  $('#confirmDeleteBtn').click(function() {
    const field = $(this).data('field');
    const form = $('<form>', {
      method: 'POST',
      action: '/seminar-hasil-mahasiswa/delete-file/' + field
    });
    form.append('<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') + '">');
    form.append('<input type="hidden" name="_method" value="DELETE">');
    $('body').append(form);
    form.submit();
  });

});

// SweetAlert2 toast notifications
document.addEventListener('DOMContentLoaded', function() {
  @if(session('success'))
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: '{{ session("success") }}',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  @endif

  @if(session('error'))
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: '{{ session("error") }}',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  @endif

  @if(session('message'))
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: '{{ session("message") }}',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  @endif
});
</script>
@endsection
