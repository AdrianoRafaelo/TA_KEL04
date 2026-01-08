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
            <a href="{{ url('/mbkm/dosen-konversi-matkul') }}" class="kp-tab active">Konversi Matakuliah</a>
            <a href="{{ url('/mbkm/dosen-pelaksanaan')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/dosen-seminar')}}" class="kp-tab">Seminar MBKM</a>
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

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header text-white fw-semibold fs-5"
             style="background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%); padding: 1rem 1.5rem;">
            Matakuliah Diampu
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="8%"  class="text-center">No.</th>
                            <th width="25%">Matakuliah</th>
                            <th width="40%">CPMK</th>
                            <th width="27%" class="text-center">Pendaftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $index => $course)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}.</td>
                            <td class="fw-semibold">{{ $course->nama_mk }}</td>
                            <td>
                                <span class="text-primary fw-medium update-cpmk-btn" style="cursor: pointer;" data-course-id="{{ $course->id }}" data-course-name="{{ $course->nama_mk }}" data-cpmk="{{ $course->cpmk ? json_encode($course->cpmk) : '[]' }}">
                                    + Perbarui CPMK
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-success btn-sm rounded-pill px-4 view-pendaftar-btn"
                                        style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
                                               border: none; font-weight: 500;"
                                        data-course-id="{{ $course->id }}"
                                        data-course-name="{{ $course->nama_mk }}">
                                    Lihat ({{ isset($pendaftarData[$course->id]) ? $pendaftarData[$course->id]->count() : 0 }})
                                </button>
                                @if(isset($pendaftarData[$course->id]) && $pendaftarData[$course->id]->where('status', 'approved')->count() > 0)
                                    <br><small class="text-success fw-semibold mt-1 d-block">
                                        <i class="bi bi-check-circle-fill"></i> Sudah Diterima
                                    </small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="mdi mdi-notebook-outline" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Belum ada mata kuliah yang diampu</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for updating CPMK -->
<div class="modal fade" id="cpmkModal" tabindex="-1" aria-labelledby="cpmkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cpmkModalLabel">Perbarui CPMK - <span id="modalCourseName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cpmkForm">
                <div class="modal-body">
                    <div id="cpmkContainer">
                        <!-- CPMK inputs will be added here -->
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="addCpmkBtn">Tambah CPMK</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for viewing students -->
<div class="modal fade" id="pendaftarModal" tabindex="-1" aria-labelledby="pendaftarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pendaftarModalLabel">Pendaftar Konversi MK - <span id="modalCourseNamePendaftar"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pendaftarContainer">
                    <!-- Student list will be added here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle CPMK update button
    document.querySelectorAll('.update-cpmk-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            const courseName = this.getAttribute('data-course-name');
            const cpmkData = JSON.parse(this.getAttribute('data-cpmk') || '[]');

            // Set modal title
            document.getElementById('modalCourseName').textContent = courseName;

            // Clear existing CPMK inputs
            const container = document.getElementById('cpmkContainer');
            container.innerHTML = '';

            // Add existing CPMK inputs
            if (cpmkData.length > 0) {
                cpmkData.forEach((cpmk, index) => {
                    addCpmkInput(cpmk);
                });
            } else {
                addCpmkInput('');
            }

            // Set form action
            document.getElementById('cpmkForm').setAttribute('data-course-id', courseId);

            // Show modal
            new bootstrap.Modal(document.getElementById('cpmkModal')).show();
        });
    });

    // Handle view pendaftar button
    document.querySelectorAll('.view-pendaftar-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            const courseName = this.getAttribute('data-course-name');

            // Set modal title
            document.getElementById('modalCourseNamePendaftar').textContent = courseName;

            // Get pendaftar data from server
            fetch(`/mbkm/dosen-konversi-matkul/${courseId}/pendaftar`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('pendaftarContainer');
                    if (data.length > 0) {
                        let html = '<div class="list-group">';
                        data.forEach((pendaftar, index) => {
                            html += `
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <div>
                                                <h6 class="card-title mb-1" style="color: #000;">${pendaftar.mahasiswa.nama}</h6>
                                                <small class="text-muted">NIM: ${pendaftar.mahasiswa.nim || 'N/A'}</small>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Deskripsi Kegiatan:</strong>
                                            <p class="mt-1">${pendaftar.deskripsi_kegiatan}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Alokasi Waktu:</strong> ${pendaftar.alokasi_waktu} jam
                                        </div>
                                        ${pendaftar.status === 'pending' ? `
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success btn-sm" onclick="approveKonversi(${pendaftar.id})">Terima</button>
                                            <button class="btn btn-danger btn-sm" onclick="rejectKonversi(${pendaftar.id})">Tolak</button>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<p class="text-muted text-center">Belum ada pendaftar untuk mata kuliah ini.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('pendaftarContainer').innerHTML = '<p class="text-danger">Terjadi kesalahan saat memuat data.</p>';
                });

            // Show modal
            new bootstrap.Modal(document.getElementById('pendaftarModal')).show();
        });
    });

    // Handle add CPMK button
    document.getElementById('addCpmkBtn').addEventListener('click', function() {
        addCpmkInput('');
    });

    // Handle CPMK form submission
    document.getElementById('cpmkForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const courseId = this.getAttribute('data-course-id');
        const cpmkInputs = document.querySelectorAll('#cpmkContainer input[name="cpmk[]"]');
        const cpmkData = Array.from(cpmkInputs).map(input => input.value).filter(val => val.trim() !== '');

        fetch(`/mbkm/dosen-konversi-matkul/${courseId}/cpmk`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cpmk: cpmkData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('CPMK berhasil diperbarui!');
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data.');
        });
    });

    // Handle remove CPMK
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-cpmk')) {
            e.target.closest('.cpmk-item').remove();
        }
    });
});

