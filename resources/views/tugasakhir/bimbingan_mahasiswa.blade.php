@extends('layouts.app')

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Page</a></li>
          <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
          <li class="breadcrumb-item active" aria-current="page">Bimbingan</li>
        </ol>
      </nav>
      <h4 class="mb-2">Bimbingan</h4>
    </div>
  </div>

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
              @forelse($bimbingans as $bimbingan)
              <tr>
                <td>{{ $bimbingan->tanggal->format('d M Y') }}</td>
                <td>{{ $bimbingan->topik_pembahasan }}</td>
                <td>{{ $bimbingan->tugas_selanjutnya ?: '-' }}</td>
                <td>
                  @if($bimbingan->status == 'approved')
                    <span class="badge bg-success">Disetujui</span>
                  @elseif($bimbingan->status == 'rejected')
                    <span class="badge bg-danger">Ditolak</span>
                  @else
                    <span class="badge bg-warning">Menunggu</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Belum ada data bimbingan</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <button type="button" class="btn btn-link text-primary p-0 mb-3" id="tambahLogActivity" onclick="openTambahLogModal()" style="font-size: 14px; text-decoration: none; border: none; background: none;">
          + Tambah log-activity
        </button>

         <form method="POST" action="{{ route('bimbingan.mahasiswa.upload.skripsi') }}" enctype="multipart/form-data">
           @csrf
           <div class="upload-section mb-3">
             <label class="me-2 fw-semibold">Unggah Form Bimbingan</label>
             <input type="file" name="form_bimbingan" class="form-control" style="max-width: 300px;" accept=".pdf,.doc,.docx">
             <button type="submit" class="btn btn-primary px-4">Unggah</button>
           </div>
         </form>
      </div>
    </div>

    {{-- Card Skripsi Akhir --}}
    <div class="card">
      <div class="card-body">
        <h6 class="kp-title">Skripsi Akhir</h6>

        {{-- Status Upload --}}
        @if($skripsi)
          <div class="mb-3">
            <div class="alert alert-info">
              <strong>Status Upload:</strong>
              <ul class="mb-0 mt-2">
                <li>Skripsi Word: @if($skripsi->file_skripsi_word)
                  <a href="{{ asset('storage/' . $skripsi->file_skripsi_word) }}" target="_blank" class="text-primary">✓ Sudah diunggah</a>
                  @else <span class="text-muted">Belum diunggah</span> @endif
                </li>
                <li>Skripsi PDF: @if($skripsi->file_skripsi_pdf)
                  <a href="{{ asset('storage/' . $skripsi->file_skripsi_pdf) }}" target="_blank" class="text-primary">✓ Sudah diunggah</a>
                  @else <span class="text-muted">Belum diunggah</span> @endif
                </li>
                <li>Form Bimbingan: @if($skripsi->file_form_bimbingan)
                  <a href="{{ asset('storage/' . $skripsi->file_form_bimbingan) }}" target="_blank" class="text-primary">✓ Sudah diunggah</a>
                  @else <span class="text-muted">Belum diunggah</span> @endif
                </li>
              </ul>
            </div>
          </div>
        @endif

        <form method="POST" action="{{ route('bimbingan.mahasiswa.upload.skripsi') }}" enctype="multipart/form-data">
          @csrf
          <div class="upload-section mb-3">
            <label class="me-2 fw-semibold">Unggah Skripsi *Word</label>
            <input type="file" name="file_skripsi_word" class="form-control" style="max-width: 300px;" accept=".doc,.docx">
          </div>
          <div class="upload-section mb-3">
            <label class="me-2 fw-semibold">Unggah Skripsi *Pdf</label>
            <input type="file" name="file_skripsi_pdf" class="form-control" style="max-width: 300px;" accept=".pdf">
          </div>
          <button type="submit" class="btn btn-primary px-4">Unggah Skripsi</button>
        </form>
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
          <div class="chart-center">
            <div class="progress-percentage">{{ $bimbingans->count() * 10 }}%</div>
            <div class="progress-text">{{ $bimbingans->count() }}/10</div>
          </div>
        </div>

        <div class="chart-actions">
          <button type="button" class="btn btn-primary btn-sm chart-btn" onclick="openTambahLogModal()">
            <i class="bi bi-plus-circle"></i> Lakukan Bimbingan
          </button>
        </div>

        <div class="mt-3 chart-info">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="chart-label">Total Bimbingan</span>
            <span class="chart-value">{{ $bimbingans->count() }}</span>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="chart-label">Disetujui</span>
            <span class="chart-value text-success">{{ $bimbingans->where('status', 'approved')->count() }}</span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span class="chart-label">Menunggu</span>
            <span class="chart-value text-warning">{{ $bimbingans->where('status', 'pending')->count() }}</span>
          </div>
        </div>

        <hr class="my-3">

        <div class="chart-downloads">
           <h6 class="mb-2">Dokumen Bimbingan</h6>
           <div class="download-item">
             <i class="bi bi-file-earmark-pdf"></i>
             <div class="download-info">
               <span class="download-name">Form Bimbingan</span>
               <small class="download-size">PDF • @if($skripsi && $skripsi->file_form_bimbingan) Sudah diupload @else Belum diupload @endif</small>
             </div>
             @if($skripsi && $skripsi->file_form_bimbingan)
               <a href="{{ asset('storage/' . $skripsi->file_form_bimbingan) }}" target="_blank" class="btn btn-sm btn-outline-primary download-btn">
                 <i class="bi bi-download"></i>
               </a>
             @else
               <button class="btn btn-sm btn-outline-secondary download-btn" disabled>
                 <i class="bi bi-dash"></i>
               </button>
             @endif
           </div>
           <div class="download-item">
             <i class="bi bi-file-earmark-word"></i>
             <div class="download-info">
               <span class="download-name">Skripsi Word</span>
               <small class="download-size">DOCX • @if($skripsi && $skripsi->file_skripsi_word) Sudah diupload @else Belum diupload @endif</small>
             </div>
             @if($skripsi && $skripsi->file_skripsi_word)
               <a href="{{ asset('storage/' . $skripsi->file_skripsi_word) }}" target="_blank" class="btn btn-sm btn-outline-primary download-btn">
                 <i class="bi bi-download"></i>
               </a>
             @else
               <button class="btn btn-sm btn-outline-secondary download-btn" disabled>
                 <i class="bi bi-dash"></i>
               </button>
             @endif
           </div>
           <div class="download-item">
             <i class="bi bi-file-earmark-pdf"></i>
             <div class="download-info">
               <span class="download-name">Skripsi PDF</span>
               <small class="download-size">PDF • @if($skripsi && $skripsi->file_skripsi_pdf) Sudah diupload @else Belum diupload @endif</small>
             </div>
             @if($skripsi && $skripsi->file_skripsi_pdf)
               <a href="{{ asset('storage/' . $skripsi->file_skripsi_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary download-btn">
                 <i class="bi bi-download"></i>
               </a>
             @else
               <button class="btn btn-sm btn-outline-secondary download-btn" disabled>
                 <i class="bi bi-dash"></i>
               </button>
             @endif
           </div>
         </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Log Activity -->
