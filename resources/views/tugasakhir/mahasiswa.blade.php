@extends('layouts.app')

@section('content')
@php
    $pengaturanTa = \App\Models\PengaturanTa::first();
    $pendaftaranDitutup = false;
    $alasanPenutupan = '';

    if ($pengaturanTa) {
        if ($pengaturanTa->pendaftaran_ditutup) {
            $pendaftaranDitutup = true;
            $alasanPenutupan = $pengaturanTa->pesan_penutupan ?: 'Pendaftaran ditutup oleh koordinator';
        } elseif ($pengaturanTa->batas_waktu_pendaftaran && now()->isAfter($pengaturanTa->batas_waktu_pendaftaran)) {
            $pendaftaranDitutup = true;
            $alasanPenutupan = 'Batas waktu pendaftaran telah berakhir pada ' . \Carbon\Carbon::parse($pengaturanTa->batas_waktu_pendaftaran)->format('d M Y H:i');
        }
    }

    // Check if student has active registration (not rejected)
    $sudahMendaftar = false;
    $username = Session::get('username');
    if ($username) {
        $activeRegistrations = collect($status_pendaftaran_sendiri)->merge($status_pendaftaran_tawaran)->filter(function($reg) {
            return $reg->status && $reg->status->name !== 'ditolak';
        });
        $sudahMendaftar = $activeRegistrations->isNotEmpty();
    }
@endphp

<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pendaftaran TA</li>
      </ol>
    </nav>
    <h4 class="mb-2">Pendaftaran TA</h4>

    @if($pendaftaranDitutup)
      <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Pendaftaran TA Ditutup</strong><br>
        {{ $alasanPenutupan }}
      </div>
    @endif

    @if($sudahMendaftar)
      <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Anda Sudah Terdaftar</strong><br>
        Anda telah mendaftar TA dan sedang menunggu persetujuan koordinator. Jika ditolak, Anda dapat mendaftar kembali.
      </div>
    @endif
  </div>
</div>

<div class="row mb-3">
  <div class="col-12">
    <div class="kp-tabs">
      <a href="{{ url('/pendaftaran-ta') }}" class="kp-tab active">Pendaftaran TA</a>
      <a href="{{ route('seminar.proposal.mahasiswa') }}" class="kp-tab ">Seminar Proposal</a>
      <a href="{{ route('seminar.hasil.mahasiswa') }}" class="kp-tab ">Seminar Hasil</a>
      <a href="{{ route('sidang.akhir.mahasiswa') }}" class="kp-tab ">Sidang Akhir</a>
      <a href="{{ route('bimbingan.mahasiswa') }}" class="kp-tab ">Bimbingan</a>
    </div>
  </div>
</div>


    <!-- Banner Section -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="gambar" style="background-image:url('/img/panel%20surya.jpeg')">
          <div class="banner-text">Peran Manajemen Rekayasa Dalam Peningkatan Energi Terbarukan</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="gambar" style="background-image:url('/img/panel%20surya.jpeg')">
          <div class="banner-text">Peraturan Pemerintah Melalui Gerakan Hijau</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="gambar" style="background-image:url('/img/wind turbine.jpg')">
          <div class="banner-text">Peningkatan Kualitas Pendidikan Teknik Mesin</div>
        </div>
      </div>
    </div>

<div class="row mb-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>No.</th>
              <th>Dosen</th>
              <th>Judul</th>
              <th>Deskripsi</th>
              <th>Syarat</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($judul_dosen as $no => $jd)
            <tr>
              <td>{{ $no+1 }}</td>
              <td>{{ $jd->dosen_nama }}</td>
              <td>{{ $jd->judul }}</td>
              <td>{{ $jd->deskripsi }}</td>
              <td>{{ $jd->deskripsi_syarat }}</td>
            <td>
                @if($pendaftaranDitutup)
                  <button type="button" class="btn btn-secondary btn-sm" disabled>
                    Pendaftaran Ditutup
                  </button>
                @elseif($sudahMendaftar)
                  <button type="button" class="btn btn-secondary btn-sm" disabled>
                    Sudah Terdaftar
                  </button>
                @else
                  <button type="button" class="btn btn-primary btn-sm btn-ambil-tawaran"
                    data-id="{{ $jd->id }}"
                    data-dosen="{{ $jd->dosen_nama }}"
                    data-judul="{{ $jd->judul }}"
                    data-deskripsi="{{ $jd->deskripsi }}"
                    data-syarat="{{ $jd->deskripsi_syarat }}">
                    Ambil tawaran
                  </button>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h6 class="kp-list-title mb-3">Judul Mahasiswa</h6>
        <form method="POST" action="{{ url('/ta/store') }}" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
              <label>Judul Penelitian</label>
              <textarea name="judul" class="form-control" rows="3" placeholder="Ketik judul penelitian"></textarea>
          </div>

          <div class="form-group">
            <label>Deskripsi Judul</label>
