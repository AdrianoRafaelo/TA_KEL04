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

    <!-- INFO MAHASISWA FULL WIDTH -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="car p-4">
            <div class="row g-4">
                <!-- Info Kiri -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">

                        <div>
                            <small class="text-muted">Nama/NIM</small>
                            <h6 class="fw-bold mb-0">{{ $student->nama ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ $student->fakultas ?? 'Universitas' }} / {{ $student->nim ?? $user['username'] }}</small>
                            @if(isset($groupMembers) && $groupMembers->count() > 0)
                                <small class="text-muted d-block mt-1">
                                    Anggota Kelompok: {{ $groupMembers->pluck('nama')->join(', ') }}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center">

                        <div>
                            <small class="text-muted">Posisi (Divisi)</small>
                            <h6 class="fw-bold mb-0">{{ $kpRequest->divisi ?? 'Belum ditentukan' }}</h6>
                        </div>
                    </div>
                </div>

                <!-- Info Kanan -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">

                        <div>
                            <small class="text-muted">Perusahaan</small>
                            <h6 class="fw-bold mb-0">{{ $kpRequest->company->nama_perusahaan ?? 'Belum ditentukan' }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">

                        <div>
                            <small class="text-muted">Pembimbing</small>
                            <h6 class="fw-bold mb-0">{{ $kpRequest->dosen->nama ?? 'Belum ditentukan' }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">

                        <div>
                            <small class="text-muted">Topik Khusus</small>
                            <h6 class="fw-bold mb-0">{{ $topikKhusus->topik ?? 'Belum ada topik khusus' }}</h6>
                        </div>
                    </div>
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
                <div class="cardd body p-4">
                    <div class="mb-3">
                        <h6 class="fw-bold">Total Bimbingan Disetujui : {{ $totalBimbingan }} / {{ $minTotal }}
                            @if($totalBimbingan >= $minTotal)
                                <span class="badge bg-success ms-2">✓ Sudah Memenuhi</span>
                            @else
                                <span class="badge bg-warning ms-2">Belum Memenuhi</span>
                            @endif
                        </h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li>• Sebelum KP : {{ $bimbinganSebelum }} / {{ $minSebelum }}
                                @if($bimbinganSebelum >= $minSebelum)
                                    <span class="badge bg-success ms-1">✓</span>
                                @else
                                    <span class="badge bg-warning ms-1">✗</span>
                                @endif
                            </li>
                            <li>• Sewaktu KP : {{ $bimbinganSewaktu }} / {{ $minSewaktu }}
                                @if($bimbinganSewaktu >= $minSewaktu)
                                    <span class="badge bg-success ms-1">✓</span>
                                @else
                                    <span class="badge bg-warning ms-1">✗</span>
                                @endif
                            </li>
                            <li>• Sesudah KP : {{ $bimbinganSesudah }} / {{ $minSesudah }}
                                @if($bimbinganSesudah >= $minSesudah)
                                    <span class="badge bg-success ms-1">✓</span>
                                @else
                                    <span class="badge bg-warning ms-1">✗</span>
                                @endif
                            </li>
                            <li>• Total Bimbingan Diajukan : {{ $totalBimbinganAll }}</li>
                            <li>• Bimbingan Disetujui : {{ $selesaiBimbingan }}</li>
                            <li>• Menunggu Persetujuan : {{ $totalBimbinganAll - $selesaiBimbingan }}</li>
                        </ul>
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <!-- Row 1 -->
                        <div class="col-md-4">
                            <!-- Upload Laporan Kerja Praktik -->
                            <div class="upload-section">
                                <h6 class="fw-bold mb-3">Laporan Kerja Praktik</h6>
                                <div id="laporanKpUploaded" style="{{ $seminar && $seminar->file_laporan_kp ? '' : 'display: none;' }}">
                                    <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                        <div>
                                            @if($seminar && $seminar->file_laporan_kp)
                                                <a href="{{ route('kp.seminar.download', basename($seminar->file_laporan_kp)) }}"
                                                   class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            @endif
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
                                    <div class="upload-area" id="laporanKpDropArea">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p>Drag & drop file Laporan KP here or <span class="upload-link">browse</span></p>
                                            <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB</small>
                                            <input type="file" id="laporanKp" accept=".pdf,.doc,.docx" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button class="btn-submit" id="submitLaporanKp">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Upload Penilaian Perusahaan -->
                            <div class="upload-section">
                                <h6 class="fw-bold mb-3">Unggah Penilaian Perusahaan</h6>
                                <div id="penilaianPerusahaanUploaded" style="{{ $seminar && $seminar->file_penilaian_perusahaan ? '' : 'display: none;' }}">
                                    <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                        <div>
                                            @if($seminar && $seminar->file_penilaian_perusahaan)
                                                <a href="{{ route('kp.seminar.download', basename($seminar->file_penilaian_perusahaan)) }}"
                                                   class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            @endif
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
                                    <div class="upload-area" id="penilaianPerusahaanDropArea">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p>Drag & drop file Penilaian Perusahaan here or <span class="upload-link">browse</span></p>
                                            <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB</small>
                                            <input type="file" id="penilaianPerusahaan" accept=".pdf,.doc,.docx" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button class="btn-submit" id="submitPenilaianPerusahaan">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Upload Surat KP -->
                            <div class="upload-section">
                                <h6 class="fw-bold mb-3">Unggah Surat KP</h6>
                                <div id="suratKpUploaded" style="{{ $seminar && $seminar->file_surat_kp ? '' : 'display: none;' }}">
                                    <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                        <div>
                                            @if($seminar && $seminar->file_surat_kp)
                                                <a href="{{ route('kp.seminar.download', basename($seminar->file_surat_kp)) }}"
                                                   class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            @endif
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
                                    <div class="upload-area" id="suratKpDropArea">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p>Drag & drop file Surat KP here or <span class="upload-link">browse</span></p>
                                            <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB</small>
                                            <input type="file" id="suratKp" accept=".pdf,.doc,.docx" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button class="btn-submit" id="submitSuratKp">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="col-md-4">
                            <!-- Upload KRS Anggota Kelompok -->
                            <div class="upload-section">
                                <h6 class="fw-bold mb-3">KRS Anggota Kelompok</h6>
                                <div id="krsAnggotaUploaded" style="{{ $seminar && $seminar->file_krs_anggota ? '' : 'display: none;' }}">
                                    <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                        <div>
                                            @if($seminar && $seminar->file_krs_anggota)
                                                <a href="{{ route('kp.seminar.download', basename($seminar->file_krs_anggota)) }}"
                                                   class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            @endif
                                            <button class="btn btn-sm btn-warning me-2" onclick="editFile('krsAnggota')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteFile('file_krs_anggota')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="krsAnggotaForm" style="{{ $seminar && $seminar->file_krs_anggota ? 'display: none;' : '' }}">
                                    <div class="upload-area" id="krsAnggotaDropArea">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p>Drag & drop file KRS Anggota here or <span class="upload-link">browse</span></p>
                                            <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB</small>
                                            <input type="file" id="krsAnggota" accept=".pdf,.doc,.docx" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button class="btn-submit" id="submitKrsAnggota">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Upload Surat Persetujuan Layak Seminar -->
                            <div class="upload-section">
                                <h6 class="fw-bold mb-3">Surat Persetujuan Layak Seminar Kerja Praktik</h6>
                                <div id="suratPersetujuanUploaded" style="{{ $seminar && $seminar->file_surat_persetujuan ? '' : 'display: none;' }}">
                                    <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                        <div>
                                            @if($seminar && $seminar->file_surat_persetujuan)
                                                <a href="{{ route('kp.seminar.download', basename($seminar->file_surat_persetujuan)) }}"
                                                   class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            @endif
                                            <button class="btn btn-sm btn-warning me-2" onclick="editFile('suratPersetujuan')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteFile('file_surat_persetujuan')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="suratPersetujuanForm" style="{{ $seminar && $seminar->file_surat_persetujuan ? 'display: none;' : '' }}">
                                    <div class="upload-area" id="suratPersetujuanDropArea">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p>Drag & drop file Surat Persetujuan here or <span class="upload-link">browse</span></p>
                                            <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB</small>
                                            <input type="file" id="suratPersetujuan" accept=".pdf,.doc,.docx" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button class="btn-submit" id="submitSuratPersetujuan">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Upload Lembar Konfirmasi Kerja Praktik -->
                            <div class="upload-section">
                                <h6 class="fw-bold mb-3">Lembar Konfirmasi Kerja Praktik</h6>
                                <div id="lembarKonfirmasiUploaded" style="{{ $seminar && $seminar->file_lembar_konfirmasi ? '' : 'display: none;' }}">
                                    <div class="alert alert-success py-2 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-check-circle me-2"></i> File sudah diunggah</span>
                                        <div>
                                            @if($seminar && $seminar->file_lembar_konfirmasi)
                                                <a href="{{ route('kp.seminar.download', basename($seminar->file_lembar_konfirmasi)) }}"
                                                   class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            @endif
                                            <button class="btn btn-sm btn-warning me-2" onclick="editFile('lembarKonfirmasi')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteFile('file_lembar_konfirmasi')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="lembarKonfirmasiForm" style="{{ $seminar && $seminar->file_lembar_konfirmasi ? 'display: none;' : '' }}">
                                    <div class="upload-area" id="lembarKonfirmasiDropArea">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p>Drag & drop file Lembar Konfirmasi here or <span class="upload-link">browse</span></p>
                                            <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 5MB</small>
                                            <input type="file" id="lembarKonfirmasi" accept=".pdf,.doc,.docx" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button class="btn-submit" id="submitLembarKonfirmasi">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        @if($totalBimbingan >= $minTotal)
                            <button class="btn-kirim" id="btnKirim">Kirim</button>
                        @else
                            <button class="btn-kirim" disabled style="opacity: 0.5;">Kirim (Belum Memenuhi Syarat)</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Status, Jadwal, Nilai -->
        <div class="col-lg-4">
            <!-- Status Pendaftaran -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-primary">Status Pendaftaran</h6>
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
                    <h6 class="fw-bold mb-0 text-primary">Jadwal Seminar</h6>
                    <i class="fas fa-calendar-alt text-muted"></i>
                </div>
                <div class="card-body p-4 text-center">
                    @if($seminar && $seminar->jadwal_seminar_file)
                        <a href="{{ route('kp.seminar.download', basename($seminar->jadwal_seminar_file)) }}"
                           class="btn btn-outline-primary btn-sm mb-2" target="_blank">
                            <i class="fas fa-download"></i> Unduh Jadwal
                        </a>
                    @else
                        <button class="btn btn-outline-primary btn-sm mb-2" disabled>Unduh Jadwal</button>
                    @endif
                    <!-- <button class="btn btn-outline-secondary btn-sm">Doc</button> -->
                </div>
            </div>

            <!-- Nilai KP -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-primary">Nilai KP</h6>
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


<style>
    /* Import Modern Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    /* Global Typography */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        font-size: 14px;
        line-height: 1.6;
        color: #374151;
    }

    /* Typography Hierarchy */
    h4, h5, h6 {
        font-weight: 600 !important;
        color: #1f2937 !important;
        letter-spacing: -0.025em !important;
    }

    h4 {
        font-size: 2rem !important;
        margin-bottom: 1rem !important;
    }

    h5 {
        font-size: 1.5rem !important;
        margin-bottom: 1rem !important;
    }

    h6 {
        font-size: 1.125rem !important;
        margin-bottom: 0.75rem !important;
    }

    p, .form-group label, .kp-list-item {
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        color: #6b7280 !important;
    }

    .form-control, .form-select {
        font-size: 0.875rem !important;
        font-weight: 400 !important;
    }

    .btn {
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        letter-spacing: 0.025em !important;
    }

    /* Glassmorphism Card Styles */
    .car {
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 4px 16px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s ease !important;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15), 0 6px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Form Controls with Rounded Corners */
    .form-control, .form-select {
        border-radius: 12px !important;
        border: none !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(5px) !important;
        transition: all 0.3s ease !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        background: rgba(255, 255, 255, 0.95) !important;
    }

    /* Buttons with Rounded Corners */
    .btn {
        border-radius: 12px !important;
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
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }

    .btn-outline-primary:hover {
        background-color: #5a67d8 !important;
        color: white !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(90, 103, 216, 0.3) !important;
    }

    .btn-warning:hover, .btn-danger:hover, .btn-success:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }

    /* Upload Area Styles */
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 20px 15px;
        text-align: center;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .upload-area:hover {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    }

    .upload-area.dragover {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        transform: scale(1.02);
    }

    .upload-icon {
        font-size: 2rem;
        color: #9ca3af;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .upload-area:hover .upload-icon {
        color: #3b82f6;
        transform: scale(1.1);
    }

    .upload-area.dragover .upload-icon {
        color: #10b981;
    }

    .upload-content p {
        margin: 0 0 10px 0;
        color: #6b7280;
        font-size: 1rem;
    }

    .upload-link {
        color: #3b82f6;
        text-decoration: underline;
        cursor: pointer;
        font-weight: 500;
    }

    .upload-link:hover {
        color: #1d4ed8;
    }

    /* Upload Section Borders */
    .upload-section {
        border: 3px solid #64748b;
        border-radius: 12px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        margin-bottom: 15px;
        position: relative;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .upload-section:not(:last-child)::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 1px;
        background: linear-gradient(90deg, transparent, #d1d5db, transparent);
    }

    /* Modern Gradient Buttons */
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    }

    .btn-submit:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.4);
    }

    .btn-kirim {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-kirim:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 35px rgba(245, 87, 108, 0.6);
        background: linear-gradient(135deg, #e785f0 0%, #e54a5f 100%);
    }

    .btn-kirim:active {
        transform: translateY(-1px) scale(1.02);
        box-shadow: 0 6px 20px rgba(245, 87, 108, 0.5);
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

    // Initialize drag and drop for each upload area
    initializeDragDrop('laporanKpDropArea', 'laporanKp');
    initializeDragDrop('penilaianPerusahaanDropArea', 'penilaianPerusahaan');
    initializeDragDrop('suratKpDropArea', 'suratKp');
    initializeDragDrop('krsAnggotaDropArea', 'krsAnggota');
    initializeDragDrop('suratPersetujuanDropArea', 'suratPersetujuan');
    initializeDragDrop('lembarKonfirmasiDropArea', 'lembarKonfirmasi');

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

    // Handle KRS Anggota upload
    document.getElementById('submitKrsAnggota')?.addEventListener('click', function() {
        uploadFile('krsAnggota', 'file_krs_anggota', 'submitKrsAnggota');
    });

    // Handle Surat Persetujuan upload
    document.getElementById('submitSuratPersetujuan')?.addEventListener('click', function() {
        uploadFile('suratPersetujuan', 'file_surat_persetujuan', 'submitSuratPersetujuan');
    });

    // Handle Lembar Konfirmasi upload
    document.getElementById('submitLembarKonfirmasi')?.addEventListener('click', function() {
        uploadFile('lembarKonfirmasi', 'file_lembar_konfirmasi', 'submitLembarKonfirmasi');
    });

    // Handle Kirim Rekap Bimbingan button
    document.getElementById('btnKirim')?.addEventListener('click', function() {
        kirimRekapBimbingan();
    });

function initializeDragDrop(dropAreaId, inputId) {
    const dropArea = document.getElementById(dropAreaId);
    const fileInput = document.getElementById(inputId);

    if (!dropArea || !fileInput) return;

    // Click to browse
    dropArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Drag over
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });

    // Drag leave
    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('dragover');
    });

    // Drop
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            // Optionally show file name or something
        }
    });

    // File input change
    fileInput.addEventListener('change', () => {
        // Optional: show selected file name
    });
}

function kirimRekapBimbingan() {
    if (!kpRequestId) {
        Swal.fire('Error!', 'Data KP tidak ditemukan. Silakan refresh halaman.', 'error');
        return;
    }

    Swal.fire({
        title: 'Kirim Rekap Bimbingan?',
        text: 'Apakah Anda yakin ingin mengirim rekap bimbingan? Setelah dikirim, data akan dikirim ke koordinator untuk persetujuan.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kirim',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            const button = document.getElementById('btnKirim');
            const originalText = button.textContent;
            button.textContent = 'Mengirim...';
            button.disabled = true;

            fetch('{{ route("kp.seminar.kirim-rekap") }}', {
                method: 'POST',
                body: JSON.stringify({
                    kp_request_id: kpRequestId
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', 'Rekap bimbingan berhasil dikirim ke koordinator.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', 'Gagal mengirim rekap: ' + (data.message || 'Terjadi kesalahan'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat mengirim rekap.', 'error');
            })
            .finally(() => {
                button.textContent = originalText;
                button.disabled = false;
            });
        }
    });
}

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