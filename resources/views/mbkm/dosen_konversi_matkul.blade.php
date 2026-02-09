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
<div id="pendaftarModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="bi bi-people-fill me-2"></i>
                Pendaftar Konversi MK - <span id="modalCourseNamePendaftar"></span>
            </div>
            <button id="closePendaftarModal" class="modal-close-btn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="pendaftarContainer">
                <!-- Student list will be added here -->
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
                        let html = '';
                        data.forEach((pendaftar, index) => {
                            html += `
                                <div class="pendaftar-card">
                                    <div class="pendaftar-info-card">
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <i class="bi bi-person-fill info-icon"></i>
                                                <div class="info-content">
                                                    <label>Mahasiswa</label>
                                                    <span>${pendaftar.mahasiswa.nama}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="bi bi-card-text info-icon"></i>
                                                <div class="info-content">
                                                    <label>NIM</label>
                                                    <span>${pendaftar.mahasiswa.nim || 'N/A'}</span>
                                                </div>
                                            </div>
                                            <div class="info-item full-width">
                                                <i class="bi bi-file-text-fill info-icon"></i>
                                                <div class="info-content">
                                                    <label>Deskripsi Kegiatan</label>
                                                    <span>${pendaftar.deskripsi_kegiatan}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="bi bi-clock-fill info-icon"></i>
                                                <div class="info-content">
                                                    <label>Alokasi Waktu</label>
                                                    <span>${pendaftar.alokasi_waktu} jam</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="bi bi-info-circle-fill info-icon"></i>
                                                <div class="info-content">
                                                    <label>Status</label>
                                                    <span class="${pendaftar.status === 'approved' ? 'text-success' : pendaftar.status === 'rejected' ? 'text-danger' : 'text-warning'}">
                                                        ${pendaftar.status === 'approved' ? 'Diterima' : pendaftar.status === 'rejected' ? 'Ditolak' : 'Menunggu'}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ${pendaftar.file_kesesuaian ? `
                                    <div class="file-section">
                                        <div class="file-item">
                                            <div class="file-label">
                                                <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                                                Form Kesesuaian Aktivitas MBKM dengan CPMK
                                            </div>
                                            <a href="/storage/${pendaftar.file_kesesuaian}" target="_blank" class="btn-download">
                                                <i class="bi bi-download me-1"></i>Unduh
                                            </a>
                                        </div>
                                    </div>
                                    ` : ''}
                                    ${pendaftar.status === 'pending' ? `
                                    <div class="action-buttons">
                                        <button class="btn-approve" onclick="approveKonversi(${pendaftar.id})">
                                            <i class="bi bi-check-circle me-1"></i>Terima
                                        </button>
                                        <button class="btn-reject" onclick="rejectKonversi(${pendaftar.id})">
                                            <i class="bi bi-x-circle me-1"></i>Tolak
                                        </button>
                                    </div>
                                    ` : ''}
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div class="no-data"><i class="bi bi-info-circle"></i><p>Belum ada pendaftar untuk mata kuliah ini.</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('pendaftarContainer').innerHTML = '<div class="error-message"><i class="bi bi-exclamation-triangle"></i><p>Terjadi kesalahan saat memuat data.</p></div>';
                });

            // Show modal
            document.getElementById('pendaftarModal').style.display = 'flex';
        });
    });

    // Close modal functionality
    document.getElementById('closePendaftarModal').addEventListener('click', function() {
        document.getElementById('pendaftarModal').style.display = 'none';
    });

    document.getElementById('pendaftarModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
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

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }

    .modal-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        max-width: 800px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .modal-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 1.5rem !important;
        border-bottom: 1px solid #e9ecef !important;
        background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%) !important;
        color: white !important;
        border-radius: 12px 12px 0 0 !important;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .modal-close-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .pendaftar-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }

    .pendaftar-info-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-icon {
        color: #1E3A8A;
        font-size: 1.25rem;
        min-width: 24px;
    }

    .info-content {
        flex: 1;
    }

    .info-content label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-content span {
        font-size: 0.9rem;
        color: #212529;
        font-weight: 500;
    }

    .file-section {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .file-label {
        display: flex;
        align-items: center;
        font-weight: 500;
        color: #495057;
    }

    .btn-download {
        background: #1E3A8A;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-download:hover {
        background: #152c5f;
        color: white;
        text-decoration: none;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .btn-approve, .btn-reject {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.875rem;
    }

    .btn-approve {
        background: #28a745;
        color: white;
    }

    .btn-approve:hover {
        background: #218838;
        transform: translateY(-1px);
    }

    .btn-reject {
        background: #dc3545;
        color: white;
    }

    .btn-reject:hover {
        background: #c82333;
        transform: translateY(-1px);
    }

    .no-data, .error-message {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .no-data i, .error-message i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .error-message {
        color: #dc3545;
    }

    </style>