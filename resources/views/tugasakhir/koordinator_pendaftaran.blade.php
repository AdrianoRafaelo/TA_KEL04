@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Seminar Proposal</li>
                </ol>
            </nav>
            <h4 class="mb-0">Tugas Akhir</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/koordinator-pendaftaran') }}" class="kp-tab active">Pendaftaran Judul</a>
                <a href="{{ route('koordinator.mahasiswa.ta') }}" class="kp-tab">Mahasiswa TA</a>
                <a href="{{ route(name: 'koordinator.sempro') }}" class="kp-tab">Seminar Proposal</a>
                <a href="{{ route('koordinator.semhas') }}" class="kp-tab">Seminar Hasil</a>
                <a href="{{ route('koordinator.sidang') }}" class="kp-tab">Sidang Akhir</a>
                <a href="{{ url('/koordinator-skripsi') }}" class="kp-tab ">Unggah Skripsi</a>
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
    </div>

    <!-- INFORMASI UMUM — KOTAK KECIL + JARAK KE BAWAH -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-inline-flex align-items-center bg-white rounded shadow-sm px-3 py-2 border" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <span class="me-2 text-success">
                    <i class="fas fa-pencil-alt"></i>
                </span>
                <span class="fw-bold text-dark" style="font-size: 0.95rem;">Informasi Umum</span>
            </div>
        </div>
    </div>

    <!-- STATUS PENDAFTARAN -->
    @php
        $statusPendaftaran = 'Terbuka';
        $statusColor = 'success';
        $statusIcon = 'fas fa-check-circle';
        $statusMessage = 'Pendaftaran TA sedang terbuka untuk mahasiswa';

        if ($pengaturanTa) {
            if ($pengaturanTa->pendaftaran_ditutup) {
                $statusPendaftaran = 'Ditutup Manual';
                $statusColor = 'danger';
                $statusIcon = 'fas fa-times-circle';
                $statusMessage = 'Pendaftaran ditutup secara manual oleh koordinator';
            } elseif ($pengaturanTa->batas_waktu_pendaftaran && now()->isAfter($pengaturanTa->batas_waktu_pendaftaran)) {
                $statusPendaftaran = 'Lewat Batas Waktu';
                $statusColor = 'warning';
                $statusIcon = 'fas fa-clock';
                $statusMessage = 'Batas waktu pendaftaran telah berakhir pada ' . \Carbon\Carbon::parse($pengaturanTa->batas_waktu_pendaftaran)->format('d M Y H:i');
            }
        }
    @endphp
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-{{ $statusColor }} d-flex align-items-center">
                <i class="{{ $statusIcon }} me-3 fs-4"></i>
                <div>
                    <h6 class="alert-heading mb-1">Status Pendaftaran: {{ $statusPendaftaran }}</h6>
                    <p class="mb-0">{{ $statusMessage }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- PENGATURAN PENDAFTARAN TA -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('ta.update-pengaturan') }}">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Pengaturan Pendaftaran TA</h6>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Pengaturan</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="batas_waktu_pendaftaran" class="form-label">Batas Waktu Pendaftaran</label>
                                    <input type="datetime-local" class="form-control" id="batas_waktu_pendaftaran" name="batas_waktu_pendaftaran"
                                           value="{{ $pengaturanTa ? \Carbon\Carbon::parse($pengaturanTa->batas_waktu_pendaftaran)->format('Y-m-d\TH:i') : '' }}">
                                    <div class="form-text">
                                        Setelah batas waktu ini, mahasiswa tidak dapat mendaftar judul TA baru.<br>
                                        <small class="text-muted">Kosongkan untuk tidak ada batas waktu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pesan_penutupan" class="form-label">Pesan Penutupan</label>
                                    <textarea class="form-control" id="pesan_penutupan" name="pesan_penutupan" rows="3"
                                              placeholder="Pesan yang akan ditampilkan ketika pendaftaran ditutup">{{ $pengaturanTa ? $pengaturanTa->pesan_penutupan : '' }}</textarea>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="pendaftaran_ditutup" name="pendaftaran_ditutup" value="1"
                                           {{ $pengaturanTa && $pengaturanTa->pendaftaran_ditutup ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pendaftaran_ditutup">
                                        Tutup Pendaftaran Secara Manual
                                        <br><small class="text-muted">Centang untuk menutup, hapus centang untuk membuka kembali</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Cara Membuka Kembali Pendaftaran:</h6>
                                    <ul class="mb-0">
                                        <li><strong>Untuk membuka pendaftaran yang ditutup manual:</strong> Hapus centang pada "Tutup Pendaftaran Secara Manual"</li>
                                        <li><strong>Untuk membuka pendaftaran yang lewat batas waktu:</strong> Set tanggal baru yang lebih lama atau kosongkan field batas waktu</li>
                                        <li><strong>Untuk membuka pendaftaran tanpa batas:</strong> Kosongkan field "Batas Waktu Pendaftaran"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch I & Batch II — BERDAMPINGAN -->
    <div class="row">
        <!-- Batch I -->
        <div class="col-lg-6 pe-lg-3 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('koordinator.terima.judul.batch1') }}">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Pengajuan Proposal Batch I</h6>
                            <button type="submit" class="btn btn-success btn-sm" id="approveBatch1">+ Terima Judul</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead>
                                    <tr class="text-muted small">
                                        <th style="width: 30px;">No.</th>
                                        <th><input type="checkbox" id="select-all-batch1"></th>
                                        <th>Dosen</th>
                                        <th>Judul</th>
                                        <th style="width: 100px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($judul_dosen as $index => $jd)
                                    <tr>
                                        <td class="text-muted small">{{ $index + 1 }}.</td>
                                        <td>
                                            @if($jd->status_id != \App\Models\RefStatusTa::where('name', 'disetujui')->first()->id)
                                                <input type="checkbox" name="ids[]" value="{{ $jd->id }}">
                                            @else
                                                <input type="checkbox" disabled>
                                            @endif
                                        </td>
                                        <td class="small">
                                            <strong>{{ $jd->dosen }}</strong>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $jd->judul }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm w-100 rounded-pill btn-selengkapnya" style="font-size: 0.765rem; padding: 0.25rem 0;"
                                                    data-id="{{ $jd->id }}"
                                                    data-judul="{{ $jd->judul }}"
                                                    data-dosen="{{ $jd->dosen }}"
                                                    data-students='@json($jd->interested_students)'>Selengkapnya</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Batch II -->
        <div class="col-lg-6 ps-lg-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('koordinator.terima.judul.batch2') }}">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold text-dark" style="font-size: 1rem;">Pengajuan Proposal Batch II</h6>
                            <button type="submit" class="btn btn-success btn-sm" id="approveBatch2">+ Terima Judul</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr class="text-muted small">
                                        <th style="width: 30px;">No.</th>
                                        <th><input type="checkbox" id="select-all-batch2"></th>
                                        <th>Nama (NIM)</th>
                                        <th>Judul</th>
                                        <th>Dokumen</th>
                                        <th>Pendaftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($judul_mahasiswa as $index => $jm)
                                    <tr>
                                        <td class="small">{{ $index + 1 }}.</td>
                                        <td>
                                            @if($jm->status_id != \App\Models\RefStatusTa::where('name', 'disetujui')->first()->id)
                                                <input type="checkbox" name="ids[]" value="{{ $jm->id }}">
                                            @else
                                                <input type="checkbox" disabled>
                                            @endif
                                        </td>
                                        <td class="small">{{ $jm->nama }} @if($jm->nim) ({{ $jm->nim }}) @endif</td>
                                        <td class="small">
                                            {{ $jm->judul }}
                                        </td>
                                        <td class="small">
                                            @if($jm->file)
                                                <a href="{{ asset('storage/' . $jm->file) }}" download>Download</a>
                                            @else
                                                Tidak ada
                                            @endif
                                        </td>
                                        <td class="small">
                                            {{ $jm->transaksi->count() }} Dosen
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Selengkapnya -->
<div class="modal fade" id="modalSelengkapnya" tabindex="-1" aria-labelledby="modalSelengkapnyaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSelengkapnyaLabel">Detail Judul TA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Judul:</strong> <span id="modal-judul"></span>
                </div>
                <div class="mb-3">
                    <strong>Dosen:</strong> <span id="modal-dosen"></span>
                </div>
                <div class="mb-3">
                    <strong>Mahasiswa yang Mengambil:</strong>
                    <ul id="modal-students" class="list-group mt-2">
                        <!-- Students will be populated here -->
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Select all checkboxes for Batch I
    document.getElementById('select-all-batch1').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="ids[]"]:not(:disabled)');
        checkboxes.forEach(checkbox => {
            if (checkbox.closest('form').action.includes('batch1')) {
                checkbox.checked = this.checked;
            }
        });
    });

    // Select all checkboxes for Batch II
    document.getElementById('select-all-batch2').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="ids[]"]:not(:disabled)');
        checkboxes.forEach(checkbox => {
            if (checkbox.closest('form').action.includes('batch2')) {
                checkbox.checked = this.checked;
            }
        });
    });

    // Approve Batch I
    document.getElementById('approveBatch1').addEventListener('click', function(e) {
        e.preventDefault();
        const selected = document.querySelectorAll('input[name="ids[]"]:checked:not(:disabled)');
        if (selected.length === 0) {
            alert('Pilih setidaknya satu judul.');
            return;
        }
        const ids = Array.from(selected).map(cb => cb.value);

        const modal = document.createElement('div');
        modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;';
        modal.innerHTML = `
            <div style="background:white;padding:20px;border-radius:8px;max-width:400px;width:90%;">
                <h5>Konfirmasi</h5>
                <p>Yakin menerima ${ids.length} judul?</p>
                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                    <button id="cancelBtn" style="padding:8px 16px;border:1px solid #ccc;background:#f8f9fa;border-radius:4px;cursor:pointer;">Batal</button>
                    <button id="confirmBtn" style="padding:8px 16px;background:#28a745;color:white;border:none;border-radius:4px;cursor:pointer;">Ya</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        modal.querySelector('#cancelBtn').addEventListener('click', () => modal.remove());
        modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });

        modal.querySelector('#confirmBtn').addEventListener('click', () => {
            modal.remove();
            const form = document.querySelector('form[action*="batch1"]');
            form.submit();
        });
    });

    // Approve Batch II
    document.getElementById('approveBatch2').addEventListener('click', function(e) {
        e.preventDefault();
        const selected = document.querySelectorAll('input[name="ids[]"]:checked:not(:disabled)');
        if (selected.length === 0) {
            alert('Pilih setidaknya satu judul.');
            return;
        }
        const ids = Array.from(selected).map(cb => cb.value);

        const modal = document.createElement('div');
        modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;';
        modal.innerHTML = `
            <div style="background:white;padding:20px;border-radius:8px;max-width:400px;width:90%;">
                <h5>Konfirmasi</h5>
                <p>Yakin menerima ${ids.length} judul?</p>
                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                    <button id="cancelBtn" style="padding:8px 16px;border:1px solid #ccc;background:#f8f9fa;border-radius:4px;cursor:pointer;">Batal</button>
                    <button id="confirmBtn" style="padding:8px 16px;background:#28a745;color:white;border:none;border-radius:4px;cursor:pointer;">Ya</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        modal.querySelector('#cancelBtn').addEventListener('click', () => modal.remove());
        modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });

        modal.querySelector('#confirmBtn').addEventListener('click', () => {
            modal.remove();
            const form = document.querySelector('form[action*="batch2"]');
            form.submit();
        });
    });

    // Handle Selengkapnya button
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('modalSelengkapnya'));
        document.querySelectorAll('.btn-selengkapnya').forEach(button => {
            button.addEventListener('click', function() {
                const judul = this.dataset.judul;
                const dosen = this.dataset.dosen;
                const students = JSON.parse(this.dataset.students);

                document.getElementById('modal-judul').textContent = judul;
                document.getElementById('modal-dosen').textContent = dosen;

                const studentsList = document.getElementById('modal-students');
                studentsList.innerHTML = '';

                if (students.length === 0) {
                    studentsList.innerHTML = '<li class="list-group-item">Belum ada mahasiswa yang mengambil judul ini.</li>';
                } else {
                    students.forEach(student => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item';
                        li.textContent = student.nama + ' (' + student.username + ')';
                        studentsList.appendChild(li);
                    });
                }

                modal.show();
            });
        });
    });
</script>
@endsection

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
    
    .kp-tabs .kp-tab {
        padding: 8px 16px;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
        color: #495057;
        font-size: 0.9rem;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .kp-tabs .kp-tab.active {
        background: #6c5ce7;
        color: white;
        border-color: #6c5ce7;
    }
    .kp-tabs .kp-tab:hover:not(.active) {
        background: #e9ecef;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .table-sm td, .table-sm th {
        padding: 0.4rem 0.5rem;
        vertical-align: middle;
    }

    .btn-sm {
        font-size: 0.765rem;
        padding: 0.25rem 0.5rem;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .d-inline-flex {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
    }

    @media (max-width: 992px) {
        .pe-lg-3, .ps-lg-3 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        .d-inline-flex {
            display: block;
            width: fit-content;
            margin: 0 auto 1.5rem;
        }
    }
</style>