<textarea name="deskripsi" class="form-control" placeholder="Ketik deskripsi" rows="5" maxlength="500"></textarea>          </div>
          <div class="form-group">
            <label>Unggah dokumen</label>
            <input type="file" name="file" class="form-control">
          </div>
          @if($pendaftaranDitutup)
            <button type="submit" class="btn btn-secondary" disabled>Kirim</button>
          @elseif($sudahMendaftar)
            <button type="submit" class="btn btn-secondary" disabled>Sudah Terdaftar</button>
          @else
            <button type="submit" class="btn btn-primary">Kirim</button>
          @endif
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h6 class="kp-list-title mb-3">Status Pendaftaran</h6>
        <table class="table">
          <thead>
            <tr>
              <th>No.</th>
              <th>Judul</th>
              <th>Deskripsi</th>
              <th>Dokumen</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($status_pendaftaran_sendiri as $no => $sp)
            <tr>
              <td>{{ $no+1 }}</td>
              <td>{{ $sp->judul }}</td>
              <td>{{ $sp->deskripsi }}</td>
              <td>
                @if($sp->file)
                  <a href="{{ route('storage.file', ['path' => $sp->file]) }}" target="_blank">doc</a>
                @else
                  Tidak ada file
                @endif
              </td>
              <td>
                @if($sp->status && $sp->status->name == 'menunggu')
                  <span class="btn btn-warning btn-sm">Menunggu</span>
                @elseif($sp->status && $sp->status->name == 'disetujui')
                  <span class="btn btn-success btn-sm">Disetujui</span>
                @elseif($sp->status && $sp->status->name == 'ditolak')
                  <span class="btn btn-danger btn-sm">Ditolak</span>
                @elseif($sp->status)
                  <span class="btn btn-secondary btn-sm">{{ $sp->status->name }}</span>
                @else
                  <span class="btn btn-secondary btn-sm">Status Tidak Diketahui</span>
                @endif
              </td>
              <td>
                @if(!$pendaftaranDitutup && $sp->status && $sp->status->name !== 'disetujui')
                  <button type="button" class="btn btn-warning btn-sm btn-edit-ta"
                    data-id="{{ $sp->id }}"
                    data-judul="{{ $sp->judul }}"
                    data-deskripsi="{{ $sp->deskripsi }}">
                    Edit
                  </button>
                  <form method="POST" action="{{ url('/ta/' . $sp->id) }}" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus?')">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                  </form>
                @elseif($sp->status && $sp->status->name == 'disetujui')
                  <span class="text-success">Pendaftaran Selesai</span>
                @else
                  <span class="text-muted">Pendaftaran Ditutup</span>
                @endif
              </td>
            </tr>
            @endforeach
            @foreach($status_pendaftaran_tawaran as $no => $sp)
            <tr>
              <td>{{ $status_pendaftaran_sendiri->count() + $no + 1 }}</td>
              <td>{{ $sp->pendaftaran->judul ?? 'N/A' }}</td>
              <td>{{ $sp->pendaftaran->deskripsi ?? 'N/A' }}</td>
              <td>
                @if($sp->file_portofolio)
                  <a href="{{ route('storage.file', ['path' => $sp->file_portofolio]) }}" target="_blank">portofolio</a>
                @else
                  Tidak ada file
                @endif
              </td>
              <td>
                @if($sp->status && $sp->status->name == 'menunggu')
                  <span class="btn btn-warning btn-sm">Menunggu</span>
                @elseif($sp->status && $sp->status->name == 'disetujui')
                  <span class="btn btn-success btn-sm">Disetujui</span>
                @elseif($sp->status && $sp->status->name == 'ditolak')
                  <span class="btn btn-danger btn-sm">Ditolak</span>
                @elseif($sp->status)
                  <span class="btn btn-secondary btn-sm">{{ $sp->status->name }}</span>
                @else
                  <span class="btn btn-secondary btn-sm">Status Tidak Diketahui</span>
                @endif
              </td>
              <td></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Modal Ambil Tawaran -->
<div class="modal fade" id="modalAmbilTawaran" tabindex="-1" aria-labelledby="modalAmbilTawaranLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <form method="POST" action="{{ url('/ta/store-transaksi') }}" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0" id="modalAmbilTawaranLabel">Ambil Tawaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="card-body">
          <input type="hidden" name="ta_pendaftaran_id" id="modal_ta_pendaftaran_id">
          <input type="hidden" name="ref_status_ta_id" value="1">
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Dosen</label>
            <div class="col-sm-9">
<p class="col-sm-3 col-form-label" id="modal_dosen"></p>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Judul</label>
            <div class="col-sm-9">
<p class="col-sm-3 col-form-label" id="modal_judul"></p>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Deskripsi</label>
            <div class="col-sm-9">
<p class="col-sm-3 col-form-label" id="modal_deskripsi"></p>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Syarat</label>
            <div class="col-sm-9">
<p class="col-sm-3 col-form-label" id="modal_syarat"></p>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="file_portofolio" class="col-sm-3 col-form-label">Unggah Portofolio</label>
            <div class="col-sm-9">
              <input type="file" class="form-control" id="file_portofolio" name="file_portofolio" required>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <button type="submit" class="btn btn-primary me-2">Ambil tawaran</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit TA -->
<div class="modal fade" id="modalEditTA" tabindex="-1" aria-labelledby="modalEditTALabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="" enctype="multipart/form-data" id="editTAForm" class="modal-content">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0" id="modalEditTALabel">Edit Judul TA</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="card-body">
          <input type="hidden" name="ta_id" id="edit_ta_id">
          <div class="mb-3">
            <label for="edit_judul" class="form-label">Judul Penelitian</label>
            <textarea name="judul" class="form-control" id="edit_judul" rows="3" placeholder="Ketik judul penelitian"></textarea>
          </div>
          <div class="mb-3">
            <label for="edit_deskripsi" class="form-label">Deskripsi Judul</label>
            <textarea name="deskripsi" class="form-control" id="edit_deskripsi" rows="5" maxlength="500" placeholder="Ketik deskripsi"></textarea>
          </div>
          <div class="mb-3">
            <label for="edit_file" class="form-label">Unggah dokumen (opsional)</label>
            <input type="file" class="form-control" id="edit_file" name="file">
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <button type="submit" class="btn btn-primary me-2">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Toast Notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3">
  <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="successMessage"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
  <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="errorMessage"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var modal = new bootstrap.Modal(document.getElementById('modalAmbilTawaran'));
  document.querySelectorAll('.btn-ambil-tawaran').forEach(function(button) {
    button.addEventListener('click', function() {
      document.getElementById('modal_ta_pendaftaran_id').value = this.dataset.id;
      document.getElementById('modal_dosen').textContent = this.dataset.dosen;
      document.getElementById('modal_judul').textContent = this.dataset.judul;
      document.getElementById('modal_deskripsi').textContent = this.dataset.deskripsi;
      document.getElementById('modal_syarat').textContent = this.dataset.syarat;
      modal.show();
    });
  });

  var editModal = new bootstrap.Modal(document.getElementById('modalEditTA'));
  document.querySelectorAll('.btn-edit-ta').forEach(function(button) {
    button.addEventListener('click', function() {
      var id = this.dataset.id;
      document.getElementById('edit_ta_id').value = id;
      document.getElementById('edit_judul').value = this.dataset.judul;
      document.getElementById('edit_deskripsi').value = this.dataset.deskripsi;
      document.getElementById('editTAForm').action = '/ta/' + id;
      editModal.show();
    });
  });
});

// Toast notifications
document.addEventListener('DOMContentLoaded', function() {
  @if(session('success'))
    document.getElementById('successMessage').textContent = '{{ session("success") }}';
    var successToast = new bootstrap.Toast(document.getElementById('successToast'));
    successToast.show();
  @endif

  @if(session('error'))
    document.getElementById('errorMessage').textContent = '{{ session("error") }}';
    var errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
    errorToast.show();
  @endif

  @if(session('message'))
    document.getElementById('successMessage').textContent = '{{ session("message") }}';
    var successToast = new bootstrap.Toast(document.getElementById('successToast'));
    successToast.show();
  @endif
});
</script>

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

  /* Banner styles */
  .gambar {
    height: 200px !important;
    background-size: cover !important;
    background-position: center !important;
    border-radius: 12px;
    position: relative;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .gambar:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }

  .banner-text {
    position: absolute;
    bottom: 10px;
    left: 10px;
    right: 10px;
    color: #ffff;
    background: rgba(0, 0, 0, 0.6);
    padding: 12px;
    border-radius: 8px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
  }

  /* Table styling */
  .table {
    background: transparent;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: none !important;
    color: #374151;
    font-weight: 600;
    padding: 16px 12px;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    position: relative;
  }

  .table thead th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
  }

  .table tbody td {
    border: none !important;
    padding: 16px 12px;
    background: rgba(255, 255, 255, 0.8);
    color: #374151;
    font-size: 0.875rem;
    vertical-align: middle;
    transition: all 0.3s ease;
  }

  .table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.3s ease;
  }

  .table tbody tr:hover {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.02), rgba(5, 150, 105, 0.02));
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
  }

  .table tbody tr:hover td {
    background: transparent;
    color: #065f46;
  }

  .table tbody tr:last-child {
    border-bottom: none;
  }

  /* Responsive fix */
  @media (max-width: 992px) {
    .section-container {
      flex-direction: column;
    }
  }
</style>
