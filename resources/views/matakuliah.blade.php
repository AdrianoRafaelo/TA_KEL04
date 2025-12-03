@extends('layouts.app')

@section('content')

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kelola Matakuliah</li>
      </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Kelola Matakuliah</h4>
      <a href="{{ route('matakuliah.create') }}" class="btn btn-primary">
        <i class="mdi mdi-plus"></i> Tambah Mata Kuliah
      </a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="table-dark">
              <tr>
                <th width="5%">No</th>
                <th width="10%">Kode MK</th>
                <th width="20%">Nama Mata Kuliah</th>
                <th width="8%">Semester</th>
                <th width="8%">SKS</th>
                <th width="15%">Dosen Pengampu</th>
                <th width="15%">CPMK</th>
                <th width="14%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($kurikulum as $semester => $mk_list)
                @foreach($mk_list as $index => $mk)
                <tr>
                  <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                  <td><strong>{{ $mk['kode_mk'] }}</strong></td>
                  <td>
                    <div>
                      <div class="fw-bold">{{ $mk['nama_mk'] }}</div>
                      @if($mk['nama_mk_eng'])
                      <small class="text-muted">{{ $mk['nama_mk_eng'] }}</small>
                      @endif
                    </div>
                  </td>
                  <td>{{ $mk['semester'] }}</td>
                  <td>{{ $mk['sks'] }}</td>
                  <td>{{ $mk['dosen_nama'] ?? 'Belum ditentukan' }}</td>
                  <td>
                    @if($mk['cpmk'] && count($mk['cpmk']) > 0)
                      <small>
                        @foreach($mk['cpmk'] as $key => $cpmk)
                          CPMK {{ $key + 1 }}: {{ Str::limit($cpmk, 30) }}<br>
                        @endforeach
                      </small>
                    @else
                      <span class="text-muted">Belum ada CPMK</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="{{ route('matakuliah.edit', $mk['id']) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="mdi mdi-pencil"></i>
                      </a>
                      <form action="{{ route('matakuliah.destroy', $mk['id']) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah {{ $mk['nama_mk'] }}?')">
                          <i class="mdi mdi-delete"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @endforeach
              @empty
              <tr>
                <td colspan="8" class="text-center py-4">
                  <div class="text-muted">
                    <i class="mdi mdi-notebook-outline" style="font-size: 3rem;"></i>
                    <p class="mt-2">Belum ada mata kuliah yang terdaftar</p>
                    <a href="{{ route('matakuliah.create') }}" class="btn btn-primary">
                      <i class="mdi mdi-plus"></i> Tambah Mata Kuliah Pertama
                    </a>
                  </div>
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
@endsection