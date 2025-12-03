
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
      <button class="kp-tab active">Pendaftaran TA</button>
      <a href="{{ route('seminar.proposal') }}" class="kp-tab">Seminar Proposal</a>
      <button class="kp-tab" disabled>Seminar Hasil</button>
      <button class="kp-tab" disabled>Sidang Akhir</button>
      <button class="kp-tab" disabled>Bimbingan</button>
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
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h6 class="kp-list-title mb-3">Usulan Judul Dosen</h6>
        <form method="POST" action="{{ url('/ta/store') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="is_dosen" value="1">
            <div class="form-group">
                <label>Judul penelitian</label>
                <textarea name="judul" class="form-control" rows="3" placeholder="Ketik judul penelitian"></textarea>
            </div>

            <div class="form-group">
                <label>Deskripsi judul</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Ketik deskripsi"></textarea>
            </div>

          @if($pendaftaranDitutup)
            <button type="submit" class="btn btn-secondary" disabled>Kirim</button>
          @else
            <button type="submit" class="btn btn-warning">Kirim</button>
          @endif
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h6 class="kp-list-title mb-3">Mahasiswa Mendaftar</h6>
        <table class="table">
          <thead>
            <tr>
              <th>Judul</th>
              <th>Nama</th>
              <th>Dokumen</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($mahasiswa_mendaftar as $mm)
            <tr>
              <td>{{ $mm->pendaftaran->judul ?? 'N/A' }}</td>
              <td>{{ $mm->nama }} @if($mm->nim) ({{ $mm->nim }}) @endif</td>
              <td>
                @if($mm->file_portofolio)
                  <a href="{{ asset('storage/' . $mm->file_portofolio) }}" download class="btn btn-link btn-sm">Download Portofolio</a>
                @else
                  Tidak ada file
                @endif
              </td>
              <td>
                @if($mm->status && $mm->status->name == 'disetujui')
                  <span class="btn btn-success btn-sm">Disetujui</span>
                @elseif($mm->status && $mm->status->name == 'ditolak')
                  <span class="btn btn-danger btn-sm">Ditolak</span>
                @else
                  @if($pendaftaranDitutup)
                    <span class="btn btn-secondary btn-sm" disabled>Pendaftaran Ditutup</span>
                  @else
                    <form method="POST" action="{{ route('ta.terimaTransaksi', ['id' => $mm->id]) }}">
                      @csrf
                      <button type="submit" class="btn btn-primary btn-sm">Terima</button>
                    </form>
                  @endif
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
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h6 class="kp-list-title mb-3">Judul Mahasiswa</h6>
        <table class="table">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nama (NIM)</th>
              <th>Judul</th>
              <th>Deskripsi</th>
              <th>Proposal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($judul_mahasiswa as $no => $jm)
            <tr>
              <td>{{ $no+1 }}</td>
              <td>{{ $jm->nama }} @if($jm->nim) ({{ $jm->nim }}) @endif</td>
              <td>{{ $jm->judul }}</td>
              <td>{{ $jm->deskripsi }}</td>
              <td>
                @if($jm->file)
                  <a href="{{ asset('storage/' . $jm->file) }}" download class="btn btn-link btn-sm">Download Proposal</a>
                @else
                  Tidak ada file
                @endif
              </td>
              <td>
                @php
                  $alreadyTaken = $jm->transaksi->where('username', $username)->isNotEmpty();
                @endphp
                @if($alreadyTaken)
                  <span class="btn btn-success btn-sm">Sudah Diambil</span>
                @else
                  @if($pendaftaranDitutup)
                    <span class="btn btn-secondary btn-sm" disabled>Pendaftaran Ditutup</span>
                  @else
                    <form method="POST" action="{{ url('/ta/store-transaksi') }}" style="display:inline;">
                      @csrf
                      <input type="hidden" name="ta_pendaftaran_id" value="{{ $jm->id }}">
                      <input type="hidden" name="ref_status_ta_id" value="1"> <!-- Assuming 1 is some status -->
                      <button type="submit" class="btn btn-primary btn-sm">Ambil Judul</button>
                    </form>
                  @endif
                @endif
              </td>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

<style>
          .gambar {
    height: 200px !important;
    background-size: cover !important;
    background-position: center !important;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .banner-text {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        color: #ffff;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px;
        border-radius: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    }
</style>
