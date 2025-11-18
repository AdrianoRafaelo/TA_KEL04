@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Kerja Praktik</a></li>
                    <li class="breadcrumb-item active">Seminar KP</li>
                </ol>
            </nav>
            <h4 class="mb-0">Seminar Kerja Praktik</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/kerja-praktik-koordinator') }}" class="kp-tab">Mahasiswa KP</a>
                <a href="{{ url('/kerja-praktik-koordinator-pelaksanaan') }}" class="kp-tab">Pelaksanaan KP</a>
                <a href="{{ url('/kerja-praktik-koordinator-seminar') }}" class="kp-tab active">Seminar KP</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Seminar Kerja Praktik
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
                    <th style="width: 200px;">Perusahaan KP</th>
                    <th style="width: 120px;">Laporan KP</th>
                    <th style="width: 90px;">Pembimbing</th>
                    <th style="width: 130px;">Dosen Penguji</th>
                    <th style="width: 150px;">Jadwal Seminar</th>
                </tr>
            </thead>
            <tbody style="font-size:14px;">
                @foreach($seminarKp as $index => $seminar)
                <tr class="hover-row">
                    <td>
                        <input type="checkbox" class="seminar-checkbox" name="selected_seminars[]" value="{{ $seminar->id }}" {{ $seminar->status == 'approved' ? 'disabled' : '' }}>
                    </td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $seminar->nama }} @if($seminar->nim) ({{ $seminar->nim }}) @endif</td>
                    <td>{{ $seminar->perusahaan }}</td>
                    <td>
                        <i class="bi bi-file-earmark-text me-1"></i>
                        @if($seminar->file_laporan_kp)
                            <a href="{{ route('kp.seminar.download', basename($seminar->file_laporan_kp)) }}" target="_blank">Laporan</a>
                        @endif
                    </td>
                    <td>{{ $seminar->pembimbing }}</td>
                    <td>
                        <select class="form-select form-select-sm penguji-select" data-field="penguji" data-seminar-id="{{ $seminar->id }}">
                            <option value="">Pilih Dosen</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->nama }}" {{ $seminar->penguji == $lecturer->nama ? 'selected' : '' }}>
                                    {{ $lecturer->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        @if($seminar->jadwal_seminar_file)
                            <a href="{{ asset('storage/' . $seminar->jadwal_seminar_file) }}" target="_blank" class="ms-2 jadwal-file-link-{{ $seminar->id }}">Lihat File</a>
                        @endif
                        <div style="margin-top:8px;">
                            <input type="file" class="form-control form-control-sm jadwal-file-input" data-seminar-id="{{ $seminar->id }}" accept=".pdf,.doc,.docx">
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer Button -->
    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-success me-2" id="approveSelected">Terima Seminar KP</button>
        <button class="btn btn-success" id="savePenguji">Simpan</button>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    $('#selectAll').change(function() {
        $('.seminar-checkbox').prop('checked', this.checked);
    });

    // Save Penguji + upload selected Jadwal Seminar files
    $('#savePenguji').click(function() {
        const updates = [];
        $('.penguji-select').each(function() {
            const seminarId = $(this).data('seminar-id');
            const field = $(this).data('field');
            const value = $(this).val();
            updates.push({ seminar_id: seminarId, field: field, value: value });
        });

        // Collect selected jadwal files
        const fileInputs = $('.jadwal-file-input');
        const fileUploads = [];
        fileInputs.each(function() {
            const input = this;
            const file = input.files[0];
            if (file) {
                const sid = $(input).data('seminar-id');
                const fd = new FormData();
                fd.append('jadwal_seminar_file', file);
                fd.append('seminar_id', sid);
                fileUploads.push({ formData: fd, seminarId: sid });
            }
        });

        // First save penguji
        Promise.all(updates.map(update =>
            fetch('{{ route("koordinator.update.seminar.penguji") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(update)
            }).then(r => r.json())
        ))
        .then(results => {
            if (!results.every(r => r.success)) {
                alert('Gagal menyimpan beberapa penguji.');
                return;
            }

            // Then upload files (if any)
            if (fileUploads.length === 0) {
                alert('Penguji berhasil disimpan!');
                return;
            }

            Promise.all(fileUploads.map(item =>
                fetch('{{ route("koordinator.upload.jadwal.seminar.kp") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: item.formData
                }).then(r => r.json())
            ))
            .then(uploadResults => {
                let allOk = true;
                uploadResults.forEach((res, idx) => {
                    const sid = fileUploads[idx].seminarId;
                    if (res.success) {
                        // Update link in table row
                        const selectorLink = '.jadwal-file-link-' + sid;
                        const selectorPlaceholder = '.jadwal-file-placeholder-' + sid;
                        // remove placeholder if exists
                        $(selectorPlaceholder).remove();
                        if ($(selectorLink).length) {
                            $(selectorLink).attr('href', res.file_path);
                        } else {
                            // insert link before the file input
                            const inputEl = $('.jadwal-file-input[data-seminar-id="' + sid + '"]');
                            inputEl.before('<a href="' + res.file_path + '" target="_blank" class="ms-2 jadwal-file-link-' + sid + '">Lihat File</a>');
                        }
                    } else {
                        allOk = false;
                    }
                });

                if (allOk) {
                    alert('Penguji dan file jadwal berhasil disimpan!');
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
            alert('Terjadi kesalahan saat menyimpan penguji.');
        });
    });

    $('#approveSelected').click(function() {
        const selected = $('.seminar-checkbox:checked:not(:disabled)');
        if (selected.length === 0) {
            alert('Pilih setidaknya satu seminar.');
            return;
        }
        const ids = selected.map((i, cb) => cb.value).get();

        const modal = $('<div style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;">' +
            '<div style="background:white;padding:20px;border-radius:8px;max-width:400px;width:90%;">' +
                '<h5>Konfirmasi</h5>' +
                '<p>Yakin menerima ' + ids.length + ' seminar?</p>' +
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
            fetch('{{ route("koordinator.approve.seminar.kp") }}', {
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