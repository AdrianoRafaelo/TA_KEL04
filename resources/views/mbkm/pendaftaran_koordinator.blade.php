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
            <div class="cardd border-0 shadow-sm p-2 d-flex flex-row align-items-center" style="border-radius: 10px;">
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

    <!-- Tambah Non-Mitra MBKM -->
        <div class="col-md-3">
        <button type="button" class="btn p-0 text-decoration-none w-100" data-bs-toggle="modal" data-bs-target="#tambahNonMitraModal">
            <div class="cardd border-0 shadow-sm p-2 d-flex flex-row align-items-center" style="border-radius: 10px;">
                <div class="me-2 bg-info-subtle p-2 rounded">
                    <i class="bi bi-person-plus fs-5 text-info"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-0">MBKM Non-Mitra</h6>
                    <span class="text-dark fw-semibold" style="font-size: 0.86rem;">Tambah</span>
                </div>
            </div>
        </button>
    </div>

    <!-- Informasi Umum -->
        <!-- Informasi umum -->
    <div class="col-md-3">
        <button type="button" class="btn p-0 text-decoration-none w-100">
            <div class="cardd border-0 shadow-sm p-2 d-flex flex-row align-items-center info-card" style="border-radius: 10px;">
                <div class="me-2 bg-success-subtle p-2 rounded">
                    <i class="bi bi-people fs-5 text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-0">MBKM</h6>
                    <span class="text-dark fw-semibold" style="font-size: 0.86rem;">informasi umum</span>
                </div>
            </div>
        </button>
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

