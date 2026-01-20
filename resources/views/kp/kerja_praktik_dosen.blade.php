@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">Kerja Praktik</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pelaksanaan KP</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="#" class="kp-tab active">Pelaksanaan KP</a>
                <a href="{{ url('/kerja-praktik-dosen-seminar') }}" class="kp-tab" disabled>Seminar KP</a>
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

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Pelaksanaan KP
            </button>
        </div>
    </div>

    

    <!-- Table -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 80px;">No.</th>
                    <th style="width: 200px;">Mahasiswa</th>
                    <th style="width: 250px;">Perusahaan KP</th>
                    <th style="width: 120px;">Detail</th>
                </tr>
            </thead>
            <tbody style="font-size:14px;">
                @forelse($supervisedStudents as $index => $group)
                <tr class="hover-row">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @foreach($group['students'] as $student)
                            <div class="fw-600">{{ $student->nama }}</div>
                            @if(!empty($student->group_members))
                                @foreach($student->group_members as $member)
                                    <div class="text-muted small">{{ $member }}</div>
                                @endforeach
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $group['company']->nama_perusahaan ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-sm btn-success btn-details"
                                data-group-id="{{ $index }}"
                                data-mahasiswa="@foreach($group['students'] as $student){{ $student->nama }} @endforeach"
                                data-perusahaan="{{ $group['company']->nama_perusahaan ?? 'N/A' }}"
                                data-students='@json($group["students"])'
                        >
                            Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        Belum ada mahasiswa bimbingan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer Button -->
    <!-- <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-success" id="sendInfo">Kirim Informasi</button>
    </div> -->
</div>

