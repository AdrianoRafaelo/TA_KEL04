
@extends('layouts.app')

@section('content')
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

<div class="row mb-3">
  <div class="col-12">
    <img src="{{ asset('assets/images/banner-ta.jpg') }}" class="kp-banner img-fluid" alt="Banner TA">
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
            <input type="text" name="judul" class="form-control" placeholder="Ketik judul penelitian">
          </div>
          <div class="form-group">
            <label>Deskripsi judul</label>
            <input type="text" name="deskripsi" class="form-control" placeholder="Ketik deskripsi">
          </div>
          <button type="submit" class="btn btn-warning">Kirim</button>
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
              <td>{{ $mm->username }}</td>
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
                  <form method="POST" action="{{ route('ta.terimaTransaksi', ['id' => $mm->id]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Terima</button>
                  </form>
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
              <th>Mahasiswa</th>
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
              <td>{{ $jm->created_by }}</td>
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
                  <form method="POST" action="{{ url('/ta/store-transaksi') }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="ta_pendaftaran_id" value="{{ $jm->id }}">
                    <input type="hidden" name="ref_status_ta_id" value="1"> <!-- Assuming 1 is some status -->
                    <button type="submit" class="btn btn-primary btn-sm">Ambil Judul</button>
                  </form>
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