<!-- Daftar Program MBKM Non-Mitra -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Daftar Program MBKM Non-Mitra</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th style="width: 30px;">No.</th>
                                <th>Nama Program</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\MbkmNonMitraProgram::where('active', 1)->get() as $index => $program)
                            <tr>
                                <td class="text-muted small">{{ $index + 1 }}.</td>
                                <td class="small">{{ $program->nama_program }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm me-1" onclick="editProgram({{ $program->id }}, '{{ $program->nama_program }}')">Edit</button>
                                    <form action="{{ route('mbkm.delete.program-nonmitra', $program->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus program ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted small">Belum ada program MBKM Non-Mitra.</td>
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
                                    <th>Status</th>
                                    <th style="width: 100px;">Selengkapnya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $index => $registration)
                                <tr>
                                    <td class="text-muted small">{{ $index + 1 }}.</td>
                                    <td class="small">{{ $registration->nama }}</td>
                                    <td class="small">{{ $registration->mitra->nama_perusahaan ?? 'N/A' }}</td>
                                    <td>
                                        @if($registration->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($registration->status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm w-100 rounded-pill" style="font-size: 0.765rem; padding: 0.25rem 0;" onclick="showDetailModal({{ $registration->id }}, '{{ $registration->nama }}', '{{ $registration->nim }}', '{{ $registration->semester }}', '{{ $registration->ipk }}', '{{ $registration->matakuliah_ekuivalensi }}', '{{ $registration->mitra->nama_perusahaan ?? 'N/A' }}', '{{ $registration->file_portofolio }}', '{{ $registration->file_cv }}', '{{ $registration->status }}', {{ $registration->dosen_id ?? 'null' }})">Selengkapnya</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted small">Belum ada pendaftar MBKM Mitra.</td>
                                </tr>
                                @endforelse
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
                                    <th>Status</th>
                                    <th style="width: 100px;">Selengkapnya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nonmitraRegistrations as $index => $registration)
                                <tr>
                                    <td class="text-muted small">{{ $index + 1 }}.</td>
                                    <td class="small">{{ $registration->mahasiswa->nama ?? 'N/A' }}</td>
                                    <td class="small">{{ $registration->program->nama_program ?? 'N/A' }}</td>
                                    <td>
                                        @if($registration->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($registration->status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm w-100 rounded-pill" style="font-size: 0.765rem; padding: 0.25rem 0;" onclick="showDetailModalNonmitra({{ $registration->id }}, '{{ $registration->mahasiswa->nama ?? 'N/A' }}', '{{ $registration->program->nama_program ?? 'N/A' }}', '{{ $registration->posisi_mbkm }}', '{{ $registration->masa_mbkm }}', '{{ $registration->matakuliah_ekuivalensi }}', '{{ $registration->file_loa }}', '{{ $registration->file_proposal }}', '{{ $registration->status }}', {{ $registration->dosen_id ?? 'null' }})">Selengkapnya</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted small">Belum ada pendaftar MBKM Non-Mitra.</td>
                                </tr>
                                @endforelse
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

<!-- Modal Tambah MBKM Non-Mitra -->
<div class="modal fade" id="tambahNonMitraModal" tabindex="-1" aria-labelledby="tambahNonMitraModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahNonMitraModalLabel">Tambah Program MBKM Non-Mitra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="programForm" action="{{ route('mbkm.store.program-nonmitra') }}" method="POST">
                @csrf
                <input type="hidden" id="programId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_program" class="form-label">Nama Program MBKM</label>
                        <input type="text" class="form-control" id="nama_program" name="nama_program"
                               placeholder="Contoh: STUPEN, IISMA, PMM" required>
                        <div class="form-text">Masukkan nama program MBKM Non-Mitra seperti STUPEN, IISMA, PMM, dll.</div>
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

function editProgram(id, name) {
    document.getElementById('tambahNonMitraModalLabel').textContent = 'Edit Program MBKM Non-Mitra';
    document.getElementById('programId').value = id;
    document.getElementById('nama_program').value = name;
    document.getElementById('programForm').action = '{{ url("/mbkm/program-nonmitra") }}/' + id;
    document.getElementById('programForm').method = 'POST';
    // Add _method for PUT
    let methodInput = document.getElementById('programForm').querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        document.getElementById('programForm').appendChild(methodInput);
    }
    methodInput.value = 'PUT';
    new bootstrap.Modal(document.getElementById('tambahNonMitraModal')).show();
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

document.getElementById('tambahNonMitraModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('tambahNonMitraModalLabel').textContent = 'Tambah Program MBKM Non-Mitra';
    document.getElementById('programId').value = '';
    document.getElementById('nama_program').value = '';
    document.getElementById('programForm').action = '{{ route("mbkm.store.program-nonmitra") }}';
    document.getElementById('programForm').method = 'POST';
    let methodInput = document.getElementById('programForm').querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
});

function showDetailModal(id, nama, nim, semester, ipk, matkul, perusahaan, portofolio, cv, status, dosenId = null) {
    document.getElementById('detailNama').value = nama;
    document.getElementById('detailNim').value = nim;
    document.getElementById('detailSemester').value = semester;
    document.getElementById('detailIpk').value = ipk;
    document.getElementById('detailMatkul').value = matkul;
    document.getElementById('detailPerusahaan').value = perusahaan;
    document.getElementById('detailPortofolio').href = '/storage/' + portofolio;
    document.getElementById('detailCv').href = '/storage/' + cv;
    document.getElementById('detailDosen').value = dosenId || '';
    document.getElementById('approveForm').action = '{{ url("/mbkm/pendaftaran/approve") }}/' + id;
    document.getElementById('rejectForm').action = '{{ url("/mbkm/pendaftaran/reject") }}/' + id;
    document.getElementById('editBtn').href = '{{ url("/mbkm/pendaftaran/edit") }}/' + id;
    // Hide approve button if already rejected
    if (status === 'rejected') {
        document.getElementById('approveForm').style.display = 'none';
    } else {
        document.getElementById('approveForm').style.display = 'inline';
    }
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}


function showDetailModalNonmitra(id, nama, perusahaan, posisi, masa, matkul, loa, proposal, status, dosenId = null) {
    document.getElementById('detailNonmitraNama').value = nama;
    document.getElementById('detailNonmitraPerusahaan').value = perusahaan;
    document.getElementById('detailNonmitraPosisi').value = posisi;
    document.getElementById('detailNonmitraMasa').value = masa;
    document.getElementById('detailNonmitraMatkul').value = matkul;
    document.getElementById('detailNonmitraLoa').href = '/storage/' + loa;
    document.getElementById('detailNonmitraProposal').href = '/storage/' + proposal;
    document.getElementById('detailNonmitraDosen').value = dosenId || '';
    document.getElementById('approveFormNonmitra').action = '{{ url("/mbkm/pendaftaran-nonmitra/approve") }}/' + id;
    document.getElementById('rejectFormNonmitra').action = '{{ url("/mbkm/pendaftaran-nonmitra/reject") }}/' + id;
    document.getElementById('editBtnNonmitra').href = '{{ url("/mbkm/pendaftaran-nonmitra/edit") }}/' + id;
    // Hide approve button if already rejected
    if (status === 'rejected') {
        document.getElementById('approveFormNonmitra').style.display = 'none';
    } else {
        document.getElementById('approveFormNonmitra').style.display = 'inline';
    }
    new bootstrap.Modal(document.getElementById('detailModalNonmitra')).show();
}

function approveRegistration(formId, modalId) {
    // Sembunyikan modal dulu
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    modal.hide();

    // Submit form setelah modal benar-benar tertutup
    $('#' + modalId).on('hidden.bs.modal', function () {
        document.getElementById(formId).submit();
    });
}

function rejectRegistration(formId, modalId) {
    if (confirm('Yakin ingin MENOLAK pendaftaran ini?')) {
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        modal.hide();

        // Submit form setelah modal benar-benar tertutup
        $('#' + modalId).on('hidden.bs.modal', function () {
            document.getElementById(formId).submit();
        });
    }
}

</script>

<!-- Modal Detail Pendaftaran MBKM -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Pendaftaran MBKM</h5>
                <button type="button" class="btn-close" ="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="detailNama" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" id="detailNim" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <input type="text" class="form-control" id="detailSemester" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">IPK</label>
                            <input type="text" class="form-control" id="detailIpk" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Matakuliah Ekuivalensi</label>
                            <textarea class="form-control" id="detailMatkul" rows="3" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Perusahaan MBKM</label>
                            <input type="text" class="form-control" id="detailPerusahaan" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Portofolio</label>
                            <a id="detailPortofolio" href="#" target="_blank" class="btn btn-link">Lihat File</a>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CV</label>
                            <a id="detailCv" href="#" target="_blank" class="btn btn-link">Lihat File</a>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dosen Pembimbing</label>
                            <select class="form-select" id="detailDosen" name="dosen_id" form="approveForm">
                                <option value="">Pilih Dosen</option>
                                @foreach(\App\Models\FtiData::where('role', 'lecturer')->get() as $lecturer)
                                    <option value="{{ $lecturer->id }}">{{ $lecturer->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form method="POST" action="" id="approveForm" style="display:inline;">
                    @csrf
                    <button type="button" class="btn btn-success"
                            onclick="approveRegistration('approveForm', 'detailModal')">
                        Terima
                    </button>
                </form>

                <form method="POST" action="" id="rejectForm" style="display:inline;">
                    @csrf
                    <button type="button" class="btn btn-danger"
                            onclick="rejectRegistration('rejectForm', 'detailModal')">
                        Tolak
                    </button>
                </form>
                <a id="editBtn" href="#" class="btn btn-warning">Edit</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pendaftaran MBKM Non-Mitra -->
<div class="modal fade" id="detailModalNonmitra" tabindex="-1" aria-labelledby="detailModalNonmitraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalNonmitraLabel">Detail Pendaftaran MBKM Non-Mitra</h5>
                <button type="button" class="btn-close" ="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Mahasiswa</label>
                            <input type="text" class="form-control" id="detailNonmitraNama" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program MBKM</label>
                            <input type="text" class="form-control" id="detailNonmitraPerusahaan" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Posisi MBKM</label>
                            <input type="text" class="form-control" id="detailNonmitraPosisi" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Masa MBKM</label>
                            <input type="text" class="form-control" id="detailNonmitraMasa" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Matakuliah Ekuivalensi</label>
                            <input type="text" class="form-control" id="detailNonmitraMatkul" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">LOA</label>
                            <a id="detailNonmitraLoa" href="#" target="_blank" class="btn btn-link">Lihat File</a>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Proposal</label>
                            <a id="detailNonmitraProposal" href="#" target="_blank" class="btn btn-link">Lihat File</a>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dosen Pembimbing</label>
                            <select class="form-select" id="detailNonmitraDosen" name="dosen_id" form="approveFormNonmitra">
                                <option value="">Pilih Dosen</option>
                                @foreach(\App\Models\FtiData::where('role', 'lecturer')->get() as $lecturer)
                                    <option value="{{ $lecturer->id }}">{{ $lecturer->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form method="POST" action="" id="approveFormNonmitra" style="display:inline;">
                    @csrf
                    <button type="button" class="btn btn-success"
                            onclick="approveRegistration('approveFormNonmitra', 'detailModalNonmitra')">
                        Terima
                    </button>
                </form>

                <form method="POST" action="" id="rejectFormNonmitra" style="display:inline;">
                    @csrf
                    <button type="button" class="btn btn-danger"
                            onclick="rejectRegistration('rejectFormNonmitra', 'detailModalNonmitra')">
                        Tolak
                    </button>
                </form>
                <a id="editBtnNonmitra" href="#" class="btn btn-warning">Edit</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

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