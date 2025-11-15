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
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
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
          <li class="breadcrumb-item active" aria-current="page">Seminar Proposal</li>
        </ol>
      </nav>
      <h4 class="mb-2">Seminar Proposal</h4>
    </div>
  </div>

  <div class="mb-3">
    <nav class="kp-tabs">
      <a href="{{ url('/ta-mahasiswa') }}" class="kp-tab">Pendaftaran TA</a>
      <a href="{{ route('seminar.proposal.mahasiswa') }}" class="kp-tab active">Seminar Proposal</a>
      <a href="{{ route('seminar.hasil.mahasiswa') }}" class="kp-tab">Seminar Hasil</a>
      <a href="{{ route('sidang.akhir.mahasiswa') }}" class="kp-tab">Sidang Akhir</a>
      <a href="{{ route('bimbingan.mahasiswa') }}" class="kp-tab">Bimbingan</a>
    </nav>
  </div>

  {{-- Informasi Mahasiswa --}}
  <div class="info-header">
    <div class="row">
      <div class="col-md-6">
        <table class="table table-borderless mb-0">
          <tr>
            <th>Nama/NIM</th>
            <td>{{ $mahasiswaTa ? $mahasiswaTa->mahasiswa : 'Data tidak ditemukan' }}</td>
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
            <th>Pendaftaran Sempro</th>
            <td>
              @if($mahasiswaTa && $mahasiswaTa->seminarProposal && $mahasiswaTa->seminarProposal->status == 'approved')
                <span class="btn btn-success btn-sm">Diterima</span>
              @elseif($mahasiswaTa && $mahasiswaTa->seminarProposal && $mahasiswaTa->seminarProposal->status == 'rejected')
                <span class="btn btn-danger btn-sm">Ditolak</span>
              @else
                <span class="btn btn-warning btn-sm">Menunggu</span>
              @endif
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  {{-- Konten dua kolom --}}
  <div class="section-container">
    {{-- Kiri --}}
    <div class="section-left">
      <div class="card">
        <div class="card-body">
          <span class="kp-list-title">Unggah Proposal</span>
          <form action="{{ route('seminar.proposal.mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label class="form-label">Unggah Proposal</label>
              <input type="file" class="form-control" name="file_proposal" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Unggah Form Persetujuan</label>
              <input type="file" class="form-control" name="file_persetujuan" required>
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
              @if($seminarProposal && $seminarProposal->jadwal_seminar_file)
                <button class="btn btn-sm btn-outline-primary view-file-btn" data-file-url="{{ route('storage.file', ['path' => $seminarProposal->jadwal_seminar_file]) }}" data-file-name="Jadwal Seminar">
                  <i class="bi bi-file-pdf"></i> Lihat
                </button>
              @else
                <span class="text-muted">Belum tersedia</span>
              @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>Rubrik Penilaian</span>
              @if($seminarProposal && $seminarProposal->rubrik_penilaian)
                <button class="btn btn-sm btn-outline-primary view-file-btn" data-file-url="{{ route('storage.file', ['path' => $seminarProposal->rubrik_penilaian]) }}" data-file-name="Rubrik Penilaian">
                  <i class="bi bi-file-pdf"></i> Lihat
                </button>
              @else
                <span class="text-muted">Belum tersedia</span>
              @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>Form Review</span>
              @if($seminarProposal && $seminarProposal->form_review)
                <button class="btn btn-sm btn-outline-primary view-file-btn" data-file-url="{{ route('storage.file', ['path' => $seminarProposal->form_review]) }}" data-file-name="Form Review">
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

    {{-- Kanan --}}
    <div class="section-right">
      <div class="card">
        <div class="card-body">
          <span class="kp-list-title">Unggah Perbaikan</span>
          <form action="{{ route('seminar.proposal.mahasiswa.upload.revisi') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label class="form-label">Unggah Poin Perbaikan</label>
              <input type="file" class="form-control" name="form_revisi" required>
              @if($mahasiswaTa && $mahasiswaTa->seminarProposal && $mahasiswaTa->seminarProposal->form_revisi)
                <small class="text-success d-block mt-1">
                  <i class="bi bi-check-circle"></i>
                  <button class="btn btn-sm btn-outline-success view-file-btn ms-1" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarProposal->form_revisi]) }}" data-file-name="Form Revisi">
                    <i class="bi bi-eye"></i> Lihat
                  </button>
                </small>
              @endif
            </div>
            <div class="mb-3">
              <label class="form-label">Unggah Dokumen Perbaikan</label>
              <input type="file" class="form-control" name="revisi_dokumen" required>
              @if($mahasiswaTa && $mahasiswaTa->seminarProposal && $mahasiswaTa->seminarProposal->revisi_dokumen)
                <small class="text-success d-block mt-1">
                  <i class="bi bi-check-circle"></i>
                  <button class="btn btn-sm btn-outline-success view-file-btn ms-1" data-file-url="{{ route('storage.file', ['path' => $mahasiswaTa->seminarProposal->revisi_dokumen]) }}" data-file-name="Dokumen Revisi">
                    <i class="bi bi-eye"></i> Lihat
                  </button>
                </small>
              @endif
            </div>
            <button type="submit" class="btn btn-warning">Kirim</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
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
});
</script>
@endsection
