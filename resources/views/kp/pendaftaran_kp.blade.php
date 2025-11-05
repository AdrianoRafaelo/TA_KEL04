@extends('layouts.app')

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">Kerja Praktik</a></li>
        <li class="breadcrumb-item active" aria-current="page">
          <a href="{{ url('/pendaftaran-kp') }}" style="color:inherit; text-decoration:none;">Pendaftaran KP</a>
        </li>
      </ol>
    </nav>
    <h4 class="mb-2">Pendaftaran KP</h4>
  </div>
</div>

<div class="row mb-3">
  <div class="col-12">
    <div class="kp-tabs">
      <a href="{{ url('/kerja-praktik') }}" class="kp-tab">Informasi Umum</a>
      <a href="{{ url('/pendaftaran-kp') }}" class="kp-tab active">Pendaftaran KP</a>
      <button class="kp-tab" disabled>Pelaksanaan KP</button>
      <button class="kp-tab" disabled>Seminar KP</button>
    </div>
  </div>
</div>

<div class="row mb-3">
  <div class="col-12">
    <div class="kp-form-card">
      <div class="row">
        <div class="col-md-6 pr-md-4">
          <h5 class="kp-form-title mb-3">Surat Permohonan KP</h5>
          <form method="POST" action="{{ route('pendaftaran-kp.permohonan') }}">
            @csrf
            <div class="form-group">
              <label>Nama Perusahaan</label>
              <input type="text" name="nama_perusahaan" class="form-control" placeholder="Ketik nama perusahaan" required>
            </div>
            <div class="form-group">
              <label>Alamat Perusahaan</label>
              <input type="text" name="alamat_perusahaan" class="form-control" placeholder="Ketik alamat perusahaan" required>
            </div>
            <div class="form-group">
              <label>Waktu Awal KP</label>
              <input type="date" name="waktu_awal_kp" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Waktu Selesai KP</label>
              <input type="date" name="waktu_selesai_kp" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Tahun Ajaran</label>
              <input type="text" name="tahun_ajaran" class="form-control" placeholder="Ketik tahun ajaran" required>
            </div>
            <div class="form-group">
              <label>Tambahkan Mahasiswa</label>
              <input type="text" name="mahasiswa" class="form-control" placeholder="Ketik nama mahasiswa" required>
            </div>
            <button type="submit" class="btn btn-primary">Req Surat</button>
          </form>
        </div>
        <div class="col-md-6 pl-md-4 border-left">
          <h5 class="kp-form-title mb-3">Surat Pengantar KP</h5>
          <form method="POST" action="{{ route('pendaftaran-kp.pengantar') }}">
            @csrf
            <div class="form-group">
              <label>Perusahaan KP</label>
              <select name="company_id" class="form-control" required>
                <option value="">Pilih Perusahaan KP</option>
                @if(isset($permohonan_requests))
                  @foreach($permohonan_requests as $request)
                    <option value="{{ $request->company_id }}">{{ $request->company->nama_perusahaan ?? 'N/A' }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="form-group">
              <label>Supervisor</label>
              <input type="text" name="nama_supervisor" class="form-control" placeholder="Ketik nama supervisor" required>
            </div>
            <div class="form-group">
              <label>No. Supervisor</label>
              <input type="text" name="no_supervisor" class="form-control" placeholder="Ketik nomor supervisor" required>
            </div>
            <button type="submit" class="btn btn-primary">Req Surat</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-4">
    <div class="kp-list-card">
      <h6 class="kp-list-title mb-3">Unduh Surat Permohonan</h6>
      @if(isset($approved_permohonan_requests) && $approved_permohonan_requests->count() > 0)
        @foreach($approved_permohonan_requests as $request)
          <div class="kp-list-item">{{ $request->company->nama_perusahaan ?? 'N/A' }} <span class="kp-badge kp-badge-unduh"><a href="{{ route('download.permohonan', $request->id) }}" class="text-white">Unduh</a></span></div>
        @endforeach
      @else
        <div class="kp-list-item text-muted">Belum ada surat permohonan yang disetujui</div>
      @endif
    </div>
  </div>
  <div class="col-md-4">
    <div class="kp-list-card">
      <h6 class="kp-list-title mb-3">Unduh Surat Pengantar</h6>
      @if(isset($pengantar_requests) && $pengantar_requests->count() > 0)
        @foreach($pengantar_requests as $request)
          <div class="kp-list-item">{{ $request->company->nama_perusahaan ?? 'N/A' }} <span class="kp-badge kp-badge-unduh"><a href="{{ route('download.pengantar', $request->id) }}" class="text-white">Unduh</a></span></div>
        @endforeach
      @else
        <div class="kp-list-item text-muted">Belum ada surat pengantar</div>
      @endif
    </div>
  </div>
  <div class="col-md-4">
    <div class="kp-list-card">
      <h6 class="kp-list-title mb-3">Perusahaan KP Final</h6>
      @if(isset($final_companies) && $final_companies->count() > 0)
        @foreach($final_companies as $company)
          <div class="kp-list-item">{{ $company->nama_perusahaan }} <span class="kp-badge kp-badge-hapus"><a href="#" class="text-white">Hapus</a></span></div>
        @endforeach
      @else
        <div class="kp-list-item text-muted">Belum ada perusahaan final</div>
      @endif
    </div>
  </div>
</div>
@endsection