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
                    <li class="breadcrumb-item active" aria-current="page">Dokumen Skripsi</li>
                </ol>
            </nav>
            <h4 class="mb-0">Dokumen Skripsi</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/koordinator-pendaftaran') }}" class="kp-tab">Pendaftaran Judul</a>
                <a href="{{ url('/koordinator-mahasiswa-ta') }}" class="kp-tab">Mahasiswa TA</a>
                <a href="{{ url('/koordinator-sempro') }}" class="kp-tab">Seminar Proposal</a>
                <a href="{{ url('/koordinator-semhas') }}" class="kp-tab">Seminar Hasil</a>
                <a href="{{ url('/koordinator-sidang') }}" class="kp-tab">Sidang Akhir</a>
                <a href="{{ url('/koordinator-skripsi') }}" class="kp-tab active">Dokumen Skripsi</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
            Dokumen Skripsi Mahasiswa
        </button>
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
                    <th style="width: 120px;">File Skripsi Word</th>
                    <th style="width: 100px;">File Skripsi PDF</th>
                </tr> 
            </thead>
            <tbody style="font-size:14px;">
                @forelse($skripsis as $index => $skripsi)
                <tr class="hover-row">
                    <td><input type="checkbox" class="skripsi-checkbox" name="selected_skripsis[]" value="{{ $skripsi->id }}"></td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $skripsi->nama }} ({{ $skripsi->nim }})</td>
                    <td>{{ $skripsi->judul }}</td>

                    <!-- File Word -->
                    <td>
                        @if($skripsi->file_skripsi_word)
                            <button type="button" class="btn btn-link text-primary p-0" onclick="previewFile('{{ route('storage.file', ['path' => $skripsi->file_skripsi_word]) }}', 'word', '{{ $skripsi->nama }} - Skripsi Word')">
                                <i class="bi bi-file-earmark-word me-1"></i>Lihat File Word
                            </button>
                        @else
                            <span class="text-muted">Belum diunggah</span>
                        @endif
                    </td>

                    <!-- File PDF -->
                    <td>
                        @if($skripsi->file_skripsi_pdf)
                            <button type="button" class="btn btn-link text-danger p-0" onclick="previewFile('{{ route('storage.file', ['path' => $skripsi->file_skripsi_pdf]) }}', 'pdf', '{{ $skripsi->nama }} - Skripsi PDF')">
                                <i class="bi bi-file-earmark-pdf me-1"></i>Lihat File PDF
                            </button>
                        @else
                            <span class="text-muted">Belum diunggah</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada data skripsi mahasiswa</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-success" id="downloadSelected">Download Terpilih</button>
    </div>
</div>

<!-- Modal Preview File -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <div id="filePreviewContainer" class="w-100 h-100">
                    <!-- File preview akan dimuat di sini -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="downloadBtn">Download File</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
@section('scripts')
<script>
let currentFileUrl = '';

function previewFile(fileUrl, fileType, title) {
    currentFileUrl = fileUrl;
    $('#previewModalLabel').text(title);
    $('#downloadBtn').attr('onclick', `window.open('${fileUrl}', '_blank')`);

    const container = $('#filePreviewContainer');
    container.empty();

    if (fileType === 'pdf') {
        // Preview PDF menggunakan iframe
        container.html(`
            <iframe src="${fileUrl}"
                    style="width: 100%; height: 100%; border: none;"
                    title="PDF Preview">
                <p>Browser Anda tidak mendukung preview PDF.
                   <a href="${fileUrl}" target="_blank">Download file</a>
                </p>
            </iframe>
        `);
    } else if (fileType === 'word') {
        // Preview Word menggunakan Google Docs Viewer
        const encodedUrl = encodeURIComponent(fileUrl);
        container.html(`
            <iframe src="https://docs.google.com/gview?url=${encodedUrl}&embedded=true"
                    style="width: 100%; height: 100%; border: none;"
                    title="Word Preview">
                <p>Browser Anda tidak mendukung preview dokumen.
                   <a href="${fileUrl}" target="_blank">Download file</a>
                </p>
            </iframe>
        `);
    }

    $('#previewModal').modal('show');
}

$(document).ready(function(){
    // Pilih semua
    $('#selectAll').change(function(){
        $('.skripsi-checkbox').prop('checked', this.checked);
    });

    // Download file terpilih
    $('#downloadSelected').click(function(){
        const selected = $('.skripsi-checkbox:checked');
        if (selected.length === 0) {
            alert('Pilih setidaknya satu skripsi untuk didownload.');
            return;
        }

        // Untuk sementara, tampilkan alert dengan daftar file yang dipilih
        let message = 'File yang akan didownload:\n\n';
        selected.each(function(index){
            const row = $(this).closest('tr');
            const nama = row.find('td:nth-child(3)').text();
            const judul = row.find('td:nth-child(4)').text();
            message += `${index + 1}. ${nama} - ${judul}\n`;
        });

        message += '\nFitur download massal akan diimplementasikan.';
        alert(message);
    });
});
</script>
@endsection

<style>

.table-responsive {
    background: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    border-radius: 8px;
    padding: 0 !important; /* hilangkan padding bawaan */
    margin: 0 !important;
    /* Supaya garis tidak sampai ujung */
    padding-left: 15px;
    padding-right: 15px;
}

.table {
    margin-bottom: 0 !important;
}

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

/* Hilangkan border bawaan */
table, th, td {
    border: none !important;
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

/* Garis pembatas antar mahasiswa */
tbody tr:not(:last-child) {
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

/* Modal Preview Styles */
.modal-xl {
    max-width: 90vw !important;
}

#previewModal .modal-body {
    padding: 0;
}

#filePreviewContainer iframe {
    border-radius: 0.375rem;
}

#previewModal .modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1rem;
}

</style>
@endsection
