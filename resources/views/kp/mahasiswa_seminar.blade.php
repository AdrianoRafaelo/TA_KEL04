@extends('layouts.app')

@section('title', 'Seminar Kerja Praktik')

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Kerja Praktik</a></li>
                    <li class="breadcrumb-item active">Seminar</li>
                </ol>
            </nav>
            <h4>Seminar KP</h4>
        </div>
    </div>

    <!-- tabs -->
<div class="row mb-3">
    <div class="col-12">
        <div class="kp-tabs">
                <a href="{{ url('/kerja-praktik') }}" class="kp-tab">Informasi Umum</a>
            <a href="{{ url('/pendaftaran-kp') }}" class="kp-tab">Pendaftaran KP</a>
            <a href="{{ url('/kerja-praktik-mahasiswa-pelaksanaan')}}" class="kp-tab">Pelaksanaan KP</a>
            <a href="{{ url('/kerja-praktik-mahasiswa-seminar')}}" class="kp-tab active">Seminar KP</a>
        </div>
    </div>
</div>

    <!-- CARD INFO MAHASISWA -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="row g-4 text-center text-md-start">
                <div class="col-md-3">
                    <div class="d-block" style="color: black; font-weight: 600;">Kelompok</div>
                    <small class="text-muted text-start">
                        @if($group)
                            {{ $student->nama }} ({{ $student->nim }})
                            @if($groupMembers->count() > 0)
                                @foreach($groupMembers as $member)
                                    <br>{{ $member->nama }} ({{ $member->nim }})
                                @endforeach
                            @endif
                        @else
                            Belum ada kelompok
                        @endif
                    </small>
                </div>
                <div class="col-md-3">
                    <div class="d-block" style="color: black; font-weight: 600;">Posisi</div>
                    <small class="text-muted text-start">{{ $kpRequest->divisi ?? 'Belum ditentukan' }}</small>
                </div>
                <div class="col-md-3">
                    <div class="d-block" style="color: black; font-weight: 600;">Perusahaan</div>
                    <small class="text-muted text-start">{{ $kpRequest->company->nama_perusahaan ?? 'N/A' }}</small>
                </div>
                <div class="col-md-3">
                    <div class="d-block" style="color: black; font-weight: 600;">Pembimbing</div>
                    <small class="text-muted text-start">{{ $kpRequest->dosen->nama ?? 'Belum ditentukan' }}</small>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-12">
                    <div class="d-block" style="color: black; font-weight: 600;">Topik Khusus</div>
                    <small class="text-muted text-start">{{ $topikKhusus->topik ?? 'Belum ada topik khusus' }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: Daftar Seminar & Upload -->
        <div class="col-lg-8">
            <!-- CARD DAFTAR SEMINAR -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0 text-primary">Daftar Seminar</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="fw-bold">Total Bimbingan : {{ $totalBimbingan }}</h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li>• Selesai Bimbingan : {{ $selesaiBimbingan }}</li>
                            <li>• Menunggu Persetujuan : {{ $totalBimbingan - $selesaiBimbingan }}</li>
                        </ul>
                    </div>

                    <hr class="my-4">

                    <!-- Upload Laporan Kerja Praktik -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Laporan Kerja Praktik</h6>
                        <div id="laporanKpUploaded" style="{{ $seminar && $seminar->file_laporan_kp ? '' : 'display: none;' }}">
                            <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                <div>
                                    <a href="{{ route('kp.seminar.download', basename($seminar->file_laporan_kp ?? '')) }}"
                                       class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <button class="btn btn-sm btn-warning me-2" onclick="editFile('laporanKp')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteFile('file_laporan_kp')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="laporanKpForm" style="{{ $seminar && $seminar->file_laporan_kp ? 'display: none;' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control form-control-sm" id="laporanKp" accept=".pdf,.doc,.docx">
                                </div>
                                <button class="btn btn-primary btn-sm px-4" id="submitLaporanKp">Submit</button>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Penilaian Perusahaan -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Unggah Penilaian Perusahaan</h6>
                        <div id="penilaianPerusahaanUploaded" style="{{ $seminar && $seminar->file_penilaian_perusahaan ? '' : 'display: none;' }}">
                            <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                <div>
                                    <a href="{{ route('kp.seminar.download', basename($seminar->file_penilaian_perusahaan ?? '')) }}"
                                       class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <button class="btn btn-sm btn-warning me-2" onclick="editFile('penilaianPerusahaan')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteFile('file_penilaian_perusahaan')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="penilaianPerusahaanForm" style="{{ $seminar && $seminar->file_penilaian_perusahaan ? 'display: none;' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control form-control-sm" id="penilaianPerusahaan" accept=".pdf,.doc,.docx">
                                </div>
                                <button class="btn btn-primary btn-sm px-4" id="submitPenilaianPerusahaan">Submit</button>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Surat KP -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Unggah Surat KP</h6>
                        <div id="suratKpUploaded" style="{{ $seminar && $seminar->file_surat_kp ? '' : 'display: none;' }}">
                            <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                <div>
                                    <a href="{{ route('kp.seminar.download', basename($seminar->file_surat_kp ?? '')) }}"
                                       class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <button class="btn btn-sm btn-warning me-2" onclick="editFile('suratKp')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteFile('file_surat_kp')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="suratKpForm" style="{{ $seminar && $seminar->file_surat_kp ? 'display: none;' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control form-control-sm" id="suratKp" accept=".pdf,.doc,.docx">
                                </div>
                                <button class="btn btn-primary btn-sm px-4" id="submitSuratKp">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary btn-lg px-5 rounded-pill">Kirim</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Status, Jadwal, Nilai -->
        <div class="col-lg-4">
            <!-- Status Pendaftaran -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Status Pendaftaran</h6>
                    <i class="fas fa-sync-alt text-muted"></i>
                </div>
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold text-primary">Pendaftaran Seminar Kerja Praktik</h6>
                    @if($seminar)
                        @if($seminar->status == 'approved')
                            <span class="badge bg-success rounded-pill px-3 py-2">Disetujui</span>
                        @elseif($seminar->status == 'rejected')
                            <span class="badge bg-danger rounded-pill px-3 py-2">Ditolak</span>
                        @elseif($seminar->status == 'completed')
                            <span class="badge bg-info rounded-pill px-3 py-2">Selesai</span>
                        @else
                            <span class="badge bg-warning rounded-pill px-3 py-2">Menunggu Persetujuan</span>
                        @endif
                    @else
                        <span class="badge bg-secondary rounded-pill px-3 py-2">Belum Mendaftar</span>
                    @endif
                </div>
            </div>

            <!-- Jadwal Seminar -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Jadwal Seminar</h6>
                    <i class="fas fa-calendar-alt text-muted"></i>
                </div>
                <div class="card-body p-4 text-center">
                    <button class="btn btn-outline-primary btn-sm mb-2">Unduh Jadwal</button>
                    <button class="btn btn-outline-secondary btn-sm">Doc</button>
                </div>
            </div>

            <!-- Nilai KP -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Nilai KP</h6>
                    <i class="fas fa-chart-line text-muted"></i>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="bg-success text-white rounded-pill d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 1.5rem; font-weight: bold;">
                        80
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12) !important;
        transform: translateY(-6px);
    }

    .badge {
        font-size: 0.85rem;
        font-weight: 600;
    }

    .form-control-sm {
        font-size: 0.85rem;
        padding: 0.375rem 0.75rem;
    }

    .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
    }

    .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 2rem;
    }

    /* Hover untuk tombol */
    .btn-primary:hover {
        background-color: #2c3e50 !important;
        border-color: #2c3e50 !important;
    }

    .btn-outline-primary:hover {
        background-color: #5a67d8 !important;
        color: white !important;
    }

    /* Input file placeholder */
    .form-control::file-selector-button {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        margin-right: 0.5rem;
        border-radius: 0.375rem;
    }

    /* Nilai KP lingkaran hijau */
    .bg-success {
        background: linear-gradient(135deg, #1cc88a, #16a085) !important;
        box-shadow: 0 8px 20px rgba(28, 200, 138, 0.3);
    }

    @media (max-width: 992px) {
        .text-center.text-md-start {
            text-align: center !important;
        }
        .btn-lg {
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const kpRequestId = '{{ $kpRequest->id ?? "" }}';

function editFile(fileType) {
    Swal.fire({
        title: 'Edit File',
        text: 'Apakah Anda yakin ingin mengedit file ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Edit',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(fileType + 'Uploaded').style.display = 'none';
            document.getElementById(fileType + 'Form').style.display = 'block';
        }
    });
}

function deleteFile(fieldName) {
    Swal.fire({
        title: 'Hapus File',
        text: 'Apakah Anda yakin ingin menghapus file ini? File akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("kp.seminar.delete-file") }}', {
                method: 'DELETE',
                body: JSON.stringify({
                    kp_request_id: kpRequestId,
                    field: fieldName
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', 'File berhasil dihapus.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', 'Gagal menghapus file: ' + (data.message || 'Terjadi kesalahan'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus file.', 'error');
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {

// Handle Laporan KP upload
document.getElementById('submitLaporanKp')?.addEventListener('click', function() {
    uploadFile('laporanKp', 'file_laporan_kp', 'submitLaporanKp');
});

// Handle Penilaian Perusahaan upload
document.getElementById('submitPenilaianPerusahaan')?.addEventListener('click', function() {
    uploadFile('penilaianPerusahaan', 'file_penilaian_perusahaan', 'submitPenilaianPerusahaan');
});

// Handle Surat KP upload
document.getElementById('submitSuratKp')?.addEventListener('click', function() {
    uploadFile('suratKp', 'file_surat_kp', 'submitSuratKp');
});

function uploadFile(inputId, fieldName, buttonId) {
    if (!kpRequestId) {
        Swal.fire('Error!', 'Data KP tidak ditemukan. Silakan refresh halaman.', 'error');
        return;
    }

    const fileInput = document.getElementById(inputId);
    const file = fileInput.files[0];

    if (!file) {
        Swal.fire('Peringatan!', 'Pilih file terlebih dahulu', 'warning');
        return;
    }

    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        Swal.fire('Error!', 'Ukuran file maksimal 5MB', 'error');
        return;
    }

    // Validate file type
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!allowedTypes.includes(file.type)) {
        Swal.fire('Error!', 'Format file harus PDF, DOC, atau DOCX', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('kp_request_id', kpRequestId);
    formData.append(fieldName, file);

    // Show loading
    const button = document.getElementById(buttonId);
    const originalText = button.textContent;
    button.textContent = 'Mengunggah...';
    button.disabled = true;

    fetch('{{ route("kp.seminar.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Berhasil!', 'File berhasil diunggah.', 'success').then(() => {
                location.reload(); // Reload to show updated status
            });
        } else {
            Swal.fire('Gagal!', 'Gagal mengunggah file: ' + (data.message || 'Terjadi kesalahan'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan saat mengunggah file.', 'error');
    })
    .finally(() => {
        button.textContent = originalText;
        button.disabled = false;
    });
}
});
</script>
@endsection