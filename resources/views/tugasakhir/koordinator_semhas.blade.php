@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Seminar Hasil</li>
                </ol>
            </nav>
            <h4 class="mb-0">Tugas Akhir</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/koordinator-pendaftaran') }}" class="kp-tab">Pendaftaran Judul</a>
                <a href="{{ route('koordinator.mahasiswa.ta') }}" class="kp-tab">Mahasiswa TA</a>
                <a href="{{ route('koordinator.sempro') }}" class="kp-tab">Seminar Proposal</a>
                <a href="{{ route('koordinator.semhas') }}" class="kp-tab active">Seminar Hasil</a>
                <a href="{{ route('koordinator.sidang') }}" class="kp-tab">Sidang Akhir</a>
                <a href="{{ url('/koordinator-skripsi') }}" class="kp-tab ">Unggah Skripsi</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Seminar Hasil
            </button>
        </div>
        <div>
            <button id="editJadwalBtn" class="btn btn-sm btn-secondary">
                <i class="bi bi-pencil me-1"></i>Edit Jadwal Seminar
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th style="width: 80px;">No.</th>
                    <th style="width: 130px;">Mahasiswa</th>
                    <th style="width: 280px;">Judul</th>
                    <th style="width: 120px;">Dokumen Penelitian</th>
                    <th style="width: 110px;">Pembimbing</th>
                    <th style="width: 110px;">Pengulas I</th>
                    <th style="width: 110px;">Pengulas II</th>
                    <th style="width: 150px;">Jadwal Seminar</th>
                    <th style="width: 100px;">Hasil</th>
                </tr>
            </thead>
            <tbody style="font-size:14px;">
                @forelse($seminarHasils as $index => $hasil)
                <tr class="hover-row">
                    <td>
                        <input type="checkbox" class="hasil-checkbox" name="selected_hasils[]" value="{{ $hasil->id }}" {{ $hasil->status == 'approved' ? 'disabled' : '' }}>
                    </td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hasil->nama }} @if($hasil->nim) ({{ $hasil->nim }}) @endif</td>
                    <td>{{ $hasil->taPendaftaran->judul }}</td>
                    <td>
                        <i class="bi bi-file-earmark-text me-1"></i>
                        @if($hasil->file_dokumen_ta)
                            <a href="{{ route('storage.file', ['path' => $hasil->file_dokumen_ta]) }}" target="_blank">Dokumen</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $hasil->pembimbing ?? '-' }}</td>
                    <td>{{ $hasil->pengulas_1 ?? '-' }}</td>
                    <td>{{ $hasil->pengulas_2 ?? '-' }}</td>
                    <td class="jadwal-cell" data-hasil-id="{{ $hasil->id }}">
                        @if($hasil->jadwal_seminar_file)
                            <a href="{{ asset('storage/' . $hasil->jadwal_seminar_file) }}" target="_blank" class="ms-2 jadwal-file-link-{{ $hasil->id }}">Lihat File</a>
                            <input type="file" class="form-control form-control-sm jadwal-file-input d-none" data-hasil-id="{{ $hasil->id }}" accept=".pdf,.doc,.docx">
                        @else
                            <input type="file" class="form-control form-control-sm jadwal-file-input" data-hasil-id="{{ $hasil->id }}" accept=".pdf,.doc,.docx">
                        @endif
                    </td>
                    <td>
                        <button 
                            class="btn btn-sm btn-success btn-selengkapnya"
                            data-hasil-id="{{ $hasil->id }}"
                            data-mahasiswa="{{ $hasil->nama }} @if($hasil->nim) ({{ $hasil->nim }}) @endif"
                            data-judul="{{ $hasil->judul }}"
                            data-pembimbing="{{ $hasil->pembimbing }}"
                            data-pengulas1="{{ $hasil->pengulas_1 ?? '' }}"
                            data-pengulas2="{{ $hasil->pengulas_2 ?? '' }}"
                            data-file_dokumen_ta="{{ $hasil->file_dokumen_ta ? asset('storage/' . $hasil->file_dokumen_ta) : '' }}"
                            data-file_log_activity="{{ $hasil->file_log_activity ? asset('storage/' . $hasil->file_log_activity) : '' }}"
                            data-file_persetujuan="{{ $hasil->file_persetujuan ? asset('storage/' . $hasil->file_persetujuan) : '' }}"
                            data-jadwal_seminar_file="{{ $hasil->jadwal_seminar_file ? asset('storage/' . $hasil->jadwal_seminar_file) : '' }}"
                            data-rubrik_penilaian="{{ $hasil->rubrik_penilaian ? asset('storage/' . $hasil->rubrik_penilaian) : '' }}"
                            data-form_review="{{ $hasil->form_review ? asset('storage/' . $hasil->form_review) : '' }}"
                            data-revisi_dokumen="{{ $hasil->revisi_dokumen ? asset('storage/' . $hasil->revisi_dokumen) : '' }}"
                            data-form_revisi="{{ $hasil->form_revisi ? asset('storage/' . $hasil->form_revisi) : '' }}"
                            data-berita_acara_pembimbing="{{ $hasil->berita_acara_pembimbing ? asset('storage/' . $hasil->berita_acara_pembimbing) : '' }}"
                            data-penilaian_pembimbing="{{ $hasil->penilaian_pembimbing ? asset('storage/' . $hasil->penilaian_pembimbing) : '' }}"
                            data-berita_acara_pengulas1="{{ $hasil->berita_acara_pengulas1 ? asset('storage/' . $hasil->berita_acara_pengulas1) : '' }}"
                            data-penilaian_pengulas1="{{ $hasil->penilaian_pengulas1 ? asset('storage/' . $hasil->penilaian_pengulas1) : '' }}"
                            data-berita_acara_pengulas2="{{ $hasil->berita_acara_pengulas2 ? asset('storage/' . $hasil->berita_acara_pengulas2) : '' }}"
                            data-penilaian_pengulas2="{{ $hasil->penilaian_pengulas2 ? asset('storage/' . $hasil->penilaian_pengulas2) : '' }}"
                        >
                            Details
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">Tidak ada data seminar hasil</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer Button -->
    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-success me-2" id="approveSelected">Terima Seminar Hasil</button>
        <button class="btn btn-success" id="saveSimpan">Simpan</button>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let editMode = false;

    $('#editJadwalBtn').click(function() {
        editMode = !editMode;
        if (editMode) {
            $(this).text('Batal Edit');
            $('.jadwal-cell .jadwal-file-input').removeClass('d-none');
            $('.jadwal-cell .jadwal-file-link').addClass('d-none');
        } else {
            $(this).html('<i class="bi bi-pencil me-1"></i>Edit Jadwal Seminar');
            $('.jadwal-cell .jadwal-file-input').addClass('d-none');
            $('.jadwal-cell .jadwal-file-link').removeClass('d-none');
        }
    });

    $('#selectAll').change(function() {
        $('.hasil-checkbox').prop('checked', this.checked);
    });

    // Save Jadwal Seminar files
    $('#saveSimpan').click(function() {
        const fileInputs = $('.jadwal-file-input');
        const fileUploads = [];
        
        fileInputs.each(function() {
            const input = this;
            const file = input.files[0];
            if (file) {
                const hid = $(input).data('hasil-id');
                const fd = new FormData();
                fd.append('jadwal_seminar_file', file);
                fd.append('hasil_id', hid);
                fileUploads.push({ formData: fd, hasilId: hid });
            }
        });

        if (fileUploads.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Tidak ada file yang dipilih untuk diunggah.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
            return;
        }

        Promise.all(fileUploads.map(item =>
            fetch('{{ route("koordinator.upload.semhas.jadwal") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: item.formData
            }).then(r => r.json())
        ))
        .then(uploadResults => {
            let allOk = true;
            uploadResults.forEach((res, idx) => {
                const hid = fileUploads[idx].hasilId;
                if (res.success) {
                    const selectorLink = '.jadwal-file-link-' + hid;
                    if ($(selectorLink).length) {
                        $(selectorLink).attr('href', res.file_path);
                    } else {
                        const inputEl = $('.jadwal-file-input[data-hasil-id="' + hid + '"]');
                        inputEl.before('<a href="' + res.file_path + '" target="_blank" class="ms-2 jadwal-file-link-' + hid + '">Lihat File</a>');
                        inputEl.addClass('d-none');
                    }
                } else {
                    allOk = false;
                }
            });

            if (allOk) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'File jadwal berhasil disimpan!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                location.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Beberapa file gagal diunggah. Periksa kembali.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengunggah file jadwal.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
        });
    });

    $('#approveSelected').click(function() {
        const selected = $('.hasil-checkbox:checked:not(:disabled)');
        if (selected.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih setidaknya satu seminar hasil.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
            return;
        }
        const ids = selected.map((i, cb) => cb.value).get();

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Yakin menerima ' + ids.length + ' seminar hasil?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("koordinator.approve.semhas") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ ids })
                })
                .then(r => r.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Sukses!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    location.reload();
                });
            }
        });
    });

    // Modal Detail – HANYA TOMBOL UPLOAD / GANTI
    $('.btn-selengkapnya').click(function() {
        const data = $(this).data();
        const modal = $('<div class="modal-overlay">' +
            '<div class="modal-container">' +
                '<div class="modal-header">' +
                    '<div class="modal-title">' +
                        '<i class="bi bi-file-earmark-text-fill me-2"></i>' +
                        'Detail Seminar Hasil' +
                    '</div>' +
                    '<button id="closeModal" class="modal-close-btn">' +
                        '<i class="bi bi-x-lg"></i>' +
                    '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                    '<div class="proposal-info-card">' +
                        '<div class="info-grid">' +
                            '<div class="info-item">' +
                                '<i class="bi bi-person-fill info-icon"></i>' +
                                '<div class="info-content">' +
                                    '<label>Mahasiswa</label>' +
                                    '<span>' + data.mahasiswa + '</span>' +
                                '</div>' +
                            '</div>' +
                            '<div class="info-item">' +
                                '<i class="bi bi-person-badge-fill info-icon"></i>' +
                                '<div class="info-content">' +
                                    '<label>Pembimbing</label>' +
                                    '<span>' + (data.pembimbing || '-') + '</span>' +
                                '</div>' +
                            '</div>' +
                            '<div class="info-item full-width">' +
                                '<i class="bi bi-book-fill info-icon"></i>' +
                                '<div class="info-content">' +
                                    '<label>Judul Tugas Akhir</label>' +
                                    '<span>' + data.judul + '</span>' +
                                '</div>' +
                            '</div>' +
                            '<div class="info-item">' +
                                '<i class="bi bi-person-check-fill info-icon"></i>' +
                                '<div class="info-content">' +
                                    '<label>Pengulas I</label>' +
                                    '<span>' + (data.pengulas1 || 'Belum dipilih') + '</span>' +
                                '</div>' +
                            '</div>' +
                            '<div class="info-item">' +
                                '<i class="bi bi-person-check-fill info-icon"></i>' +
                                '<div class="info-content">' +
                                    '<label>Pengulas II</label>' +
                                    '<span>' + (data.pengulas2 || 'Belum dipilih') + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="tabs-container">' +
                        '<div class="tabs-header">' +
                            '<button class="tab-btn active" data-tab="pembimbing">' +
                                '<i class="bi bi-person-badge me-1"></i>Pembimbing' +
                            '</button>' +
                            '<button class="tab-btn" data-tab="pengulas1">' +
                                '<i class="bi bi-person-check me-1"></i>Pengulas I' +
                            '</button>' +
                            '<button class="tab-btn" data-tab="pengulas2">' +
                                '<i class="bi bi-person-check me-1"></i>Pengulas II' +
                            '</button>' +
                            '<button class="tab-btn" data-tab="mahasiswa">' +
                                '<i class="bi bi-person me-1"></i>Mahasiswa' +
                            '</button>' +
                        '</div>' +
                        '<div class="tabs-content">' +
                            // Pembimbing
                            generateTab('pembimbing', ['Berita Acara', 'Penilaian'], [
                                { dataKey: 'berita_acara_pembimbing', field: 'berita_acara_pembimbing' },
                                { dataKey: 'penilaian_pembimbing', field: 'penilaian_pembimbing' }
                            ]) +
                            // Pengulas I
                            generateTab('pengulas1', ['Berita Acara', 'Penilaian'], [
                                { dataKey: 'berita_acara_pengulas1', field: 'berita_acara_pengulas1' },
                                { dataKey: 'penilaian_pengulas1', field: 'penilaian_pengulas1' }
                            ]) +
                            // Pengulas II
                            generateTab('pengulas2', ['Berita Acara', 'Penilaian'], [
                                { dataKey: 'berita_acara_pengulas2', field: 'berita_acara_pengulas2' },
                                { dataKey: 'penilaian_pengulas2', field: 'penilaian_pengulas2' }
                            ]) +
                            // Mahasiswa
                            generateTab('mahasiswa', ['Revisi Dokumen', 'Form Revisi'], [
                                { dataKey: 'revisiDokumen', field: 'revisi_dokumen' },
                                { dataKey: 'formRevisi', field: 'form_revisi' }
                            ]) +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                    '<button id="simpanBtn" class="btn-primary"><i class="bi bi-check-circle me-2"></i>Simpan Perubahan</button>' +
                '</div>' +
            '</div>' +
        '</div>');

        // Fungsi generateTab – HANYA TOMBOL UPLOAD / GANTI
        function generateTab(tabId, labels, keyObjs) {
            let html = '<div id="tab-' + tabId + '" class="tab-content' + (tabId === 'pembimbing' ? ' active' : '') + '">';
            html += '<div class="doc-grid">';
            keyObjs.forEach((obj, i) => {
                const path = data[obj.dataKey] || '';
                const hasFile = !!path;
                const field = obj.field;

                const uploadedClass = hasFile ? ' uploaded' : '';
                html += '<div class="upload-item' + uploadedClass + '" data-field="' + field + '" data-has-file="' + hasFile + '">';
                html += '<div class="upload-label"><i class="bi bi-file-earmark-text" style="margin-right:8px;color:#1E3A8A;"></i>' + labels[i] + (hasFile ? ' <i class="bi bi-check-circle-fill text-success"></i>' : '') + '</div>';
                html += '<div class="file-actions">';

                if (hasFile) {
                    // Tombol Ganti
                    html += '<button class="btn-upload btn-sm">Ganti</button>';
                    // Simpan path di hidden div (untuk backend)
                    html += '<div class="hidden-file-path" style="display:none;" data-path="' + path + '"></div>';
                } else {
                    // Tombol Upload
                    html += '<button class="btn-upload btn-sm">Upload</button>';
                }

                html += '</div>';
                html += '<input type="file" class="file-input" accept=".pdf,.doc,.docx" style="display:none;">';
                html += '<div class="upload-status small mt-1" style="display:none; min-height:20px;"></div>';
                html += '</div>';
            });
            html += '</div></div>';
            return html;
        }

        $('body').append(modal);

        // Close Modal
        modal.find('#closeModal').click(() => modal.remove());
        modal.click(e => { if (e.target === modal[0]) modal.remove(); });

        // Tabs
        modal.find('.tab-btn').click(function() {
            modal.find('.tab-btn').removeClass('active');
            modal.find('.tab-content').removeClass('active');
            $(this).addClass('active');
            modal.find('#tab-' + $(this).data('tab')).addClass('active');
        });

        // Upload Handler
        modal.on('click', '.btn-upload', function() {
            $(this).closest('.upload-item').find('.file-input').click();
        });

        modal.on('change', '.file-input', function() {
            const inputEl = $(this);
            const item = inputEl.closest('.upload-item');
            const field = item.data('field');
            const pid = data.hasilId;
            const status = item.find('.upload-status');
            const file = this.files[0];
            if (!file) return;

            const fd = new FormData();
            fd.append('file', file);
            fd.append('field', field);
            fd.append('hasil_id', pid);

            status.text('Mengunggah...').css('color', '#2563eb').show();

            fetch('{{ route("koordinator.upload.semhas.dokumen") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    const newPath = res.file_path;
                    item.find('.btn-upload').text('Ganti');
                    let hiddenPath = item.find('.hidden-file-path');
                    if (hiddenPath.length === 0) {
                        item.find('.file-actions').append('<div class882="hidden-file-path" style="display:none;" data-path="' + newPath + '"></div>');
                    } else {
                        hiddenPath.data('path', newPath);
                    }
                    item.data('has-file', true);
                    status.text('Berhasil diunggah!').css('color', 'green').show();
                    setTimeout(() => status.fadeOut(), 2000);
                } else {
                    status.text('Gagal: ' + (res.message || 'Error')).css('color', 'red').show();
                }
            })
            .catch(err => {
                console.error(err);
                status.text('Gagal mengunggah').css('color', 'red').show();
            });
        });

        // Simpan (hanya notifikasi)
        modal.find('#simpanBtn').click(() => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Semua perubahan disimpan otomatis.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
            modal.remove();
        });
    });
});
</script>
@endsection

