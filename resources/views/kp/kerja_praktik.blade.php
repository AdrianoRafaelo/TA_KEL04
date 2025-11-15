{{-- resources/views/kerja_praktik.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Page</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">Kerja Praktik</a></li>
                <li class="breadcrumb-item active" aria-current="page">Informasi Umum</li>
            </ol>
        </nav>
        <h4 class="mb-2">Kerja Praktik</h4>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="kp-tabs">
            <button class="kp-tab active">Informasi Umum</button>
            <a href="{{ url('/pendaftaran-kp') }}" class="kp-tab">Pendaftaran KP</a>
            <button class="kp-tab">Pelaksanaan KP</button>
            <button class="kp-tab">Seminar KP</button>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <img src="{{ asset('assets/images/banner-kp.jpg') }}" class="kp-banner img-fluid" alt="Banner KP">
        <div class="card">
            <div class="card-body p-3">
                <p>
                    <strong>Kerja Praktik</strong> - Merupakan matakuliah wajib di MR. Matakuliah ini akan memberikan kesempatan bagi setiap mahasiswa untuk mengasah kemampuan pembelajaran selama ini melalui kegiatan aktual di lapangan.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="font-weight-bold text-center mb-4">Alur Kegiatan Kerja Praktik</h5>
                <div class="kp-stepper">
                    <div class="kp-step active">
                        <span class="kp-circle">1</span>
                        <div class="kp-label font-weight-bold">INFORMASI UMUM</div>
                    </div>
                    <div class="kp-step">
                        <span class="kp-circle">2</span>
                        <div class="kp-label text-primary font-weight-bold">PENDAFTARAN KP</div>
                    </div>
                    <div class="kp-step">
                        <span class="kp-circle">3</span>
                        <div class="kp-label text-secondary">PELAKSANAAN KP</div>
                    </div>
                    <div class="kp-step">
                        <span class="kp-circle">4</span>
                        <div class="kp-label text-secondary">SEMINAR KP</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <!-- DAFTAR KELOMPOK -->
        <div class="kp-action-card mb-3">
            <span class="kp-icon" style="color:#f9b115;"><i class="mdi mdi-account-multiple-plus"></i></span>
            <div>
                <div class="kp-title"><a href="#" class="text-primary" id="openKelompokModal">Daftar Kelompok KP</a></div>
                <div class="kp-desc">
                    <a href="#" class="text-decoration-none" id="openKelompokModalLink">+ Kelompok KP</a>
                </div>
            </div>
        </div>

        <!-- UNGGAH CV (STATIS) -->
        <div class="kp-action-card">
            <span class="kp-icon" style="color:#b16cf9;"><i class="mdi mdi-fingerprint"></i></span>
            <div>
                <div class="kp-title"><a href="#" class="text-primary" id="openUnggahCVModal">Unggah CV Kelompok</a></div>
                <div class="kp-desc">+Unggah CV</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* === MODAL STYLES (SHARED) === */
#kelompokModalOverlay,
#cvModalOverlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(0, 0, 0, 0.75) !important;
    z-index: 99999999 !important;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 20px;
    overflow-y: auto;
}

#kelompokModalContainer,
#cvModalContainer {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.5);
    animation: modalPop 0.3s ease-out;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@keyframes modalPop {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

.modal-header {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-close-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-close-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.1);
}

.modal-body {
    padding: 24px;
    max-height: calc(90vh - 160px);
    overflow-y: auto;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* Buttons */
.btn-primary, .btn-secondary {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-primary {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(30,58,138,0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-outline-primary {
    background: transparent;
    color: #1E3A8A;
    border: 2px solid #1E3A8A;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
}

.btn-outline-primary:hover {
    background: #1E3A8A;
    color: white;
}

.btn-outline-danger {
    background: transparent;
    color: #dc2626;
    border: 1px solid #dc2626;
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-outline-danger:hover {
    background: #dc2626;
    color: white;
}

/* Form */
.input-group {
    display: flex;
    gap: 8px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 12px;
}

.input-group .form-select,
.input-group .form-control {
    border: none;
    background: transparent;
    flex: 1;
    font-size: 14px;
}

.mahasiswa-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 12px;
}

.mahasiswa-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.proposal-info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
}

.loading-state {
    text-align: center;
    padding: 40px;
    color: #6b7280;
}

.file-label {
    background: #e5e7eb;
    color: #374151;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
    cursor: pointer;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    // ========================================
    // 1. MODAL DAFTAR KELOMPOK (DENGAN AJAX)
    // ========================================
    $('#openKelompokModal, #openKelompokModalLink').on('click', function(e) {
        e.preventDefault();
        openKelompokModal();
    });

    function openKelompokModal() {
        $('body').css('overflow', 'hidden');

        const modalHtml = `
        <div id="kelompokModalOverlay">
            <div id="kelompokModalContainer">
                <div class="modal-header">
                    <div class="modal-title">
                        <i class="bi bi-people-fill"></i> Daftar Kelompok Kerja Praktik
                    </div>
                    <button class="modal-close-btn" id="closeKelompokModal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <div class="loading-state">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Memuat data mahasiswa...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelKelompok">Tutup</button>
                    <button type="submit" form="kelompokForm" class="btn-primary">Simpan Kelompok</button>
                </div>
            </div>
        </div>`;

        $('body').append(modalHtml);

        $.get('{{ route("api.mahasiswa") }}')
            .done(function(res) {
                if (res.success && res.data.length > 0) {
                    $('.loading-state').fadeOut(200, () => renderKelompokForm(res.data));
                } else {
                    $('.loading-state').html('<p class="text-warning">Tidak ada data mahasiswa.</p>');
                }
            })
            .fail(() => {
                $('.loading-state').html('<p class="text-danger">Gagal memuat data.</p>');
            });

        $(document).on('click', '#closeKelompokModal, #cancelKelompok', closeKelompokModal);
        $(document).on('click', '#kelompokModalOverlay', function(e) {
            if (e.target === this) closeKelompokModal();
        });
    }

    function closeKelompokModal() {
        $('#kelompokModalOverlay').remove();
        $('body').css('overflow', '');
    }

    function renderKelompokForm(data) {
        const options = data.map(m => `<option value="${m.username}">${m.nama} (${m.nim})</option>`).join('');

        $('.modal-body').html(`
            <div class="proposal-info-card">
                <div class="info-item">
                    <i class="bi bi-info-circle-fill" style="color:#1E3A8A;font-size:20px;"></i>
                    <div>
                        <label style="font-size:11px;color:#6b7280;text-transform:uppercase;">Informasi</label>
                        <span>Buat kelompok kerja praktik dengan memilih mahasiswa yang akan bergabung</span>
                    </div>
                </div>
            </div>
            <form id="kelompokForm">
                <label style="display:block;font-weight:600;color:#1f2937;margin-bottom:16px;">Anggota Kelompok</label>
                <div id="mahasiswaContainer"></div>
                <div style="text-align:center;margin-top:20px;">
                    <button type="button" class="btn-outline-primary" id="addMahasiswa">
                        <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
                    </button>
                </div>
            </form>
        `);

        addMahasiswaField(data);

        $(document).on('click', '#addMahasiswa', () => addMahasiswaField(data));
        $(document).on('click', '.remove-mahasiswa', function() {
            $(this).closest('.input-group, .mahasiswa-item').fadeOut(200, function() { $(this).remove(); });
        });

        $(document).on('change', '.mahasiswa-select', function() {
            const val = $(this).val();
            if (!val) return;
            const m = data.find(x => x.username === val);
            const card = `
                <div class="mahasiswa-item">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;">
                        <div class="mahasiswa-avatar">${m.nama[0].toUpperCase()}</div>
                        <div>
                            <h6 style="margin:0;font-size:14px;">${m.nama}</h6>
                            <small>NIM: ${m.nim}</small>
                        </div>
                    </div>
                    <input type="hidden" name="mahasiswa[]" value="${m.username}">
                    <button type="button" class="btn-outline-danger remove-mahasiswa">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>`;
            $(this).closest('.input-group').replaceWith(card);
        });

        $(document).on('submit', '#kelompokForm', function(e) {
            e.preventDefault();
            const selected = $('input[name="mahasiswa[]"]').map((_, el) => el.value).get();
            if (selected.length === 0) return alert('Pilih minimal 1 mahasiswa.');
            if ([...new Set(selected)].length !== selected.length) return alert('Mahasiswa tidak boleh duplikat.');

            $.ajax({
                url: '{{ route("kerja-praktik.daftar-kelompok") }}',
                method: 'POST',
                data: { mahasiswa: selected },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: res => {
                    if (res.success) {
                        alert('Kelompok berhasil dibuat!');
                        closeKelompokModal();
                    } else {
                        alert(res.message || 'Gagal menyimpan.');
                    }
                },
                error: () => alert('Terjadi kesalahan server.')
            });
        });
    }

    function addMahasiswaField(data) {
        const options = data.map(m => `<option value="${m.username}">${m.nama} (${m.nim})</option>`).join('');
        $('#mahasiswaContainer').append(`
            <div class="input-group">
                <select class="form-select mahasiswa-select" required>
                    <option value="">Pilih Mahasiswa</option>
                    ${options}
                </select>
                <button type="button" class="btn-outline-danger remove-mahasiswa"><i class="bi bi-x"></i></button>
            </div>`);
    }

    // ========================================
    // 2. MODAL UNGGAH CV (DINAMIS)
    // ========================================
    $('#openUnggahCVModal').on('click', function(e) {
        e.preventDefault();

        // Fetch user group
        $.get('{{ route("api.user-group") }}')
            .done(function(res) {
                if (res.success) {
                    openCvModal(res.group);
                } else {
                    alert(res.message);
                }
            })
            .fail(() => {
                alert('Gagal memuat data kelompok.');
            });
    });

    function openCvModal(group) {
        // Fetch existing CVs
        $.get(`{{ url('/api/cv-kelompok') }}/${group.id}`)
            .done(function(existingCvs) {
                const cvMap = {};
                existingCvs.forEach(cv => cvMap[cv.user_id] = cv);

                const modalHtml = `
                <div id="cvModalOverlay">
                    <div id="cvModalContainer">
                        <div class="modal-header">
                            <div class="modal-title">
                                <i class="bi bi-file-earmark-person"></i> Unggah CV Kelompok: ${group.nama_kelompok}
                            </div>
                            <button class="modal-close-btn" id="closeCVModal"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="proposal-info-card">
                                <div class="info-item">
                                    <i class="bi bi-info-circle-fill" style="color:#1E3A8A;font-size:20px;"></i>
                                    <div>
                                        <label style="font-size:11px;color:#6b7280;text-transform:uppercase;">Petunjuk</label>
                                        <span>Unggah CV dalam format <strong>PDF</strong>, maksimal <strong>5MB</strong> per file.</span>
                                    </div>
                                </div>
                            </div>

                            <form id="cvForm" enctype="multipart/form-data">
                                <input type="hidden" name="kp_group_id" value="${group.id}">
                                ${group.mahasiswa.map((m, index) => {
                                    const existing = cvMap[m.username];
                                    return `
                                    <div class="mahasiswa-item mb-3">
                                        <div class="mahasiswa-avatar">${m.nama[0]}</div>
                                        <div style="flex:1;">
                                            <h6 style="margin:0;font-size:14px;font-weight:600;">${m.nama}</h6>
                                            <small class="text-muted">NIM: ${m.nim}</small>
                                            <div class="mt-1">
                                                ${existing
                                                    ? `<span style="color:green;font-size:12px;"><i class="bi bi-check-circle"></i> Sudah diunggah</span>`
                                                    : `<span style="color:#dc2626;font-size:12px;"><i class="bi bi-x-circle"></i> Belum diunggah</span>`
                                                }
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="file" name="cv_files[${index}]" class="form-control" accept=".pdf" ${existing ? 'disabled' : ''}>
                                        <input type="hidden" name="user_ids[${index}]" value="${m.username}">
                                        <label class="file-label">Pilih File</label>
                                    </div>
                                    ${existing ? `<small class="text-success d-block mb-3">File: <a href="{{ asset('storage') }}/${existing.file_path}" target="_blank" style="color:green;">${existing.original_name}</a></small>` : ''}
                                    <hr>
                                    `;
                                }).join('')}
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" id="cancelCV">Batal</button>
                            <button type="button" class="btn-primary" id="simpanCV">Simpan CV</button>
                        </div>
                    </div>
                </div>`;

                $('body').append(modalHtml);
                $('body').css('overflow', 'hidden');

                // Tutup modal
                $(document).on('click', '#closeCVModal, #cancelCV, #cvModalOverlay', function(e) {
                    if (e.target === this || $(e.target).is('#closeCVModal, #cancelCV')) {
                        $('#cvModalOverlay').fadeOut(200, function() { $(this).remove(); });
                        $('body').css('overflow', '');
                    }
                });

                // Klik label → buka file
                $('.modal-body').on('click', '.file-label', function() {
                    $(this).prev('input[type="file"]').click();
                });

                // Update label saat pilih file
                $('.modal-body').on('change', 'input[type="file"]', function() {
                    const file = this.files[0];
                    if (file) {
                        $(this).next().html(`<i class="bi bi-check-circle"></i> ${file.name.substring(0, 15)}${file.name.length > 15 ? '...' : ''}`);
                    }
                });

                // Simpan
                $(document).on('click', '#simpanCV', function() {
                    const formData = new FormData(document.getElementById('cvForm'));
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    $.ajax({
                        url: '{{ route("kerja-praktik.upload-cv") }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            if (res.success) {
                                alert(res.message);
                                $('#cvModalOverlay').remove();
                                $('body').css('overflow', '');
                            } else {
                                alert(res.message || 'Gagal mengunggah CV.');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan server.');
                        }
                    });
                });
            })
            .fail(() => {
                alert('Gagal memuat data CV.');
            });
    }

});
</script>
@endsection