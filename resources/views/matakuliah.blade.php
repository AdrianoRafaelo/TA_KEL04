@extends('layouts.app')

@section('content')


<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item active" aria-current="page">Matakuliah</li>
      </ol>
    </nav>
    <h4 class="mb-2">Matakuliah</h4>
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
        <div class="d-flex flex-column">
          <div class="row">
            @foreach(array_chunk($kurikulum->toArray(), ceil(count($kurikulum) / 2)) as $chunk)
              <div class="col-12">
                @foreach($chunk as $semester => $mk_list)
                  <div class="mb-3">
                    <div class="card h-100">
                      <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Semester {{ $semester }}</h6>
                      </div>
                      <div class="card-body p-2">
                        <ul class="list-unstyled small">
                          @foreach($mk_list as $mk)
                            <li class="mb-1">
                              <strong>{{ $mk['kode_mk'] }}</strong> - {{ $mk['nama_mk'] }} ({{ $mk['sks'] }} SKS)
                            </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5>Deskripsi Matakuliah</h5>
        <p>Silakan pilih matakuliah dari daftar di sebelah kiri untuk melihat deskripsi lengkap.</p>
        <!-- Tempat untuk deskripsi matakuliah, akan diisi secara dinamis berdasarkan pemilihan matakuliah -->
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
        position: relative;
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