<style>
.table th,
.table td {
    vertical-align: middle !important;
    text-align: left !important;
    padding: 8px 12px !important;
}

.upload-item.uploaded {
    background-color: rgba(34, 197, 94, 0.1);
    border: 1px solid #22c55e;
}

/* Kolom checkbox rata tengah */
.table th:first-child,
.table td:first-child {
    text-align: center !important;
    width: 50px !important;
}

/* Kolom nomor rata tengah */
.table th:nth-child(2),
.table td:nth-child(2) {
    text-align: center !important;
    width: 80px !important;
}

/* Hover efek lembut dan sedikit mengangkat */
.hover-row {
    transition: all 0.25s ease;
}

.hover-row:hover {
    background-color: #f9fafb !important;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.kp-tabs {
    display: flex;
    justify-content: flex-start;
    gap: 10px;
    margin-bottom: 25px;
}

.kp-tab {
    padding: 10px 18px;
    background: #eee;
    color: #444;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
    border: none;
    cursor: pointer;
}

.kp-tab:hover { background: #dcdcdc; }
.kp-tab.active { background: white; box-shadow: 0 3px 6px rgba(0,0,0,0.1); }
.kp-tab.disabled { opacity: 0.5; cursor: not-allowed; }

/* Modal Styles */
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center;
    z-index: 9999; backdrop-filter: blur(4px); animation: fadeIn 0.3s ease-out;
}