<div class="modal fade modern-modal" id="tambahLogModal" tabindex="-1" role="dialog" aria-labelledby="tambahLogModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content modern-modal-content">
      <!-- Header with gradient -->
      <div class="modern-modal-header">
        <div class="modal-icon">
          <i class="bi bi-plus-circle-fill"></i>
        </div>
        <div class="modal-title-section">
          <h5 class="modal-title" id="tambahLogModalLabel">Tambah Log Activity</h5>
          <p class="modal-subtitle">Catat aktivitas bimbingan tugas akhir</p>
        </div>
        <button type="button" class="modern-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Body with modern form -->
      <div class="modern-modal-body">
        <form id="formTambahLog">
          <div class="form-group-modern">
            <label for="tanggal" class="form-label-modern">
              <i class="bi bi-calendar-event"></i>
              Tanggal Bimbingan
            </label>
            <div class="input-wrapper">
              <input type="date" class="form-control-modern" id="tanggal" name="tanggal" required>
              <div class="input-focus-border"></div>
            </div>
          </div>

          <div class="form-group-modern">
            <label for="topik_pembahasan" class="form-label-modern">
              <i class="bi bi-chat-quote"></i>
              Topik Pembahasan
            </label>
            <div class="input-wrapper">
              <textarea class="form-control-modern textarea-modern" id="topik_pembahasan" name="topik_pembahasan"
                        rows="4" required placeholder="Jelaskan topik yang dibahas dalam bimbingan..."></textarea>
              <div class="input-focus-border"></div>
            </div>
          </div>

          <div class="form-group-modern">
            <label for="tugas_selanjutnya" class="form-label-modern">
              <i class="bi bi-list-check"></i>
              Tugas Selanjutnya
            </label>
            <div class="input-wrapper">
              <textarea class="form-control-modern textarea-modern" id="tugas_selanjutnya" name="tugas_selanjutnya"
                        rows="3" placeholder="Tugas yang harus dikerjakan selanjutnya..."></textarea>
              <div class="input-focus-border"></div>
            </div>
          </div>
        </form>
      </div>

      <!-- Footer with modern buttons -->
      <div class="modern-modal-footer">
        <button type="button" class="btn-modern btn-modern-secondary" data-dismiss="modal">
          <i class="bi bi-x-circle"></i>
          <span>Batal</span>
        </button>
        <button type="button" class="btn-modern btn-modern-primary" id="simpanLog">
          <i class="bi bi-check-circle"></i>
          <span>Simpan Log</span>
        </button>
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

  /* Tambah Log Activity Button */
  #tambahLogActivity {
    cursor: pointer !important;
    user-select: none;
    transition: all 0.2s ease;
    z-index: 10;
    position: relative;
    font-size: 14px !important;
    text-decoration: none !important;
    border: none !important;
    background: none !important;
    padding: 0 !important;
    color: #0d6efd !important;
  }
  #tambahLogActivity:hover {
    color: #0056b3 !important;
    text-decoration: underline !important;
  }
  #tambahLogActivity:focus {
    outline: none !important;
    box-shadow: none !important;
  }

  /* Enhanced Chart Styling */
  .chart-card {
    text-align: center;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .chart-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
  }

  .chart-container {
    position: relative;
    width: 180px;
    height: 180px;
    margin: 0 auto 20px;
  }

  .chart-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #1f2937;
  }

  .progress-percentage {
    font-size: 24px;
    font-weight: 700;
    color: #6366f1;
    line-height: 1;
  }

  .progress-text {
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
  }

  .chart-actions {
    margin-bottom: 20px;
  }

  .chart-btn {
    width: 100%;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .chart-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
  }

  .chart-info {
    background: #f8fafc;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 16px;
  }

  .chart-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }

  .chart-value {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
  }

  .chart-downloads h6 {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 12px;
  }

  .download-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px solid #e5e7eb;
  }

  .download-item:last-child {
    border-bottom: none;
  }

  .download-item i {
    font-size: 20px;
    color: #6366f1;
    flex-shrink: 0;
  }

  .download-info {
    flex: 1;
    min-width: 0;
  }

  .download-name {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #1f2937;
  }

  .download-size {
    display: block;
    font-size: 11px;
    color: #6b7280;
  }

  .download-btn {
    padding: 6px 8px;
    font-size: 12px;
    border-radius: 6px;
    transition: all 0.2s ease;
  }

  .download-btn:hover {
    transform: scale(1.05);
  }

  @media (max-width: 992px) {
    .bimbingan-wrapper {
      flex-direction: column;
    }
  }

  /* Modern Modal Styling */
  .modern-modal .modal-backdrop {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
  }

  .modern-modal .modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
  }

  @keyframes modalSlideIn {
    from {
      opacity: 0;
      transform: translateY(-50px) scale(0.9);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  .modern-modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px 32px 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
  }

  .modal-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    backdrop-filter: blur(10px);
  }

  .modal-title-section {
    flex: 1;
  }

  .modal-title {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: white;
  }

  .modal-subtitle {
    font-size: 14px;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
  }

  .modern-close-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
  }

  .modern-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
  }

  .modern-modal-body {
    padding: 32px;
    background: #ffffff;
  }

  .form-group-modern {
    margin-bottom: 28px;
  }

  .form-label-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 14px;
  }

  .form-label-modern i {
    color: #6366f1;
    font-size: 16px;
  }

  .input-wrapper {
    position: relative;
  }

  .form-control-modern {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #374151;
  }

  .form-control-modern:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
  }

  .textarea-modern {
    resize: vertical;
    min-height: 100px;
  }

  .input-focus-border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6);
    transition: width 0.3s ease;
  }

  .form-control-modern:focus + .input-focus-border {
    width: 100%;
  }

  /* Form animations */
  .form-group-modern {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
  }

  .form-group-modern:nth-child(1) { animation-delay: 0.1s; }
  .form-group-modern:nth-child(2) { animation-delay: 0.2s; }
  .form-group-modern:nth-child(3) { animation-delay: 0.3s; }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Placeholder styling */
  .form-control-modern::placeholder {
    color: #9ca3af;
    font-style: italic;
  }

  /* Button hover effects */
  .btn-modern-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
  }

  .btn-modern-secondary:active {
    transform: translateY(0);
  }

  .modern-modal-footer {
    padding: 24px 32px 32px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
  }

  .btn-modern {
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 120px;
    justify-content: center;
  }

  .btn-modern-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
  }

  .btn-modern-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
  }

  .btn-modern-secondary {
    background: #ffffff;
    color: #64748b;
    border: 2px solid #e2e8f0;
  }

  .btn-modern-secondary:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    transform: translateY(-1px);
  }

  .btn-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .modern-modal-header {
      padding: 24px 20px 20px;
      flex-direction: column;
      text-align: center;
      gap: 16px;
    }

    .modal-icon {
      width: 50px;
      height: 50px;
      font-size: 20px;
    }

    .modal-title {
      font-size: 20px;
    }

    .modern-modal-body {
      padding: 24px 20px;
    }

    .modern-modal-footer {
      padding: 20px 20px 24px;
      flex-direction: column;
    }

    .btn-modern {
      width: 100%;
    }
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Global function for inline onclick
function openTambahLogModal() {
  console.log('openTambahLogModal called');

  // Set tanggal hari ini sebagai default
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('tanggal').value = today;

  // Reset form
  document.getElementById('formTambahLog').reset();

  // Show modal using jQuery (consistent with seminar proposal)
  $('#tambahLogModal').modal('show');
  console.log('Modal opened successfully');
}