@section('scripts')
<script>
$(document).ready(function() {
    $('#selectAll').change(function() {
        $('.proposal-checkbox').prop('checked', this.checked);
    });

    // Modal for details
    $('.btn-details').click(function() {
        const data = $(this).data();
        const students = data.students;

        // Generate log activity content
        let logContent = '<div class="row g-3">';
        if (students && students.length > 0) {
            students.forEach(student => {
                if (student.aktivitas && student.aktivitas.length > 0) {
                    logContent += '<div class="col-12"><h6 class="fw-bold text-primary">' + student.nama + '</h6>';
                    student.aktivitas.forEach(aktivitas => {
                        const detailId = 'detail-' + aktivitas.id;
                        let actionButtons = '';
                        if (aktivitas.status === 'menunggu') {
                            actionButtons = '<div class="d-flex gap-1 mt-2">' +
                                '<button class="btn btn-success btn-sm" onclick="approveAktivitas(' + aktivitas.id + ', \'approve\')">Terima</button>' +
                                '<button class="btn btn-danger btn-sm" onclick="approveAktivitas(' + aktivitas.id + ', \'reject\')">Tolak</button>' +
                            '</div>';
                        }
                        logContent += '<div class="mb-3 p-3 bg-white rounded-3 shadow-sm log-item" onclick="toggleLogDetail(\'' + detailId + '\')" style="cursor: pointer;">' +
                            '<div class="d-flex align-items-center justify-content-between">' +
                                '<h6 class="fw-bold mb-0 text-primary" style="font-size: 0.95rem;">' + aktivitas.judul + '</h6>' +
                                '<i class="fas fa-chevron-down toggle-icon" id="icon-' + detailId + '"></i>' +
                            '</div>' +
                            '<div class="log-detail" id="' + detailId + '" style="display: none; margin-top: 10px;">' +
                                '<div class="border-top pt-2 mt-2">' +
                                    '<p class="small text-muted mb-1" style="font-size: 0.85rem;">' + (aktivitas.deskripsi || 'tidak ada') + '</p>' +
                                    '<small class="text-muted" style="font-size: 0.75rem;">' + new Date(aktivitas.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '</small>' +
                                    actionButtons +
                                '</div>' +
                            '</div>' +
                        '</div>';
                    });
                    logContent += '</div>';
                }
            });
        }
        if (logContent === '<div class="row g-3">') {
            logContent += '<div class="col-12 text-center py-4"><i class="fas fa-list fa-3x text-muted mb-3"></i><p class="text-muted">Belum ada log aktivitas</p></div>';
        }
        logContent += '</div>';

        // Generate topik khusus content
        let topikContent = '';
        if (students && students.length > 0) {
            students.forEach(student => {
                if (student.topik_khusus && student.topik_khusus.length > 0) {
                    topikContent += '<div class="mb-3"><h6 class="fw-bold text-primary">' + student.nama + '</h6>';
                    student.topik_khusus.forEach(topik => {
                        const statusClass = topik.status === 'diterima' ? 'bg-success' : (topik.status === 'ditolak' ? 'bg-danger' : 'bg-warning');
                        const statusText = topik.status.charAt(0).toUpperCase() + topik.status.slice(1);
                        let actionButtons = '';
                        if (topik.status === 'menunggu') {
                            actionButtons = '<div class="d-flex gap-1 mt-2">' +
                                '<button class="btn btn-success btn-sm" onclick="approveTopik(' + topik.id + ', \'approve\')">Terima</button>' +
                                '<button class="btn btn-danger btn-sm" onclick="approveTopik(' + topik.id + ', \'reject\')">Tolak</button>' +
                            '</div>';
                        }
                        topikContent += '<div class="border rounded-3 p-3 bg-light mb-2">' +
                            '<div class="d-flex justify-content-between align-items-start">' +
                                '<p class="mb-0 small text-dark">' + topik.topik + '</p>' +
                                '<span class="badge ' + statusClass + ' text-white px-3 py-2 rounded-pill small ms-3">' + statusText + '</span>' +
                            '</div>' + actionButtons +
                        '</div>';
                    });
                    topikContent += '</div>';
                }
            });
        }
        if (topikContent === '') {
            topikContent = '<div class="text-center py-4"><i class="fas fa-lightbulb fa-3x text-muted mb-3"></i><p class="text-muted">Belum ada topik khusus</p></div>';
        }

        // Generate bimbingan content
        let bimbinganContent = '';
        if (students && students.length > 0) {
            students.forEach(student => {
                if (student.bimbingan && student.bimbingan.length > 0) {
                    bimbinganContent += '<div class="mb-3"><h6 class="fw-bold text-primary">' + student.nama + '</h6>';
                    student.bimbingan.forEach(bimbingan => {
                        const jenisText = bimbingan.jenis === 'sebelum_kp' ? 'Sebelum KP' : 'Sesudah KP';
                        const statusClass = bimbingan.status === 'diterima' ? 'bg-success' : (bimbingan.status === 'ditolak' ? 'bg-danger' : 'bg-warning');
                        const statusText = bimbingan.status.charAt(0).toUpperCase() + bimbingan.status.slice(1);
                        let actionButtons = '';
                        if (bimbingan.status === 'menunggu') {
                            actionButtons = '<div class="d-flex gap-1 mt-2">' +
                                '<button class="btn btn-success btn-sm" onclick="approveBimbingan(' + bimbingan.id + ', \'approve\')">Terima</button>' +
                                '<button class="btn btn-danger btn-sm" onclick="approveBimbingan(' + bimbingan.id + ', \'reject\')">Tolak</button>' +
                            '</div>';
                        }
                        bimbinganContent += '<div class="border rounded-3 p-3 bg-light mb-2">' +
                            '<div class="d-flex justify-content-between align-items-start mb-2">' +
                                '<div>' +
                                    '<strong>' + bimbingan.topik + '</strong><br>' +
                                    '<small class="text-muted">' + new Date(bimbingan.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + '</small>' +
                                '</div>' +
                                '<div class="d-flex gap-2">' +
                                    '<span class="badge bg-light text-dark px-2 py-1 rounded-pill small">' + jenisText + '</span>' +
                                    '<span class="badge ' + statusClass + ' text-white px-2 py-1 rounded-pill small">' + statusText + '</span>' +
                                '</div>' +
                            '</div>' + actionButtons +
                        '</div>';
                    });
                    bimbinganContent += '</div>';
                }
            });
        }
        if (bimbinganContent === '') {
            bimbinganContent = '<div class="text-center py-4"><i class="fas fa-comments fa-3x text-muted mb-3"></i><p class="text-muted">Belum ada data bimbingan</p></div>';
        }

        const modal = $('<div class="modal-overlay">' +
            '<div class="modal-container">' +
                '<div class="modal-header">' +
                    '<div class="modal-title">' +
                        '<i class="bi bi-file-earmark-text-fill me-2"></i>' +
                        'Detail KP' +
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
                                '<i class="bi bi-building-fill info-icon"></i>' +
                                '<div class="info-content">' +
                                    '<label>Perusahaan</label>' +
                                    '<span>' + data.perusahaan + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="tabs-container">' +
                        '<div class="tabs-header">' +
                            '<button class="tab-btn active" data-tab="log">' +
                                '<i class="bi bi-calendar me-1"></i>Log Aktivitas' +
                            '</button>' +
                            '<button class="tab-btn" data-tab="topik">' +
                                '<i class="bi bi-lightbulb me-1"></i>Topik Khusus' +
                            '</button>' +
                            '<button class="tab-btn" data-tab="bimbingan">' +
                                '<i class="bi bi-chat me-1"></i>Bimbingan' +
                            '</button>' +
                        '</div>' +
                        '<div class="tabs-content">' +
                            '<div id="tab-log" class="tab-content active">' + logContent + '</div>' +
                            '<div id="tab-topik" class="tab-content">' + topikContent + '</div>' +
                            '<div id="tab-bimbingan" class="tab-content">' + bimbinganContent + '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>');

        $('body').append(modal);

        modal.find('#closeModal').click(() => modal.remove());
        modal.click(e => { if (e.target === modal[0]) modal.remove(); });

        modal.find('.tab-btn').click(function() {
            modal.find('.tab-btn').removeClass('active');
            modal.find('.tab-content').removeClass('active');
            $(this).addClass('active');
            modal.find('#tab-' + $(this).data('tab')).addClass('active');
        });
    });

    $('#sendInfo').click(function() {
        $('#informasiKpModal').modal('show');
    });

    // Approve bimbingan
    window.approveBimbingan = function(id, action) {
        fetch('{{ route("kp.bimbingan.approve") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: id, action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status bimbingan berhasil diperbarui');
                location.reload();
            } else {
                alert('Kesalahan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui status');
        });
    };

    // Approve topik khusus
    window.approveTopik = function(id, action) {
        fetch('{{ route("kp.topik-khusus.approve") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: id, action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status topik khusus berhasil diperbarui');
                location.reload();
            } else {
                alert('Kesalahan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui status');
        });
    };

    // Approve aktivitas
    window.approveAktivitas = function(id, action) {
        fetch('{{ route("kp.aktivitas.approve") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: id, action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status aktivitas berhasil diperbarui');
                location.reload();
            } else {
                alert('Kesalahan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui status');
        });
    };

    // Toggle log detail
    window.toggleLogDetail = function(detailId) {
        const detail = document.getElementById(detailId);
        const icon = document.getElementById('icon-' + detailId);

        if (detail.style.display === 'none') {
            detail.style.display = 'block';
            icon.className = 'fas fa-chevron-up toggle-icon';
        } else {
            detail.style.display = 'none';
            icon.className = 'fas fa-chevron-down toggle-icon';
        }
    };
});
</script>
@endsection

