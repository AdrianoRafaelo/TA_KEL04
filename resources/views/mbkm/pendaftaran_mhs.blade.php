@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Page</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">MBKM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Informasi Umum</li>
            </ol>
        </nav>
        <h4 class="mb-2">MBKM</h4>
    </div>
</div>


<div class="row mb-3">
    <div class="col-12">
        <div class="kp-tabs">
            <button class="kp-tab">Informasi Umum</button>
            <a href="{{ url('/mbkm/pendaftaran-mhs') }}" class="kp-tab active">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-mhs')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-mhs')}}" class="kp-tab">Seminar MBKM</a>
        </div>
    </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-8">
    <div class="kp-form-card">
      <div class="row justify-content-center">
<div class="col-md-13">
          <h5 class="kp-form-title mb-3">MBKM MITRA</h5>
          <form method="POST" action="{{ route('mbkm.pendaftaran-mhs.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label>Nama</label>
              <input type="text" name="nama" class="form-control" value="{{ auth()->user()->name ?? '' }}" readonly required>
            </div>
            <div class="form-group">
              <label>NIM</label>
              <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" required>
            </div>
            <div class="form-group">
              <label>Semester</label>
              <input type="text" name="semester" class="form-control" placeholder="Masukkan Semester" required>
            </div>
            <div class="form-group">
              <label>IPK</label>
              <input type="number" step="0.01" name="ipk" class="form-control" placeholder="Masukkan IPK" min="0" max="4" required>
            </div>
            <div class="form-group">
              <label>Matakuliah Ekuivalensi</label>
              <textarea name="matakuliah_ekuivalensi" class="form-control" rows="3" placeholder="Masukkan Matakuliah Ekuivalensi" required></textarea>
            </div>
            <div class="form-group">
              <label>Mitra</label>
              <select name="mitra_id" class="form-control" required>
                <option value="">Pilih Mitra</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->nama_perusahaan }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Unggah Portofolio</label>
              <input type="file" name="portofolio_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" required>
            </div>
            <div class="form-group">
              <label>Unggah CV</label>
              <input type="file" name="cv_file" class="form-control" accept=".pdf,.doc,.docx,jpg,png" required>
            </div>
            <div class="form-group">
              <label>Masa MBKM</label>
              <input type="text" name="masa_mbkm" class="form-control" placeholder="Masukkan masa MBKM" required>
            </div>
            <button type="submit" class="btn btn-primary">Req Surat</button>
          </form>
        </div>
        </div>
    </div>
  </div>
</div>
@endsection
