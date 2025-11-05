@extends('layouts.app')

@section('content')
  <style>
    /* ========================================================================= */
    /* 1. BANNER SECTION */
    /* ========================================================================= */
    .banner-card {
      height: 150px;
      /* Tinggi disesuaikan */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      border-radius: 8px;
      /* Sudut membulat */
      overflow: hidden;
      position: relative;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .banner-text {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 10px;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0));
      color: white;
      font-weight: bold;
      font-size: 14px;
      line-height: 1.2;
    }


    /* ========================================================================= */
    /* 2. INFORMASI CARD (KOLOM KIRI BESAR) */
    /* ========================================================================= */
    .info-card {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      /* Bayangan halus */
      border: 1px solid #e0e0e0;
      min-height: 500px;
      /* Memberikan tinggi minimum */
    }

    /* Penataan setiap bagian kategori (Kompetisi, Magang, Info Umum) */
    .info-section {
      position: relative;
      padding-top: 10px;
      padding-bottom: 10px;
    }

    /* Button Filter */
    .btn-filter {
      position: absolute;
      top: 5px;
      right: 0;
      background: none;
      border: 1px solid #ccc;
      color: #666;
      padding: 2px 8px;
      border-radius: 4px;
      font-size: 12px;
      cursor: pointer;
    }

    .btn-filter:hover {
      background-color: #f5f5f5;
    }

    /* Item Informasi di dalam kategori */
    .info-item {
      padding-top: 5px;
      padding-bottom: 5px;
    }

    .info-item b {
      display: block;
      margin-bottom: 2px;
      color: #333;
      font-size: 14px;
    }

    .info-item p {
      margin-bottom: 0;
      font-size: 13px;
    }


    /* ========================================================================= */
    /* 3. SMALL CARDS (MBKM, TUGAS AKHIR, KERJA PRAKTIK) */
    /* ========================================================================= */
    .info-small-card {
      background-color: #ffffff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      border: 1px solid #e0e0e0;
      margin-bottom: 20px;
      /* Memberi jarak antar kartu kecil */
    }

    /* Style untuk kontainer ikon dan teks */
    .item-icon {
      display: flex;
      align-items: center;
      padding: 8px 0;
      margin-bottom: 5px;
    }

    .item-icon i {
      font-size: 24px;
      margin-right: 15px;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
    }

    .item-icon div p {
      line-height: 1.2;
    }

    /* Warna Ikon */
    .item-icon.orange i {
      background-color: #fff1e5;
      /* Latar belakang oranye muda */
      color: #ff9933;
      /* Ikon oranye */
    }

    .item-icon.blue i {
      background-color: #e5f4ff;
      /* Latar belakang biru muda */
      color: #3399ff;
      /* Ikon biru */
    }
  </style>
  <div class="container-fluid py-4">
    <div class="mb-2">
      <small>Page / <b><a href="{{ route('welcome') }}" class="text-decoration-none text-black">Beranda</a></b></small>
      <h5 class="fw-bold mt-1">Beranda</h5>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="banner-card" style="background-image:url('/assets/images/solar.jpg')">
          <div class="banner-text">Peran Manajemen Rekayasa Dalam Peningkatan Energi Terbarukan</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="banner-card" style="background-image:url('/assets/images/wind.jpg')">
          <div class="banner-text">Peraturan Pemerintah Melalui Gerakan Hijau</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="banner-card" style="background-image:url('/assets/images/solar2.jpg')">
          <div class="banner-text">Peran Manajemen Rekayasa Dalam Peningkatan Energi Terbarukan</div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-6">
        <div class="info-card">
          <h5 class="mb-3 fw-bold">Informasi</h5>

          {{-- KATEGORI: KOMPETISI --}}
          <div class="info-section">
            <h6 class="text-primary fw-bold">Kompetisi</h6>
            <button class="btn-filter">
              Filter
              <i class="mdi mdi-chevron-down"></i>
            </button>

            @forelse ($pengumuman_kompetisi as $item)
              <div class="info-item @if(!$loop->first) border-top pt-2 @endif">
                <b>{{ $item->judul }}</b>
                <p class="text-muted">{{ Str::limit($item->deskripsi, 80) }}</p>
              </div>
            @empty
              <div class="info-item">
                <p class="text-muted small">Saat ini belum ada pengumuman Kompetisi.</p>
              </div>
            @endforelse
          </div>

          {{-- KATEGORI: MAGANG --}}
          <div class="info-section mt-3">
            <h6 class="text-primary fw-bold">Magang</h6>
            @forelse ($pengumuman_magang as $item)
              <div class="info-item @if(!$loop->first) border-top pt-2 @endif">
                <b>{{ $item->judul }}</b>
                <p class="text-muted">{{ Str::limit($item->deskripsi, 80) }}</p>
              </div>
            @empty
              <div class="info-item">
                <p class="text-muted small">Saat ini belum ada pengumuman Magang.</p>
              </div>
            @endforelse
          </div>

          {{-- KATEGORI: INFO UMUM --}}
          <div class="info-section mt-3">
            <h6 class="text-primary fw-bold">Info Umum</h6>
            @forelse ($pengumuman_umum as $item)
              <div class="info-item @if(!$loop->first) border-top pt-2 @endif">
                <b>{{ $item->judul }}</b>
                <p class="text-muted">{{ Str::limit($item->deskripsi, 80) }}</p>
              </div>
            @empty
              <div class="info-item">
                <p class="text-muted small">Saat ini belum ada pengumuman Umum.</p>
              </div>
            @endforelse
          </div>

          {{-- Link Kelola Pengumuman (Hanya Admin) --}}
          @auth
            @if (auth()->user()->isAdmin()) {{-- Sesuaikan dengan fungsi isAdmin() Anda --}}
              <a href="{{ route('pengumuman.index') }}" class="text-primary small fw-bold mt-3 d-block">Kelola
                Informasi/Pengumuman (Admin)</a>
            @else
              <a href="#" class="text-primary small fw-bold mt-3 d-block">+ Tambah Informasi</a>
            @endif
          @endauth
        </div>
      </div>

      <div class="col-md-3 col-lg-3">
        <div class="info-small-card">
          <h6 class="fw-bold text-primary mb-2">MBKM</h6>
          <div class="item-icon orange">
            <i class="mdi mdi-account-check"></i>
            <div>
              <a href="#" class="text-primary fw-bold">Persetujuan</a>
              <p class="text-muted small">15 Mahasiswa</p>
            </div>
          </div>
          <div class="item-icon blue">
            <i class="mdi mdi-briefcase-outline"></i>
            <div>
              <a href="#" class="text-primary fw-bold">Dosen Pembimbing</a>
              <p class="text-muted small">5 Mahasiswa</p>
            </div>
          </div>
        </div>

        <div class="info-small-card">
          <h6 class="fw-bold text-primary mb-2">Tugas Akhir</h6>
          <div class="item-icon orange">
            <i class="mdi mdi-file-document-edit-outline"></i>
            <div>
              <a href="#" class="text-primary fw-bold">Terima Mahasiswa</a>
              <p class="text-muted small">15 Mahasiswa</p>
            </div>
          </div>
          <div class="item-icon blue">
            <i class="mdi mdi-briefcase-outline"></i>
            <div>
              <a href="#" class="text-primary fw-bold">Dosen Pembimbing</a>
              <p class="text-muted small">4 Mahasiswa</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-lg-3">
        <div class="info-small-card">
          <h6 class="fw-bold text-primary mb-2">Kerja Praktik</h6>
          <div class="item-icon blue">
            <i class="mdi mdi-briefcase-outline"></i>
            <div>
              <a href="#" class="text-primary fw-bold">Dosen Pembimbing</a>
              <p class="text-muted small">3 Kelompok</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection