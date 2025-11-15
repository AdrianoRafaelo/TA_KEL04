\@extends('layouts.app')

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">Kerja Praktik</a></li>
        <li class="breadcrumb-item active" aria-current="page">Mahasiswa KP</li>
      </ol>
    </nav>
    <h4 class="mb-2">Mahasiswa KP</h4>
  </div>
</div>

<!-- Tabs -->
<div class="row mb-3">
  <div class="col-12">
    <div class="kp-tabs">
      <a href="{{ url('/kerja-praktik-koordinator') }}" class="kp-tab {{ request()->routeIs('kerja-praktik-koordinator') ? 'active' : '' }}">Mahasiswa KP</a>
      <a href="{{ url('/kerja-praktik-koordinator-pelaksanaan') }}" class="kp-tab {{ request()->routeIs('kerja-praktik.index') ? 'active' : '' }}">Pelaksanaan KP</a>
      <a href="{{ url('/kerja-praktik/seminar') }}" class="kp-tab {{ request()->routeIs('kerja-praktik.seminar') ? 'active' : '' }}">Seminar KP</a>
    </div>
  </div>
</div>

<!-- Banner dengan 3 Gambar -->
<div class="row mb-3">
  <div class="col-12">
    <div class="d-flex overflow-auto gap-3 p-2" style="scrollbar-width: none; -ms-overflow-style: none;">
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-1.jpg') }}" class="img-fluid rounded" alt="Banner 1">

      </div>
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-2.jpg') }}" class="img-fluid rounded" alt="Banner 2">

      </div>
      <div class="flex-shrink-0 position-relative" style="width: 320px;">
        <img src="{{ asset('assets/images/kp-banner-3.jpg') }}" class="img-fluid rounded" alt="Banner 3">

      </div>
    </div>
  </div>
</div>

<!-- Tabel Permohonan KP -->
@if($permohonan_requests->count() > 0)
<div class="row mb-4">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="p-3 border-bottom">
          <h6 class="kp-list-title mb-0 text-primary fw-bold">Permohonan KP</h6>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th width="5%" class="text-center">No.</th>
                <th>Mahasiswa</th>
                <th>Perusahaan KP</th>
                <th width="15%">Alamat</th>
                <th width="15%">Waktu KP</th>
                <th width="10%" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($permohonan_requests as $index => $request)
              <tr>
                <td class="text-center">{{ $loop->iteration }}.</td>
                <td>
                  <div class="fw-bold">{{ $request->mahasiswa->nama ?? 'N/A' }}</div>
                  <div class="text-muted small">{{ $request->company->tahun_ajaran ?? 'N/A' }}</div>
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
      </div>
    </div>
  </div>
</div>
@endif

<!-- Tabel Mahasiswa Kerja Praktik -->
<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="p-3 border-bottom">
          <h6 class="kp-list-title mb-0 text-primary fw-bold">Mahasiswa Kerja Praktik</h6>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th width="5%" class="text-center">No.</th>
                <th>Mahasiswa</th>
                <th>Perusahaan KP</th>
                <th width="15%">Alamat</th>
                <th width="12%">Divisi</th>
                <th width="18%">Dosen Pembimbing</th>
                <th width="10%" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pengantar_requests as $index => $request)
              <tr>
                <td class="text-center">{{ $loop->iteration }}.</td>
                <td>
                  <div class="fw-bold">{{ $request->mahasiswa->nama ?? 'N/A' }}</div>
                  <div class="text-muted small">{{ $request->supervisor->nama_supervisor ?? 'N/A' }}</div>
                </td>
                <td>{{ $request->company->nama_perusahaan ?? 'N/A' }}</td>
                <td class="text-truncate" style="max-width: 150px;">
                  {{ $request->company->alamat_perusahaan ?? 'N/A' }}
                </td>
                <td colspan="3" class="p-2">
                  @if($request->status == 'assigned')
                    <div class="row">
                      <div class="col-md-4">
                        <span>{{ $request->divisi ?? 'N/A' }}</span>
                      </div>
                      <div class="col-md-5">
                        <span class="fw-bold">{{ $request->dosen->nama ?? 'N/A' }}</span>
                      </div>
                      <div class="col-md-3 text-center">
                        <span class="badge bg-success">Disimpan</span>
                      </div>
                    </div>
                  @else
                    <form method="POST" action="{{ route('kerja-praktik-koordinator.assign-dosen') }}">
                      @csrf
                      <input type="hidden" name="request_id" value="{{ $request->id }}">
                      <div class="row">
                        <div class="col-md-4">
                          <input type="text" name="divisi" class="form-control form-control-sm" placeholder="Masukkan divisi" required>
                        </div>
                        <div class="col-md-5">
                          <select name="dosen_id" class="form-select form-select-sm" required>
                            <option value="">Pilih Dosen</option>
                            @foreach($lecturers as $lecturer)
                              <option value="{{ $lecturer->id }}" {{ $request->dosen_id == $lecturer->id ? 'selected' : '' }}>
                                {{ $lecturer->nama }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-3 text-center">
                          <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                            Simpan
                          </button>
                        </div>
                      </div>
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
      </div>
    </div>
  </div>
</div>

<!-- Custom CSS untuk kesempurnaan -->
<style>
  .kp-tabs {
    display: flex;
    gap: 0.5rem;
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 1rem;
  }
  .kp-tab {
    padding: 0.5rem 1rem;
    text-decoration: none;
    color: #6c757d;
    font-weight: 500;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
  }
  .kp-tab.active,
  .kp-tab:hover {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
  }

  .table .form-select-sm {
    font-size: 0.85rem;
    padding: 0.25rem 0.5rem;
    height: auto;
  }
  .table .btn-sm {
    font-size: 0.775rem;
    padding: 0.25rem 0.75rem;
  }
  .table th, .table td {
    vertical-align: middle !important;
  }
  .table .text-truncate {
    max-width: 150px;
  }
  .d-flex.gap-2 {
    gap: 0.5rem;
  }
  .flex-fill {
    flex: 1;
  }
</style>


@endsection
