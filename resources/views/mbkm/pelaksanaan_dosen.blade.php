@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">MBKM</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pelaksanaan</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/mbkm/dosen-konversi-matkul') }}" class="kp-tab">Konversi Matakuliah</a>
                <a href="{{ url('/mbkm/dosen-pelaksanaan')}}" class="kp-tab active">Pelaksanaan MBKM</a>
                <a href="{{ url('/mbkm/dosen-seminar')}}" class="kp-tab">Seminar MBKM</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Pelaksanaan MBKM Dosen
            </button>
        </div>
    </div>

    <div class="container-fluid py-4">

    <!-- MBKM non-Pertukaran Pelajar / Konversi Matakuliah -->
    <div class="table-responsive bg-white shadow-sm rounded p-3 mb-4">
        <h6 class="mb-3 text-primary fw-bold">MBKM non-Pertukaran Pelajar / Konversi Matakuliah</h6>
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;" class="text-center">No.</th>
                    <th style="width: 200px;">Mahasiswa</th>
                    <th style="width: 250px;">Perusahaan MBKM</th>
                    <th style="width: 100px;" class="text-center">Minggu</th>
                    <th style="width: 120px;">Matkul</th>
                    <th style="width: 250px;">Deskripsi Kegiatan</th>
                    <th style="width: 150px;">Bimbingan</th>
                </tr>
            </thead>
            <tbody style="font-size:14px; color:#111;">
                @forelse($logData as $index => $log)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $log->mahasiswa->nama ?? 'N/A' }}</td>
                    <td>{{ $companies[$log->mahasiswa_id] ?? 'N/A' }}</td>
                    <td class="text-center">W{{ $log->minggu }}</td>
                    <td>{{ $log->matkul }}</td>
                    <td>{{ $log->deskripsi_kegiatan }}</td>
                    <td>{{ $log->bimbingan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data log activity.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mahasiswa Bimbingan -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <h6 class="mb-3 text-primary fw-bold">Mahasiswa Bimbingan</h6>
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
                <tr>
                    <th style="width: 40px;" class="text-center">No.</th>
                    <th style="width: 250px;">Mahasiswa</th>
                    <th style="width: 350px;">Lokasi MBKM</th>
                    <th style="width: 150px;" class="text-center">Selengkapnya</th>
                </tr>
            </thead>
            <tbody style="font-size:14px; color:#111;">
                @forelse($bimbinganStudents as $index => $student)
                <tr class="hover-row">
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td class="fw-semibold">{{ $student['nama'] }}</td>
                    <td>{{ $student['lokasi_mbkm'] }}</td>
                    <td class="text-center">
                        <button class="btn btn-success btn-sm rounded-pill px-4 shadow-sm lihat-pelaksanaan"
                                style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
                                       border: none; font-weight: 500;"
                                data-nama="{{ $student['nama'] }}"
                                data-pelaksanaans="{{ $student['pelaksanaans']->toJson() }}">
                            Lihat
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada mahasiswa bimbingan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>

<!-- Modal for Pelaksanaan Details -->
<div class="modal fade" id="pelaksanaanModal" tabindex="-1" aria-labelledby="pelaksanaanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pelaksanaanModalLabel">Pelaksanaan MBKM - <span id="studentName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Minggu</th>
                            <th>Deskripsi Kegiatan</th>
                            <th>Bimbingan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pelaksanaanTableBody">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.lihat-pelaksanaan').forEach(button => {
        button.addEventListener('click', function() {
            const nama = this.getAttribute('data-nama');
            const pelaksanaans = JSON.parse(this.getAttribute('data-pelaksanaans'));

            document.getElementById('studentName').textContent = nama;

            const tbody = document.getElementById('pelaksanaanTableBody');
            tbody.innerHTML = '';

            if (pelaksanaans.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Belum ada log aktivitas.</td></tr>';
            } else {
                pelaksanaans.forEach(pelaksanaan => {
                    const row = document.createElement('tr');
                    let statusBadge = '';
                    let actionButtons = '';

                    if (pelaksanaan.status === 'approved') {
                        statusBadge = '<span class="badge bg-success">Disetujui</span>';
                        actionButtons = '<span class="text-muted">Sudah disetujui</span>';
                    } else if (pelaksanaan.status === 'rejected') {
                        statusBadge = '<span class="badge bg-danger">Ditolak</span>';
                        actionButtons = '<span class="text-muted">Sudah ditolak</span>';
                    } else {
                        statusBadge = '<span class="badge bg-warning">Pending</span>';
                        actionButtons = `
                            <button class="btn btn-success btn-sm me-1 approve-btn" data-id="${pelaksanaan.id}">Terima</button>
                            <button class="btn btn-danger btn-sm reject-btn" data-id="${pelaksanaan.id}">Tolak</button>
                        `;
                    }

                    row.innerHTML = `
                        <td>W${pelaksanaan.minggu}</td>
                        <td>${pelaksanaan.deskripsi_kegiatan}</td>
                        <td>${pelaksanaan.bimbingan || '-'}</td>
                        <td>${statusBadge}</td>
                        <td>${actionButtons}</td>
                    `;
                    tbody.appendChild(row);
                });
            }

            const modal = new bootstrap.Modal(document.getElementById('pelaksanaanModal'));
            modal.show();
        });
    });

    // Event delegation for approve and reject buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('approve-btn')) {
            const id = e.target.getAttribute('data-id');
            if (confirm('Apakah Anda yakin ingin menyetujui pelaksanaan ini?')) {
                fetch(`/mbkm/pelaksanaan/approve/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload to update the modal
                    } else {
                        alert(data.error || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses permintaan');
                });
            }
        }

        if (e.target.classList.contains('reject-btn')) {
            const id = e.target.getAttribute('data-id');
            if (confirm('Apakah Anda yakin ingin menolak pelaksanaan ini?')) {
                fetch(`/mbkm/pelaksanaan/reject/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload to update the modal
                    } else {
                        alert(data.error || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses permintaan');
                });
            }
        }

    });
});
</script>

@endsection
<!-- Tambahan CSS -->
<style>
    /* Hilangkan border bawaan */
    table, th, td {
        border: none !important;
    }

    /* Garis pembatas antar mahasiswa */
    tbody tr:not(:last-child) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
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

    /* Supaya garis tidak sampai ujung */
    .table-responsive {
        padding-left: 15px;
        padding-right: 15px;
    }

    .btn-success {
        transition: all 0.25s ease;
    }
    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4) !important;
    }
</style>