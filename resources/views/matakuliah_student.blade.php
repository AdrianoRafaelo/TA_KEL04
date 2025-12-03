@extends('layouts.app')

@section('content')

<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Matakuliah</li>
      </ol>
    </nav>
    <h4 class="mb-2">Daftar Matakuliah</h4>
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
        <h5 class="card-title mb-3">Pilih Matakuliah</h5>
        <div class="d-flex flex-column" style="max-height: 600px; overflow-y: auto;">
          @foreach($kurikulum as $semester => $mk_list)
            <div class="mb-3">
              <div class="card h-100">
                <div class="card-header bg-primary text-white">
                  <h6 class="mb-0">Semester {{ $semester }}</h6>
                </div>
                <div class="card-body p-2">
                  <ul class="list-unstyled small">
                    @foreach($mk_list as $mk)
                      <li class="mb-2">
                        <a href="#" class="text-decoration-none matakuliah-link"
                           data-id="{{ $mk['id'] }}"
                           data-kode="{{ $mk['kode_mk'] }}"
                           data-nama="{{ $mk['nama_mk'] }}"
                           data-nama-eng="{{ $mk['nama_mk_eng'] }}"
                           data-semester="{{ $mk['semester'] }}"
                           data-sks="{{ $mk['sks'] }}"
                           data-deskripsi="{{ $mk['deskripsi_mk'] }}"
                           data-dosen="{{ $mk['dosen_nama'] ?? 'Belum ditentukan' }}"
                           data-cpmk="{{ json_encode($mk['cpmk']) }}">
                          <strong>{{ $mk['kode_mk'] }}</strong> - {{ $mk['nama_mk'] }} ({{ $mk['sks'] }} SKS)
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-3">Detail Matakuliah</h5>
        <div id="matakuliah-detail">
          <div class="text-center text-muted py-5">
            <i class="mdi mdi-notebook-outline" style="font-size: 3rem;"></i>
            <p class="mt-3">Silakan pilih matakuliah dari daftar di sebelah kiri untuk melihat detail lengkap</p>
          </div>
        </div>
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

.matakuliah-link {
    color: #333;
    transition: color 0.3s ease;
}

.matakuliah-link:hover {
    color: #007bff !important;
    text-decoration: underline !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.matakuliah-link');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all links
            links.forEach(l => l.classList.remove('text-primary', 'fw-bold'));
            // Add active class to clicked link
            this.classList.add('text-primary', 'fw-bold');

            const data = this.dataset;
            const cpmk = JSON.parse(data.cpmk || '[]');

            let cpmkHtml = '';
            if (cpmk.length > 0) {
                cpmkHtml = '<h6 class="mt-3">Capaian Pembelajaran Mata Kuliah (CPMK):</h6><ul class="list-group list-group-flush">';
                cpmk.forEach((item, index) => {
                    cpmkHtml += `<li class="list-group-item"><strong>CPMK ${index + 1}:</strong> ${item}</li>`;
                });
                cpmkHtml += '</ul>';
            }

            const detailHtml = `
                <div class="matakuliah-info">
                    <div class="mb-3">
                        <h6 class="text-primary">${data.kode} - ${data.nama}</h6>
                        ${data.namaEng ? `<p class="text-muted mb-1">${data.namaEng}</p>` : ''}
                        <p class="mb-1"><strong>Semester:</strong> ${data.semester}</p>
                        <p class="mb-1"><strong>SKS:</strong> ${data.sks}</p>
                        <p class="mb-1"><strong>Dosen Pengampu:</strong> ${data.dosen}</p>
                    </div>

                    ${data.deskripsi ? `
                    <div class="mb-3">
                        <h6>Deskripsi:</h6>
                        <p>${data.deskripsi}</p>
                    </div>
                    ` : ''}

                    ${cpmkHtml}
                </div>
            `;

            document.getElementById('matakuliah-detail').innerHTML = detailHtml;
        });
    });
});
</script>