// Handle Simpan Log - attached when modal is shown
function handleSimpanLog() {
  console.log('handleSimpanLog function called');

  const tanggal = $('#tanggal').val();
  const topik_pembahasan = $('#topik_pembahasan').val();
  const tugas_selanjutnya = $('#tugas_selanjutnya').val();

  console.log('Form values:', {
    tanggal: tanggal,
    topik_pembahasan: topik_pembahasan,
    tugas_selanjutnya: tugas_selanjutnya
  });

  // Validate required fields
  if (!tanggal || !topik_pembahasan) {
    console.log('Validation failed: missing required fields');
    alert('Tanggal dan Topik Pembahasan harus diisi!');
    return;
  }

  console.log('Validation passed, preparing AJAX call');

  // Disable button and show loading state
  const simpanBtn = $('#simpanLog');
  simpanBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i><span>Menyimpan...</span>');

  const ajaxData = {
    tanggal: tanggal,
    topik_pembahasan: topik_pembahasan,
    tugas_selanjutnya: tugas_selanjutnya,
    _token: '{{ csrf_token() }}'
  };

  console.log('AJAX data to send:', ajaxData);
  console.log('AJAX URL:', '{{ route("bimbingan.mahasiswa.store") }}');

  // Use jQuery AJAX for consistency
  $.ajax({
    url: '{{ route("bimbingan.mahasiswa.store") }}',
    method: 'POST',
    data: ajaxData,
    dataType: 'json',
    success: function(data, textStatus, xhr) {
      console.log('AJAX success - status:', textStatus);
      console.log('AJAX success - response:', data);
      console.log('AJAX success - xhr:', xhr);

      if (data.success) {
        alert(data.message);
        $('#tambahLogModal').modal('hide');
        location.reload();
      } else {
        alert('Gagal menyimpan: ' + (data.message || 'Unknown error'));
      }
    },
    error: function(xhr, status, error) {
      console.error('AJAX error - status:', status);
      console.error('AJAX error - error:', error);
      console.error('AJAX error - xhr:', xhr);
      console.error('AJAX error - response text:', xhr.responseText);

      alert('Terjadi kesalahan saat menyimpan data: ' + error);
    },
    complete: function(xhr, status) {
      console.log('AJAX complete - status:', status);
      simpanBtn.prop('disabled', false).html('<i class="bi bi-check-circle"></i><span>Simpan Log</span>');
    }
  });
}

