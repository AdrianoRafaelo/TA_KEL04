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
            <a href="{{ url('/mbkm/pendaftaran-koordinator') }}" class="kp-tab active">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-koordinator')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-koordinator')}}" class="kp-tab">Seminar MBKM</a>
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

<!-- Menu Cards (Compact Version) -->
<div class="row g-3 mb-3">
    <!-- Tambah Mitra MBKM -->
    <div class="col-md-3">
        <button type="button" class="btn p-0 text-decoration-none w-100" data-bs-toggle="modal" data-bs-target="#tambahMitraModal">
            <div class="card border-0 shadow-sm p-2 d-flex flex-row align-items-center" style="border-radius: 10px;">
                <div class="me-2 bg-success-subtle p-2 rounded">
                    <i class="bi bi-people fs-5 text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-0">MBKM</h6>
                    <span class="text-dark fw-semibold" style="font-size: 0.86rem;">Tambah Mitra MBKM</span>
                </div>
            </div>
        </button>
    </div>

    <!-- Informasi Umum -->
    <div class="col-md-3">
        <a href="{{ url('/mbkm/informasi-umum') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm p-2 d-flex flex-row align-items-center" style="border-radius: 10px;">
                <div class="me-2 bg-info-subtle p-2 rounded">
                    <i class="bi bi-pencil-square fs-5 text-info"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-0">MBKM</h6>
                    <span class="text-dark fw-semibold" style="font-size: 0.86rem;">Informasi Umum</span>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Daftar Perusahaan MBKM -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Daftar Perusahaan MBKM</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th style="width: 30px;">No.</th>
                                <th>Nama Perusahaan</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $index => $company)
                            <tr>
                                <td class="text-muted small">{{ $index + 1 }}.</td>
                                <td class="small">{{ $company->nama_perusahaan }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm me-1" onclick="editCompany({{ $company->id }}, '{{ $company->nama_perusahaan }}')">Edit</button>
                                    <form action="{{ route('mbkm.delete.tambah.mitra', $company->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted small">Belum ada perusahaan MBKM.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- mitra & non-mitra — BERDAMPINGAN -->
    <div class="row g-3 mb-4">
        <!-- Batch I -->
        <div class="col-lg-6 pe-lg-3 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">MBKM Mitra</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th style="width: 30px;">No.</th>
                                    <th>Mahasiswa</th>
                                    <th>Perusahaan MBKM</th>
                                    <th style="width: 100px;">Selengkapnya</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Placeholder rows - replace with actual data loop -->
                                <tr>
                                    <td class="text-muted small">1.</td>
                                    <td class="small">Nama Mahasiswa 1</td>
                                    <td class="small">Perusahaan A</td>
                                    <td>
                                        <button class="btn btn-success btn-sm w-100 rounded-pill" style="font-size: 0.765rem; padding: 0.25rem 0;">Selengkapnya</button>
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batch II -->
        <div class="col-lg-6 ps-lg-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">MBKM Non-Mitra</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th style="width: 30px;">No.</th>
                                    <th>Mahasiswa</th>
                                    <th>MBKM</th>

                                    <th style="width: 100px;">Selengkapnya</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Placeholder rows - replace with actual data loop -->
                                <tr>
                                    <td class="small">1.</td>
                                    <td class="small">Nama Mahasiswa MBKM 1</td>
                                    <td>studpen</td>
                                    <td>
                                        <button class="btn btn-success btn-sm w-100 rounded-pill" style="font-size: 0.765rem; padding: 0.25rem 0;">Selengkapnya</button>
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

<!-- Modal Tambah/Edit Perusahaan MBKM -->
<div class="modal fade" id="tambahMitraModal" tabindex="-1" aria-labelledby="tambahMitraModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahMitraModalLabel">Tambah Perusahaan MBKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="companyForm" action="{{ route('mbkm.store.tambah.mitra') }}" method="POST">
                @csrf
                <input type="hidden" id="companyId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCompany(id, name) {
    document.getElementById('tambahMitraModalLabel').textContent = 'Edit Perusahaan MBKM';
    document.getElementById('companyId').value = id;
    document.getElementById('nama_perusahaan').value = name;
    document.getElementById('companyForm').action = '{{ url("/mbkm/tambah-mitra") }}/' + id;
    document.getElementById('companyForm').method = 'POST';
    // Add _method for PUT
    let methodInput = document.getElementById('companyForm').querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        document.getElementById('companyForm').appendChild(methodInput);
    }
    methodInput.value = 'PUT';
    new bootstrap.Modal(document.getElementById('tambahMitraModal')).show();
}

document.getElementById('tambahMitraModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('tambahMitraModalLabel').textContent = 'Tambah Perusahaan MBKM';
    document.getElementById('companyId').value = '';
    document.getElementById('nama_perusahaan').value = '';
    document.getElementById('companyForm').action = '{{ route("mbkm.store.tambah.mitra") }}';
    document.getElementById('companyForm').method = 'POST';
    let methodInput = document.getElementById('companyForm').querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
});
</script>

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