function loadCpmkForPendaftar(pendaftarId, courseId) {
    fetch(`/mbkm/dosen-konversi-matkul/${courseId}/cpmk-data`)
        .then(response => response.json())
        .then(cpmkData => {
            const container = document.getElementById(`cpmk-display-${pendaftarId}`);
            if (cpmkData && cpmkData.length > 0) {
                let html = '<ul class="list-unstyled">';
                cpmkData.forEach((cpmk, index) => {
                    html += `<li class="mb-1"><small>${index + 1}. ${cpmk}</small></li>`;
                });
                html += '</ul>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<small class="text-muted">Belum ada CPMK yang ditentukan</small>';
            }
        })
        .catch(error => {
            console.error('Error loading CPMK:', error);
            document.getElementById(`cpmk-display-${pendaftarId}`).innerHTML = '<small class="text-danger">Error loading CPMK</small>';
        });
}

function approveKonversi(id) {
    if (confirm('Apakah Anda yakin ingin menyetujui konversi MK ini?')) {
        fetch(`/mbkm/konversi-mk/approve/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Konversi MK berhasil disetujui!');
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan.');
        });
    }
}

function rejectKonversi(id) {
    if (confirm('Apakah Anda yakin ingin menolak konversi MK ini?')) {
        fetch(`/mbkm/konversi-mk/reject/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Konversi MK berhasil ditolak!');
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan.');
        });
    }
}

function addCpmkInput(value) {
    const container = document.getElementById('cpmkContainer');
    const index = container.children.length + 1;

    const div = document.createElement('div');
    div.className = 'input-group mb-2 cpmk-item';
    div.innerHTML = `
        <input type="text" name="cpmk[]" class="form-control" value="${value}" placeholder="Masukkan CPMK ${index}">
        <button type="button" class="btn btn-danger remove-cpmk">Hapus</button>
    `;

    container.appendChild(div);
}
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
        .btn-success {
        transition: all 0.25s ease;
    }
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4) !important;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .info-card {
        background-color: white;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

</style>