// Wait for jQuery to be loaded, then initialize
function initializeBimbinganPage() {
  console.log('Initializing bimbingan page with jQuery loaded');

  $(document).ready(function() {
    console.log('Document ready - initializing bimbingan page');

    // Dynamic Chart initialization based on bimbingan data
    const ctx = document.getElementById('bimbinganChart');
    if (ctx) {
      const totalBimbingan = {{ $bimbingans->count() }};
      const approvedBimbingan = {{ $bimbingans->where('status', 'approved')->count() }};
      const pendingBimbingan = {{ $bimbingans->where('status', 'pending')->count() }};
      const rejectedBimbingan = {{ $bimbingans->where('status', 'rejected')->count() }};

      // Calculate progress (assuming target is 10 bimbingan)
      const targetBimbingan = 10;
      const progressPercentage = Math.min((totalBimbingan / targetBimbingan) * 100, 100);
      const remainingPercentage = 100 - progressPercentage;

      // Color scheme based on progress
      let progressColor = '#e5e7eb'; // Default gray
      if (progressPercentage >= 80) {
        progressColor = '#10b981'; // Green for good progress
      } else if (progressPercentage >= 50) {
        progressColor = '#f59e0b'; // Yellow for medium progress
      } else if (progressPercentage >= 25) {
        progressColor = '#f97316'; // Orange for low progress
      }

      new Chart(ctx, {
        type: 'doughnut',
        data: {
          datasets: [{
            data: [progressPercentage, remainingPercentage],
            backgroundColor: [progressColor, '#f3f4f6'],
            borderWidth: 0,
            hoverBorderWidth: 2,
            hoverBorderColor: '#ffffff'
          }]
        },
        options: {
          cutout: '75%',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              enabled: true,
              callbacks: {
                label: function(context) {
                  const label = context.dataIndex === 0 ? 'Selesai' : 'Sisa';
                  const value = context.parsed;
                  return `${label}: ${value.toFixed(1)}%`;
                }
              }
            }
          },
          animation: {
            animateScale: true,
            animateRotate: true,
            duration: 1000,
            easing: 'easeOutQuart'
          }
        }
      });

      console.log('Chart initialized with data:', {
        total: totalBimbingan,
        approved: approvedBimbingan,
        pending: pendingBimbingan,
        rejected: rejectedBimbingan,
        progress: progressPercentage
      });
    }

    // Attach save button handler when modal is shown
    $('#tambahLogModal').on('shown.bs.modal', function() {
      console.log('Modal shown, attaching save handler');

      // Remove any existing handlers first
      $('#simpanLog').off('click.saveHandler');

      // Attach new handler
      $('#simpanLog').on('click.saveHandler', function(e) {
        e.preventDefault();
        console.log('Save button clicked via jQuery event handler');
        handleSimpanLog();
      });

      console.log('Save handler attached successfully');
    });

    // Reset form when modal is hidden
    $('#tambahLogModal').on('hidden.bs.modal', function() {
      console.log('Modal hidden, resetting form');
      $('#formTambahLog')[0].reset();
    });
  });
}

// Check if jQuery is loaded, if not wait for it
if (typeof $ === 'undefined') {
  console.log('jQuery not loaded yet, waiting...');
  let checkJQuery = setInterval(function() {
    if (typeof $ !== 'undefined') {
      clearInterval(checkJQuery);
      console.log('jQuery loaded, initializing bimbingan page');
      initializeBimbinganPage();
    }
  }, 50); // Check every 50ms
} else {
  console.log('jQuery already loaded, initializing bimbingan page');
  initializeBimbinganPage();
}
</script>
@endsection