.modal-container {
    background: white; border-radius: 16px; width: 95%; max-width: 1000px; max-height: 90vh;
    overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideIn 0.3s ease-out;
}

.modal-header {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white; padding: 20px 24px; display: flex; justify-content: space-between;
    align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.modal-title { font-size: 20px; font-weight: 600; display: flex; align-items: center; }
.modal-close-btn { background: rgba(255,255,255,0.2); border: none; color: white; width: 36px; height: 36px;
    border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; }
.modal-close-btn:hover { background: rgba(255,255,255,0.3); transform: scale(1.1); }

.modal-body { padding: 24px; overflow-y: auto; max-height: calc(90vh - 140px); }
.modal-footer { padding: 20px 24px; border-top: 1px solid #e5e7eb; background: #f9fafb; text-align: right; }

.proposal-info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.info-item { display: flex; align-items: center; gap: 12px; padding: 12px; background: white;
    border-radius: 8px; border: 1px solid #e5e7eb; }
.info-item.full-width { grid-column: span 2; }
.info-icon { color: #1E3A8A; font-size: 20px; flex-shrink: 0; }
.info-content label { font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 2px; }
.info-content span { font-size: 14px; font-weight: 500; color: #1f2937; }

.tabs-container { border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; background: white; }
.tabs-header { display: flex; background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
.tab-btn { flex: 1; background: none; border: none; padding: 16px 20px; font-size: 14px;
    color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; font-weight: 500;
    transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 6px; }
.tab-btn:hover { background: rgba(30,58,138,0.05); color: #1E3A8A; }
.tab-btn.active { color: #1E3A8A; border-bottom: 3px solid #1E3A8A; background: white; }

.tabs-content { padding: 24px; }
.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeIn 0.3s ease-out; }

.doc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
.upload-item { border: 2px dashed #d1d5db; border-radius: 12px; padding: 16px; text-align: center; background: #fafafa; }
.upload-label { font-size: 13px; color: #374151; margin-bottom: 12px; font-weight: 600; display: block; }
.file-actions { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; }

.btn-upload {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white; border: none; padding: 10px 18px; border-radius: 8px;
    font-size: 13px; cursor: pointer; transition: all 0.15s ease;
}
.btn-upload:hover, .btn-replace:hover, .btn-download:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(30,58,138,0.12); }

.btn-download {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    color: white; border: none; padding: 10px 18px; border-radius: 8px;
    font-size: 13px; cursor: pointer; transition: all 0.15s ease;
    display: inline-flex; align-items: center; gap: 6px;
}

.btn-primary {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white; border: none; padding: 12px 24px; border-radius: 8px;
    font-weight: 600; cursor: pointer; transition: all 0.2s ease;
    display: inline-flex; align-items: center; gap: 8px;
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(30,58,138,0.3); }

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideIn { from { opacity: 0; transform: scale(0.9) translateY(-20px); } to { opacity: 1; transform: scale(1) translateY(0); } }

@media (max-width: 768px) {
    .modal-container { width: 98%; margin: 10px; }
    .info-grid { grid-template-columns: 1fr; }
    .info-item.full-width { grid-column: span 1; }
    .tabs-header { flex-direction: column; }
    .doc-grid { grid-template-columns: 1fr; }
}
</style>
@endsection