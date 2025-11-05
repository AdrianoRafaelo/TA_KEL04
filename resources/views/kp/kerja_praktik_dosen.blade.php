@extends('layouts.app')

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">Kerja Praktik</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pelaksanaan KP</li>
      </ol>
    </nav>
    <h4 class="mb-2">Pelaksanaan KP</h4>
  </div>
</div>

<!-- Tabs -->
<div class="row mb-3">
  <div class="col-12">
    <div class="kp-tabs">
      <button class="kp-tab active">Pelaksanaan KP</button>
      <button class="kp-tab" disabled>Seminar KP</button>
    </div>
  </div>
</div>

<!-- Banner dengan 3 Gambar -->
<div class="row mb-3">
  <div class="col-12">
    <div class="d-flex overflow-auto gap-3 p-2" style="scrollbar-width: none; -ms-overflow-style: none;">
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-1.jpg') }}" class="img-fluid rounded" alt="Banner 1">
        <div class="position-absolute bottom-0 start-0 end-0 text-center text-white small fw-bold p-2" style="background: rgba(0,0,0,0.6); border-radius: 0 0 8px 8px;">
          Peran Manajemen Rekayasa Dalam Peningkatan Energi Terbarukan
        </div>
      </div>
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-2.jpg') }}" class="img-fluid rounded" alt="Banner 2">
        <div class="position-absolute bottom-0 start-0 end-0 text-center text-white small fw-bold p-2" style="background: rgba(0,0,0,0.6); border-radius: 0 0 8px 8px;">
          Peraturan Pemerintah Melalui Gerakan Hijau
        </div>
      </div>
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-3.jpg') }}" class="img-fluid rounded" alt="Banner 3">
        <div class="position-absolute bottom-0 start-0 end-0 text-center text-white small fw-bold p-2" style="background: rgba(0,0,0,0.6); border-radius: 0 0 8px 8px;">
          Peran Manajemen Rekayasa Dalam Peningkatan Energi Terbarukan
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tombol Kerja Praktik Informasi Umum -->
<div class="row mb-4">
  <div class="col-12">
    <button type="button" class="btn btn-outline-primary btn-lg px-4 py-2" data-bs-toggle="modal" data-bs-target="#informasiKpModal">
      <i class="fas fa-clipboard-list me-2"></i>Kerja Praktik Informasi Umum
    </button>
  </div>
</div>

<!-- Tabel Mahasiswa Bimbingan -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h6 class="kp-list-title mb-3 text-primary fw-bold">Mahasiswa Bimbingan</h6>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th width="5%">No.</th>
                <th>Mahasiswa</th>
                <th>Perusahaan KP</th>
                <th width="15%">Selengkapnya</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td>
                  <div>Hansel Septiyan Pasaribu</div>
                  <div class="text-muted small">Junaedy Siahaan</div>
                </td>
                <td>PT. MRT Jakarta Perseroda</td>
                <td>
                  <button class="btn btn-success btn-sm rounded-pill px-3">Lihat</button>
                </td>
              </tr>
              <tr>
                <td>2.</td>
                <td>
                  <div>Yuni Magdalena Sinaga</div>
                  <div class="text-muted small">Esti Sormin</div>
                  <div class="text-muted small">Natalia Hutagaol</div>
                  <div class="text-muted small">Agnes Fransiska Lisnawati</div>
                </td>
                <td>PT. DanLiris</td>
                <td>
                  <button class="btn btn-success btn-sm rounded-pill px-3">Lihat</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Informasi Kegiatan Kerja Praktik -->
<div class="modal fade" id="informasiKpModal" tabindex="-1" aria-labelledby="informasiKpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="informasiKpModalLabel">Informasi Kegiatan Kerja Praktik</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ url('/kp/informasi/store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <!-- Judul -->
          <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul" placeholder="Ketik judul informasi anda" required>
          </div>

          <!-- Deskripsi -->
          <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi Informasi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Ketik deskripsi informasi" required></textarea>
          </div>

          <!-- Tabel Mahasiswa Bimbingan dalam Modal -->
          <div class="card mb-3">
            <div class="card-body">
              <h6 class="card-title text-primary fw-bold mb-3">Mahasiswa Bimbingan</h6>
              <div class="table-responsive">
                <table class="table table-sm table-hover">
                  <thead class="table-light">
                    <tr>
                      <th width="5%">No.</th>
                      <th>Mahasiswa</th>
                      <th>Dokumen Lampiran</th>
                      <th width="20%">Expired</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1.</td>
                      <td>
                        <div>Hansel Septiyan Pasaribu</div>
                        <div class="text-muted small">Junaedy Siahaan</div>
                      </td>
                      <td>
                        <input type="file" class="form-control form-control-sm" name="dokumen_1" accept=".pdf,.doc,.docx">
                      </td>
                      <td>
                        <input type="date" class="form-control form-control-sm" name="expired_1">
                      </td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td>
                        <div>Yuni Magdalena Sinaga</div>
                        <div class="text-muted small">Esti Sormin</div>
                        <div class="text-muted small">Natalia Hutagaol</div>
                        <div class="text-muted small">Agnes Fransiska Lisnawati</div>
                      </td>
                      <td>
                        <input type="file" class="form-control form-control-sm" name="dokumen_2" accept=".pdf,.doc,.docx">
                      </td>
                      <td>
                        <input type="date" class="form-control form-control-sm" name="expired_2">
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Kirim Informasi</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection