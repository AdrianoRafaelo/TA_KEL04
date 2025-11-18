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
                    <li class="breadcrumb-item active" aria-current="page">Mahasiswa TA</li>
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
                <a href="{{ route('koordinator.sempro') }}" class="kp-tab active">Seminar Proposal</a>
                <a href="{{ route('koordinator.semhas') }}" class="kp-tab">Seminar Hasil</a>
                <a href="{{ route('koordinator.sidang') }}" class="kp-tab">Sidang Akhir</a>
                <a href="{{ url('/koordinator-skripsi') }}" class="kp-tab ">Unggah Skripsi</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Seminar Proposal
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
                    <th style="width: 420px;">Judul</th>
                    <th style="width: 120px;">Dokumen</th>
                    <th style="width: 90px;">Pembimbing</th>
                    <th style="width: 130px;">Pengulas I</th>
                    <th style="width: 130px;">Pengulas II</th>
                    <th style="width: 150px;">Jadwal Seminar</th>
                    <th style="width: 100px;">Hasil</th>
                </tr>
            </thead>
            <tbody style="font-size:14px;">
                @foreach($seminarProposals as $index => $proposal)
                <tr class="hover-row">
                    <td>
                        <input type="checkbox" class="proposal-checkbox" name="selected_proposals[]" value="{{ $proposal->id }}" {{ $proposal->status == 'approved' ? 'disabled' : '' }}>
                    </td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $proposal->nama }} @if($proposal->nim) ({{ $proposal->nim }}) @endif</td>
                    <td>{{ $proposal->judul }}</td>
                    <td>
                        <i class="bi bi-file-earmark-text me-1"></i>
                        @if($proposal->file_proposal)
                            <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank">Proposal</a>
                        @endif
                        @if($proposal->file_proposal && $proposal->file_persetujuan) | @endif
                        @if($proposal->file_persetujuan)
                            <a href="{{ asset('storage/' . $proposal->file_persetujuan) }}" target="_blank">Persetujuan</a>
                        @endif
                    </td>
                    <td>{{ $proposal->pembimbing }}</td>
                    <td>
                        <select class="form-select form-select-sm pengulas-select" data-field="pengulas_1" data-proposal-id="{{ $proposal->id }}">
                            <option value="">Pilih Dosen</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->nama }}" {{ $proposal->pengulas_1 == $lecturer->nama ? 'selected' : '' }}>
                                    {{ $lecturer->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-select form-select-sm pengulas-select" data-field="pengulas_2" data-proposal-id="{{ $proposal->id }}">
                            <option value="">Pilih Dosen</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->nama }}" {{ $proposal->pengulas_2 == $lecturer->nama ? 'selected' : '' }}>
                                    {{ $lecturer->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        @if($proposal->jadwal_seminar_file)
                            <a href="{{ asset('storage/' . $proposal->jadwal_seminar_file) }}" target="_blank" class="ms-2 jadwal-file-link-{{ $proposal->id }}">Lihat File</a>
                        @endif
                        <div style="margin-top:8px;">
                            <input type="file" class="form-control form-control-sm jadwal-file-input" data-proposal-id="{{ $proposal->id }}" accept=".pdf,.doc,.docx">
                        </div>
                    </td>
                    <td>
                        <button
                            class="btn btn-sm btn-success btn-selengkapnya"
                            data-proposal-id="{{ $proposal->id }}"
                            data-mahasiswa="{{ $proposal->nama }} @if($proposal->nim) ({{ $proposal->nim }}) @endif"
                            data-judul="{{ $proposal->judul }}"
                            data-pembimbing="{{ $proposal->pembimbing }}"
                            data-pengulas1="{{ $proposal->pengulas_1 ?? '' }}"
                            data-pengulas2="{{ $proposal->pengulas_2 ?? '' }}"
                            data-file_proposal="{{ $proposal->file_proposal ? asset('storage/' . $proposal->file_proposal) : '' }}"
                            data-file_persetujuan="{{ $proposal->file_persetujuan ? asset('storage/' . $proposal->file_persetujuan) : '' }}"
                            data-form_persetujuan="{{ $proposal->form_persetujuan ? asset('storage/' . $proposal->form_persetujuan) : '' }}"
                            data-proposal_penelitian="{{ $proposal->proposal_penelitian ? asset('storage/' . $proposal->proposal_penelitian) : '' }}"
                            data-berita_acara_pembimbing="{{ $proposal->berita_acara_pembimbing ? asset('storage/' . $proposal->berita_acara_pembimbing) : '' }}"
                            data-penilaian_pembimbing="{{ $proposal->penilaian_pembimbing ? asset('storage/' . $proposal->penilaian_pembimbing) : '' }}"
                            data-berita_acara_pengulas1="{{ $proposal->berita_acara_pengulas1 ? asset('storage/' . $proposal->berita_acara_pengulas1) : '' }}"
                            data-penilaian_pengulas1="{{ $proposal->penilaian_pengulas1 ? asset('storage/' . $proposal->penilaian_pengulas1) : '' }}"
                            data-berita_acara_pengulas2="{{ $proposal->berita_acara_pengulas2 ? asset('storage/' . $proposal->berita_acara_pengulas2) : '' }}"
                            data-penilaian_pengulas2="{{ $proposal->penilaian_pengulas2 ? asset('storage/' . $proposal->penilaian_pengulas2) : '' }}"
                           data-revisi_dokumen="{{ $proposal->revisi_dokumen ? asset('storage/' . $proposal->revisi_dokumen) : '' }}"
                           data-form_revisi="{{ $proposal->form_revisi ? asset('storage/' . $proposal->form_revisi) : '' }}"
                        >
                            Details
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer Button -->
    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-success me-2" id="approveSelected">Terima Seminar Proposal</button>
        <button class="btn btn-success" id="savePengulas">Simpan</button>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    $('#selectAll').change(function() {
        $('.proposal-checkbox').prop('checked', this.checked);
    });

    // Save Pengulas + upload selected Jadwal Seminar files
    $('#savePengulas').click(function() {
        const updates = [];
        $('.pengulas-select').each(function() {
            const proposalId = $(this).data('proposal-id');
            const field = $(this).data('field');
            const value = $(this).val();
            updates.push({ proposal_id: proposalId, field: field, value: value });
        });

        // Collect selected jadwal files
        const fileInputs = $('.jadwal-file-input');
        const fileUploads = [];
        fileInputs.each(function() {
            const input = this;
            const file = input.files[0];
            if (file) {
                const pid = $(input).data('proposal-id');
                const fd = new FormData();
                fd.append('jadwal_seminar_file', file);
                fd.append('proposal_id', pid);
                fileUploads.push({ formData: fd, proposalId: pid });
            }
        });

        // First save pengulas
        Promise.all(updates.map(update =>
            fetch('{{ route("koordinator.update.sempro.pengulas") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(update)
            }).then(r => r.json())
        ))
        .then(results => {
            if (!results.every(r => r.success)) {
                alert('Gagal menyimpan beberapa pengulas.');
                return;
            }

            // Then upload files (if any)
            if (fileUploads.length === 0) {
                alert('Pengulas berhasil disimpan!');
                return;
            }

            Promise.all(fileUploads.map(item =>
                fetch('{{ route("koordinator.upload.jadwal.seminar") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: item.formData
                }).then(r => r.json())
            ))
            .then(uploadResults => {
                let allOk = true;
                uploadResults.forEach((res, idx) => {
                    const pid = fileUploads[idx].proposalId;
                    if (res.success) {
                        // Update link in table row
                        const selectorLink = '.jadwal-file-link-' + pid;
                        const selectorPlaceholder = '.jadwal-file-placeholder-' + pid;
                        // remove placeholder if exists
                        $(selectorPlaceholder).remove();
                        if ($(selectorLink).length) {
                            $(selectorLink).attr('href', res.file_path);
                        } else {
                            // insert link before the file input
                            const inputEl = $('.jadwal-file-input[data-proposal-id="' + pid + '"]');
                            inputEl.before('<a href="' + res.file_path + '" target="_blank" class="ms-2 jadwal-file-link-' + pid + '">Lihat File</a>');
                        }
                    } else {
                        allOk = false;
                    }
                });

                if (allOk) {
                    alert('Pengulas dan file jadwal berhasil disimpan!');
                } else {
                    alert('Beberapa file gagal diunggah. Periksa kembali.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat mengunggah file jadwal.');
            });
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat menyimpan pengulas.');
        });
    });

    $('#approveSelected').click(function() {
        const selected = $('.proposal-checkbox:checked:not(:disabled)');
        if (selected.length === 0) {
            alert('Pilih setidaknya satu proposal.');
            return;
        }
        const ids = selected.map((i, cb) => cb.value).get();

        const modal = $('<div style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;">' +
            '<div style="background:white;padding:20px;border-radius:8px;max-width:400px;width:90%;">' +
                '<h5>Konfirmasi</h5>' +
                '<p>Yakin menerima ' + ids.length + ' proposal?</p>' +
                '<div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">' +
                    '<button id="cancelBtn" style="padding:8px 16px;border:1px solid #ccc;background:#f8f9fa;border-radius:4px;cursor:pointer;">Batal</button>' +
                    '<button id="confirmBtn" style="padding:8px 16px;background:#28a745;color:white;border:none;border-radius:4px;cursor:pointer;">Ya</button>' +
                '</div>' +
            '</div>' +
        '</div>');
        $('body').append(modal);

        modal.find('#cancelBtn').click(() => modal.remove());
        modal.find('#confirmBtn').click(() => {
            modal.remove();
            fetch('{{ route("koordinator.approve.sempro") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ids })
            })
            .then(r => r.json())
            .then(data => {
                alert(data.message || 'Sukses!');
                location.reload();
            });
        });
    });

    // === MODAL DETAIL + UPLOAD ===
    $('.btn-selengkapnya').click(function() {
        const data = $(this).data();
        const proposalId = data.proposalId;


        const modal = $('<div class="modal-overlay">' +
            '<div class="modal-container">' +
                '<div class="modal-header">' +
                    '<div class="modal-title">' +
                        '<i class="bi bi-file-earmark-text-fill me-2"></i>' +
                        'Detail Seminar Proposal' +
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
                                    '<span>' + data.pembimbing + '</span>' +
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
                            generateTab('pembimbing', ['Berita Acara', 'Penilaian'], ['berita_acara_pembimbing', 'penilaian_pembimbing']) +
                            generateTab('pengulas1', ['Berita Acara', 'Penilaian'], ['berita_acara_pengulas1', 'penilaian_pengulas1']) +
                            generateTab('pengulas2', ['Berita Acara', 'Penilaian'], ['berita_acara_pengulas2', 'penilaian_pengulas2']) +
                            generateTab('mahasiswa', ['File Proposal', 'Form Persetujuan', 'Proposal Penelitian', 'Revisi Dokumen', 'Form Revisi'],
                                ['file_proposal', 'form_persetujuan', 'proposal_penelitian', 'revisi_dokumen', 'form_revisi']) +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                    '<button id="simpanBtn" class="btn-primary">' +
                        '<i class="bi bi-check-circle me-2"></i>Simpan Perubahan' +
                    '</button>' +
                '</div>' +
            '</div>' +
        '</div>');

        function generateTab(tabId, labels, keys) {
            let html = '<div id="tab-' + tabId + '" class="tab-content">';
            html += '<div class="doc-grid">';
            keys.forEach((key, i) => {
                html += '<div class="upload-item" data-field="' + key + '" data-proposal-id="' + proposalId + '">' +
                    '<div class="upload-label">' + labels[i] + '</div>' +
                    '<button class="btn-upload btn-sm">' +
                        '<i class="bi bi-cloud-upload me-1"></i>Upload' +
                    '</button>' +
                    '<input type="file" class="file-input" accept=".pdf,.doc,.docx" style="display:none;">' +
                    '<div class="upload-status text-success small mt-1" style="display:none;"></div>' +
                '</div>';
            });
            html += '</div></div>';
            return html;
        }

        $('body').append(modal);

        // Close
        modal.find('#closeModal').click(() => modal.remove());
        modal.click(e => { if (e.target === modal[0]) modal.remove(); });

        // Tabs
        modal.find('.tab-btn').click(function() {
            modal.find('.tab-btn').removeClass('active');
            modal.find('.tab-content').removeClass('active');
            $(this).addClass('active');
            modal.find('#tab-' + $(this).data('tab')).addClass('active');
        });

        // Upload
        modal.find('.upload-item').each(function() {
            const item = $(this);
            const field = item.data('field');
            const pid = item.data('proposal-id');
            const input = item.find('.file-input');
            const status = item.find('.upload-status');

            item.find('.btn-upload, .btn-replace').click(() => input.click());

            input.change(function() {
                const file = this.files[0];
                if (!file) return;

                const fd = new FormData();
                fd.append('file', file);
                fd.append('field', field);
                fd.append('proposal_id', pid);

                status.text('Mengunggah...').show();

                fetch('{{ route("koordinator.upload.sempro.dokumen") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: fd
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        status.text('Berhasil!').show();
                        // Reset input file
                        input.val('');
                    } else {
                        status.text('Gagal: ' + res.message).css('color', 'red').show();
                    }
                });
            });
        });

        modal.find('#simpanBtn').click(() => {
            alert('Semua perubahan disimpan otomatis.');
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

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease-out;
}

.modal-container {
    background: white;
    border-radius: 16px;
    width: 95%;
    max-width: 1000px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s ease-out;
}

.modal-header {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.modal-title {
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.modal-close-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.modal-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.modal-body {
    padding: 24px;
    overflow-y: auto;
    max-height: calc(90vh - 140px);
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    text-align: right;
}

/* Proposal Info Card */
.proposal-info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.info-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.info-item.full-width {
    grid-column: span 2;
}

.info-icon {
    color: #1E3A8A;
    font-size: 20px;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-content label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.info-content span {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #1f2937;
}

/* Tabs */
.tabs-container {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.tabs-header {
    display: flex;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.tab-btn {
    flex: 1;
    background: none;
    border: none;
    padding: 16px 20px;
    font-size: 14px;
    color: #6b7280;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.tab-btn:hover {
    background: rgba(30, 58, 138, 0.05);
    color: #1E3A8A;
}

.tab-btn.active {
    color: #1E3A8A;
    border-bottom: 3px solid #1E3A8A;
    background: white;
}

.tabs-content {
    padding: 24px;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease-out;
}

/* Document Grid */
.doc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.upload-item {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    background: #fafafa;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.upload-item:hover {
    border-color: #1E3A8A;
    background: rgba(30, 58, 138, 0.02);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.upload-label {
    font-size: 13px;
    color: #374151;
    margin-bottom: 12px;
    font-weight: 600;
    display: block;
}

.btn-upload, .btn-replace, .btn-download {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-upload:hover, .btn-replace:hover, .btn-download:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

.btn-replace {
    background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
    margin-left: 8px;
}

.btn-download {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
}

.file-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.file-link {
    color: #1E3A8A;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
}

.file-link:hover {
    color: #3B82F6;
    text-decoration: underline;
}

.upload-status {
    margin-top: 8px;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    display: none;
}

.upload-status.text-success {
    background: #dcfce7;
    color: #166534;
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .modal-container {
        width: 98%;
        margin: 10px;
    }

    .modal-body {
        padding: 16px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .info-item.full-width {
        grid-column: span 1;
    }

    .tabs-header {
        flex-direction: column;
    }

    .doc-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection