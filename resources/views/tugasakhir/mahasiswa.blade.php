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


<div class="row mb-3">
  <div class="col-12">
    <img src="{{ asset('img/panel surya.jpeg') }}" class="kp-banner img-fluid" alt="Banner TA">
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
                  <a href="{{ asset('storage/' . $sp->file) }}" download>doc</a>
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
            </tr>
            @endforeach
            @foreach($status_pendaftaran_tawaran as $no => $sp)
            <tr>
              <td>{{ $status_pendaftaran_sendiri->count() + $no + 1 }}</td>
              <td>{{ $sp->pendaftaran->judul ?? 'N/A' }}</td>
              <td>{{ $sp->pendaftaran->deskripsi ?? 'N/A' }}</td>
              <td>
                @if($sp->file_portofolio)
                  <a href="{{ asset('storage/' . $sp->file_portofolio) }}" download>portofolio</a>
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
});
</script>
