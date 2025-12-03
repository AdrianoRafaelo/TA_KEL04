<?php

use App\Models\KpRequest;
use App\Models\KpGroup;

$permohonan_requests = KpRequest::with(['company', 'mahasiswa'])->where('type', 'permohonan')->get();

// Load groups for each request
$permohonan_requests->each(function ($request) {
    $request->group = KpGroup::where('active', true)->whereJsonContains('mahasiswa', $request->mahasiswa_id)->first();
});

// Load groups for pengantar requests
$pengantar_requests->each(function ($request) {
    $request->group = KpGroup::where('active', true)->whereJsonContains('mahasiswa', $request->mahasiswa_id)->first();
});
?>

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
                    <li class="breadcrumb-item active" aria-current="page">Mahasiswa KP</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kp-tabs d-flex flex-wrap gap-2">
                <a href="{{ url('/kerja-praktik-koordinator') }}" class="kp-tab active {{ request()->routeIs('kerja-praktik-koordinator') ? 'active' : '' }}">Mahasiswa KP</a>
                <a href="{{ url('/kerja-praktik-koordinator-pelaksanaan') }}" class="kp-tab {{ request()->routeIs('kerja-praktik.index') ? 'active' : '' }}">Pelaksanaan KP</a>
                <a href="{{ url('/kerja-praktik-koordinator-seminar') }}" class="kp-tab {{ request()->routeIs('kerja-praktik.seminar') ? 'active' : '' }}">Seminar KP</a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
                Kerja Praktik
            </button>
        </div>
    </div>

<!-- Tabel Permohonan KP -->
@if($permohonan_requests->count() > 0)
    <!-- Table -->
    <div class="table-responsive bg-white shadow-sm rounded p-3 mb-4">
        <table class="table align-middle mb-0">
            <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
              <tr>
                <th style="width: 80px;" class="text-center">No.</th>
                <th style="width: 200px;">Mahasiswa</th>
                <th style="width: 250px;">Perusahaan KP</th>
                <th style="width: 150px;">Alamat</th>
                <th style="width: 150px;">Waktu KP</th>
                <th style="width: 100px;" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody style="font-size:14px;">
              @forelse($permohonan_requests as $index => $request)
              <tr class="hover-row">
                <td class="text-center">{{ $loop->iteration }}.</td>
                <td>
                    @if($request->group && $request->group->mahasiswa() && $request->group->mahasiswa()->count() > 0)
                        @foreach($request->group->mahasiswa() as $anggota)
                            <div>{{ $anggota->nama }} ({{ $anggota->nim }})</div>
                        @endforeach
                    @else
                        <div class="text-muted">Belum ada anggota</div>
                    @endif
                </td>
                <td>{{ $request->company->nama_perusahaan ?? 'N/A' }}</td>
                <td class="text-truncate" style="max-width: 150px;">
                  {{ $request->company->alamat_perusahaan ?? 'N/A' }}
                </td>
                <td>
                  {{ \Carbon\Carbon::parse($request->company->waktu_awal_kp)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($request->company->waktu_selesai_kp)->format('d/m/Y') }}
                </td>
                <td class="text-center">
                  @if($request->status == 'pending')
                    <form method="POST" action="{{ route('kerja-praktik-koordinator.approve-permohonan') }}" class="d-inline">
                      @csrf
                      <input type="hidden" name="request_id" value="{{ $request->id }}">
                      <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                        Setujui
                      </button>
                    </form>
                    <form method="POST" action="{{ route('kerja-praktik-koordinator.reject-permohonan') }}" class="d-inline ms-1">
                      @csrf
                      <input type="hidden" name="request_id" value="{{ $request->id }}">
                      <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                        Tolak
                      </button>
                    </form>                      
                  @elseif($request->status == 'approved')
                    <span class="badge bg-success">Disetujui</span>
                  @elseif($request->status == 'rejected')
                    <span class="badge bg-danger">Ditolak</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    Belum ada permintaan surat permohonan KP.
                </td>
              </tr>
              @endforelse
            </tbody>
        </table>
    </div>
@endif
<!-- Tabel Mahasiswa Kerja Praktik -->
<div class="table-responsive bg-white shadow-sm rounded p-3">
    <table class="table align-middle mb-0">
        <thead style="background-color:#f8f9fa; color:#555; font-size:12px; text-transform:uppercase; border-bottom: 2px solid #b3743b;">
            <tr>
                <th class="text-center" style="width: 60px;">No.</th>
                <th style="width: 180px;">Mahasiswa</th>
                <th style="width: 200px;">Perusahaan KP</th>
                <th style="width: 150px;">Alamat</th>
                <th style="width: 140px;">Divisi</th>
                <th style="width: 200px;">Dosen Pembimbing</th>
                <th class="text-center" style="width: 100px;">Aksi</th>
            </tr>
        </thead>

        <tbody style="font-size:14px;">
            @forelse($pengantar_requests as $index => $request)
            <tr class="hover-row">
                <td class="text-center">{{ $loop->iteration }}.</td>

                <td>
                    @if($request->group && $request->group->mahasiswa() && $request->group->mahasiswa()->count() > 0)
                        @foreach($request->group->mahasiswa() as $anggota)
                            <div>{{ $anggota->nama }} ({{ $anggota->nim }})</div>
                        @endforeach
                    @else
                        <div class="text-muted">Belum ada anggota</div>
                    @endif
                </td>

                <td>{{ $request->company->nama_perusahaan ?? 'N/A' }}</td>

                <td class="text-truncate" style="max-width: 150px;">
                    {{ $request->company->alamat_perusahaan ?? 'N/A' }}
                </td>

                <!-- Kolom Divisi -->
                <td>
                    @if($request->status == 'assigned' || $request->status == 'approved')
                        {{ $request->divisi ?? 'N/A' }}
                    @else
                        <input 
                            type="text" 
                            name="divisi" 
                            class="form-control form-control-sm" 
                            placeholder="Divisi"
                            form="form-assign-{{ $request->id }}"
                            required
                        >
                    @endif
                </td>

                <!-- Kolom Dosen -->
                <td>
                    @if($request->status == 'assigned' || $request->status == 'approved')
                        <span>{{ $request->dosen->nama ?? 'N/A' }}</span>
                    @else
                        <select 
                            name="dosen_id" 
                            class="form-select form-select-sm"
                            form="form-assign-{{ $request->id }}"
                            required>
                            <option value="">Pilih Dosen</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->nama }}</option>
                            @endforeach
                        </select>
                    @endif
                </td>

                <!-- Kolom Aksi -->
                <td class="text-center">

                    @if($request->status == 'assigned')
                        <form method="POST" action="{{ route('kerja-praktik-koordinator.approve-pengantar') }}">
                            @csrf
                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                            <button class="btn btn-success btn-sm rounded-pill px-3">Approve</button>
                        </form>

                    @elseif($request->status == 'approved')
                        <span class="badge bg-success">Disetujui</span>

                    @else
                        <form 
                            id="form-assign-{{ $request->id }}" 
                            method="POST" 
                            action="{{ route('kerja-praktik-koordinator.assign-dosen') }}">
                            @csrf
                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                            <button class="btn btn-success btn-sm rounded-pill px-3 mt-2">Simpan</button>
                        </form>
                    @endif
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    Belum ada permintaan surat pengantar KP.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
function editPermohonan(id) {
    // Implement edit functionality, e.g., redirect to edit page or open modal
    window.location.href = '{{ url("/kerja-praktik-koordinator/edit-permohonan") }}/' + id;
}

function deletePermohonan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus permintaan ini?')) {
        // Implement delete functionality, e.g., AJAX call or form submission
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("/kerja-praktik-koordinator/delete-permohonan") }}/' + id;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
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
</style>
