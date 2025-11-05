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

<div class="row mb-3">
  <div class="col-12">
    <img src="{{ asset('img/panel surya.jpeg') }}" class="kp-banner img-fluid" alt="Banner Matakuliah">
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