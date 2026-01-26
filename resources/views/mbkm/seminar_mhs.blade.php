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
            <a href="{{ url('/mbkm/pendaftaran-mhs') }}" class="kp-tab">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-mhs')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-mhs')}}" class="kp-tab active">Seminar MBKM</a>
        </div>
    </div>
</div>

<!-- informasi mbkm mahasiswa -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-4">
            <!-- Info Kiri -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <small class="text-muted">Nama/NIM</small>
                        <h6 class="fw-bold mb-0">Nama Mahasiswa</h6>
                        <small class="text-muted">Fakultas / 123456789</small>
                        <small class="text-muted d-block mt-1">Anggota Kelompok: Anggota 1, Anggota 2</small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <small class="text-muted">Posisi (Divisi)</small>
                        <h6 class="fw-bold mb-0">Divisi Contoh</h6>
                    </div>
                </div>
            </div>

            <!-- Info Kanan -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <small class="text-muted">Perusahaan</small>
                        <h6 class="fw-bold mb-0">Nama Perusahaan</h6>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <small class="text-muted">Pembimbing</small>
                        <h6 class="fw-bold mb-0">Nama Pembimbing</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- laporan mbkm -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-4">
                    <i class="mdi mdi-upload"></i> Unggah Laporan MBKM
                </h5>

                @foreach($mkKonversis as $mkKonversi)
                <div class="mb-5">
                    <h6 class="fw-bold">Laporan ({{ $mkKonversi->kurikulum->nama_mk }})</h6>
                    <div class="mb-4">
                        <label class="fw-bold">Apakah kegiatan termasuk magang?</label><br>
                        <label class="me-3"><input type="radio" name="is_magang_ekotek_{{ $mkKonversi->id }}" value="1" {{ old('is_magang', $seminars->where('mk_konversi_id', $mkKonversi->id)->first()->is_magang ?? false) ? 'checked' : '' }}> Ya</label>
                        <label><input type="radio" name="is_magang_ekotek_{{ $mkKonversi->id }}" value="0" {{ !old('is_magang', $seminars->where('mk_konversi_id', $mkKonversi->id)->first()->is_magang ?? false) ? 'checked' : '' }}> Tidak</label>
                    </div>

                    <form id="ekotek-form-{{ $mkKonversi->id }}" action="{{ route('mbkm.seminar-ekotek.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="mk_konversi_id" value="{{ $mkKonversi->id }}">
                        <input type="hidden" name="is_magang" value="{{ old('is_magang_ekotek_' . $mkKonversi->id, $seminars->where('mk_konversi_id', $mkKonversi->id)->first()->is_magang ?? 0) }}">

                
                        <label class="fw-bold d-block mb-1">CPMK</label>
                        <textarea class="form-control mb-3" rows="4" name="cpmk_ekotek" placeholder="Masukkan Capaian Pembelajaran Mata Kuliah (CPMK) untuk laporan EKOTEK">{{ old('cpmk_ekotek', $seminars->where('mk_konversi_id', $mkKonversi->id)->first()->cpmk_ekotek ?? '') }}</textarea>

                        <label class="fw-bold d-block mb-1">Unggah laporan {{ $mkKonversi->kurikulum->nama_mk }}</label>
                        <div class="input-group mb-2" style="max-width: 350px;">
                            <input type="file" class="form-control" name="laporan_ekotek_file" accept=".pdf,.doc,.docx">
                        </div>

                        <a href="#" class="small text-primary d-block mb-3">Template laporan</a>

                        <button type="submit" class="btn btn-dark px-4">Submit</button>
                    </form>
                </div>
                @endforeach

                <hr>


                
                <div class="text-end">
                    <button class="btn btn-dark px-5">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    <!-- unduh jadwal -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <span class="px-4 py-2 text-white fw-bold"
                          style="background:#1c1b3b; border-radius: 12px;">
                        Jadwal Seminar <i class="mdi mdi-calendar"></i>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Unduh Jadwal</span>
                    <a href="{{ route('mbkm.download.jadwal.seminar') }}" class="text-primary">Doc</a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
Swal.fire({
  title: 'Berhasil!',
  text: '{{ session('success') }}',
  icon: 'success',
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true
});
</script>
@endif
@if(session('error'))
<script>
Swal.fire({
  title: 'Error!',
  text: '{{ session('error') }}',
  icon: 'error',
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle radio button changes for EKOTEK forms
    @foreach($mkKonversis as $mkKonversi)
    const ekotekRadios{{ $mkKonversi->id }} = document.querySelectorAll('input[name="is_magang_ekotek_{{ $mkKonversi->id }}"]');
    const ekotekHidden{{ $mkKonversi->id }} = document.querySelector('#ekotek-form-{{ $mkKonversi->id }} input[name="is_magang"]');

    ekotekRadios{{ $mkKonversi->id }}.forEach(radio => {
        radio.addEventListener('change', function() {
            if (ekotekHidden{{ $mkKonversi->id }}) {
                ekotekHidden{{ $mkKonversi->id }}.value = this.value;
            }
        });
    });
    @endforeach

    // Handle radio button changes for PMB form
    const pmbRadios = document.querySelectorAll('input[name="is_magang_pmb"]');
    const pmbHidden = document.querySelector('#pmb-form input[name="is_magang"]');

    pmbRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (pmbHidden) {
                pmbHidden.value = this.value;
            }
        });
    });
});
</script>
@endsection