{{-- ==================================== MODAL INFORMASI UMUM (di dalam file yang sama) ==================================== --}}
<div class="modal fade" id="informasiKpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informasi Kegiatan Kerja Praktik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('kp.informasi.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi Informasi</label>
                        <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-primary fw-bold mb-3">Mahasiswa Bimbingan (Lampiran & Expired)</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No.</th>
                                            <th>Mahasiswa</th>
                                            <th>Dokumen Lampiran</th>
                                            <th>Expired</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($supervisedStudents as $index => $group)
                                            <tr>
                                                <td>{{ $loop->iteration }}.</td>
                                                <td>
                                                    @foreach($group['students'] as $student)
                                                        <div>{{ $student->nama }}</div>
                                                        @foreach($student->group_members ?? [] as $member)
                                                            <small class="text-muted d-block">{{ $member }}</small>
                                                        @endforeach
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <input type="file" class="form-control form-control-sm"
                                                           name="dokumen_{{ $loop->iteration }}" accept=".pdf,.doc,.docx">
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control form-control-sm"
                                                           name="expired_{{ $loop->iteration }}">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Kirim Informasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .gambar {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* Rasio aspek 16:9 */
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
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

.kp-tabs .kp-tab {
    background: #e9ecef;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    color: #555;
}

.kp-tabs .kp-tab.active {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
    font-weight: 600;
}

.kp-tabs .kp-tab:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
}
</style>
@endsection
