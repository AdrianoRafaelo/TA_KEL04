@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Page</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">MBKM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Informasi Umum</li>
            </ol>
        </nav>
        <h4 class="mb-2">MBKM</h4>
    </div>
</div>


<div class="row mb-3">
    <div class="col-12">
        <div class="kp-tabs">
            <a href="{{ url('/mbkm/pendaftaran-koordinator') }}" class="kp-tab">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-koordinator')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-koordinator')}}" class="kp-tab active">Seminar MBKM</a>
        </div>
    </div>
</div>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
            Seminar MBKM Koordinator
        </button>
    </div>
</div>

<div class="container-fluid py-4">

<!-- Seminar MBKM Table -->
<div class="table-responsive bg-white shadow-sm rounded p-3 mb-4">
    <h6 class="mb-3 text-primary fw-bold">Seminar MBKM</h6>
    <table class="table align-middle mb-0">
        <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
            <tr>
                <th style="width: 40px;" class="text-center">No.</th>
                <th style="width: 200px;">Mahasiswa</th>
                <th style="width: 250px;">Perusahaan MBKM</th>
                <th style="width: 150px;" class="text-center">Laporan MBKM</th>
                <th style="width: 150px;" class="text-center">Matakuliah Konversi</th>
                <th style="width: 200px;" class="text-center">Jadwal Seminar</th>
            </tr>
        </thead>
        <tbody style="font-size:14px; color:#111;">
            @forelse($seminars as $index => $seminar)
            <tr>
                <td class="text-center">{{ $index + 1 }}.</td>
                <td>{{ $seminar->mahasiswa->nama ?? 'N/A' }}</td>
                <td>{{ $companies[$seminar->mahasiswa_id] ?? 'N/A' }}</td>
                <td class="text-center">
                    @if($seminar->laporan_ekotek_file || $seminar->laporan_pmb_file)
                        <span class="badge bg-success">Ada</span>
                    @else
                        <span class="badge bg-warning">Belum</span>
                    @endif
                </td>
                <td class="text-center">{{ $konversiCounts[$seminar->mahasiswa_id] ?? 0 }}</td>
                <td class="text-center">
                    @if($seminar->jadwal_seminar_file)
                        <a href="{{ asset('storage/' . $seminar->jadwal_seminar_file) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">Download</a>
                    @endif
                    <form class="d-inline upload-form" data-id="{{ $seminar->id }}">
                        <input type="file" class="form-control form-control-sm d-none" id="file-{{ $seminar->id }}" name="jadwal_file" accept=".pdf,.doc,.docx">
                        <button type="button" class="btn btn-sm {{ $seminar->jadwal_seminar_file ? 'btn-outline-primary' : 'btn-primary' }} choose-file" data-id="{{ $seminar->id }}">
                            Choose File
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data seminar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>

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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle choose file button clicks
    document.querySelectorAll('.choose-file').forEach(button => {
        button.addEventListener('click', function() {
            const seminarId = this.getAttribute('data-id');
            document.getElementById('file-' + seminarId).click();
        });
    });

    // Handle file input changes
    document.querySelectorAll('input[name="jadwal_file"]').forEach(input => {
        input.addEventListener('change', function() {
            const seminarId = this.id.replace('file-', '');
            const form = this.closest('.upload-form');
            const formData = new FormData(form);
            formData.append('seminar_id', seminarId);

            // Show loading state
            const button = form.querySelector('.choose-file');
            const originalText = button.textContent;
            button.textContent = 'Uploading...';
            button.disabled = true;

            fetch('/mbkm/upload-jadwal-seminar', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Jadwal seminar berhasil diupload');
                    location.reload(); // Reload to update the table
                } else {
                    alert(data.error || 'Terjadi kesalahan');
                    button.textContent = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupload file');
                button.textContent = originalText;
                button.disabled = false;
            });
        });
    });
});
</script>
@endsection
