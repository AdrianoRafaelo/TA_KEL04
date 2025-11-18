@extends('layouts.app')

@section('title', 'Pelaksanaan Kerja Praktik')

@section('content')
<div class="container-fluid py-3">

    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Kerja Praktik</a></li>
                    <li class="breadcrumb-item active">Pelaksanaan</li>
                </ol>
            </nav>
            <h4 class="mb-0 fw-bold">Pelaksanaan Kerja Praktik</h4>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/kerja-praktik-koordinator') }}" class="kp-tab {{ request()->routeIs('kerja-praktik-koordinator') ? 'active' : '' }}">Mahasiswa KP</a>
                <a href="{{ url('/kerja-praktik-koordinator-pelaksanaan') }}" class="kp-tab active {{ request()->routeIs('kerja-praktik.index') ? 'active' : '' }}">Pelaksanaan KP</a>
                <a href="{{ url('/kerja-praktik-koordinator-seminar') }}" class="kp-tab {{ request()->routeIs('kerja-praktik.seminar') ? 'active' : '' }}">Seminar KP</a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Pelaksanaan Kerja Praktik
            </button>
        </div>
    </div>

    <!-- TABEL -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table align-middle mb-0">
                            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                                <tr>
                                    <th style="width: 80px;" class="text-center">No.</th>
                                    <th style="width: 180px;">Mahasiswa</th>
                                    <th style="width: 200px;">Perusahaan KP</th>
                                    <th style="width: 100px;" class="text-center">Minggu</th>
                                    <th style="width: 120px;" class="text-center">Log Activity</th>
                                    <th style="width: 100px;" class="text-center">Bimbingan</th>
                                    <th style="width: 180px;">Dosen Pembimbing</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelaksanaan_kp as $index => $kp)
                                <tr class="hover-row">
                                    <td class="px-4 py-3 text-muted small">{{ $index + 1 }}.</td>
                                    <td class="px-4 py-3">
                                        <div class="fw-medium">{{ $kp->mahasiswa }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-muted small">
                                        {{ $kp->perusahaan }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small">
                                            W{{ $kp->minggu }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button class="btn btn-sm btn-success btn-details"
                                                data-username="{{ $kp->username ?? '' }}"
                                                data-mahasiswa="{{ $kp->mahasiswa }}"
                                                >
                                            Details
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-success text-white rounded-pill px-2 py-1">
                                            {{ $kp->bimbingan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 small text-muted">
                                        {{ $kp->dosen }}
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
        </table>
    </div>

</div>

<!-- Modal for viewing activities -->
<div class="modal fade" id="activitiesModal" tabindex="-1" aria-labelledby="activitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activitiesModalLabel">Log Activities</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="activitiesContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for previewing documents -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="previewFrame" src="" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <a id="downloadLink" href="" target="_blank" class="btn btn-primary">Download</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    // Handle view activities button click
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-details')) {
            e.preventDefault();
            const btn = e.target.closest('.btn-details');
            const username = btn.getAttribute('data-username');
            const mahasiswaName = btn.getAttribute('data-mahasiswa');

            if (!username) {
                alert('Username mahasiswa tidak ditemukan');
                return;
            }

            // Set modal title
            document.getElementById('activitiesModalLabel').textContent = 'Log Activities - ' + mahasiswaName;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('activitiesModal'));
            modal.show();

            // Load activities
            loadStudentActivities(username);
        }
    });

    function loadStudentActivities(username) {
        const contentDiv = document.getElementById('activitiesContent');

        fetch(`/api/kp-student-log-activities/${username}`, { credentials: 'same-origin' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderActivities(data.data);
                } else {
                    contentDiv.innerHTML = '<div class="alert alert-danger">Gagal memuat data aktivitas</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                contentDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memuat data</div>';
            });
    }

    function renderActivities(activities) {
        const contentDiv = document.getElementById('activitiesContent');

        if (activities.length === 0) {
            contentDiv.innerHTML = '<div class="alert alert-info">Belum ada aktivitas yang dicatat</div>';
            return;
        }

        let html = '<div class="list-group">';
        activities.forEach(activity => {
            const createdDate = new Date(activity.created_at).toLocaleDateString('id-ID');
            const fileLink = activity.file_path && activity.file_exists ?
                `<button class="btn btn-sm btn-outline-primary ms-2" onclick="previewDocument(${activity.id}, '${activity.judul}')">
                    <i class="fas fa-eye"></i> Lihat Dokumen
                </button>` : '';

            html += `
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${activity.judul}</h6>
                        <small class="text-muted">${createdDate}</small>
                    </div>
                    <p class="mb-1">${activity.deskripsi || 'Tidak ada deskripsi'}</p>
                    ${fileLink}
                </div>
            `;
        });
        html += '</div>';

        contentDiv.innerHTML = html;
    }

    // Function to preview document in modal
    window.previewDocument = function(activityId, title) {
        const previewUrl = `/kerja-praktik/view-log-activity/${activityId}`;
        const downloadUrl = `/kerja-praktik/download-log-activity/${activityId}`;

        document.getElementById('previewModalLabel').textContent = 'Preview: ' + title;
        document.getElementById('previewFrame').src = previewUrl;
        document.getElementById('downloadLink').href = downloadUrl;

        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }

    // Optional: Tambahkan sorting sederhana (client-side)
    document.querySelectorAll('th').forEach(th => {
        if (th.querySelector('.fa-sort')) {
            th.style.cursor = 'pointer';
            th.addEventListener('click', () => {
                const table = th.closest('table');
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                const index = Array.from(th.parentElement.children).indexOf(th);
                const isAsc = th.classList.toggle('asc');

                rows.sort((a, b) => {
                    const aText = a.children[index].innerText;
                    const bText = b.children[index].innerText;
                    return isAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
                });

                rows.forEach(row => table.querySelector('tbody').appendChild(row));
            });
        }
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