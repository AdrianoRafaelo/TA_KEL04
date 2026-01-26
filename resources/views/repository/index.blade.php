@extends('layouts.app')

@section('content')
<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">E-Repository Akademik</h4>
                            <p class="card-description">Cari dan download dokumen akademik</p>

                            <!-- Search Form -->
                            <form method="GET" action="{{ route('repository.search') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="title" class="form-control" placeholder="Judul dokumen" value="{{ request('title') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="author" class="form-control" placeholder="Penulis/Author" value="{{ request('author') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="year" class="form-control" placeholder="Tahun" value="{{ request('year') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="type" class="form-control">
                                            <option value="">Semua Tipe</option>
                                            <option value="ta" {{ request('type') == 'ta' ? 'selected' : '' }}>Tugas Akhir</option>
                                            <option value="kp" {{ request('type') == 'kp' ? 'selected' : '' }}>Kerja Praktik</option>
                                            <option value="mbkm" {{ request('type') == 'mbkm' ? 'selected' : '' }}>MBKM</option>
                                            <option value="manual" {{ request('type') == 'manual' ? 'selected' : '' }}>Manual Upload</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Cari</button>
                                        <a href="{{ route('repository.index') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <!-- Upload Form -->
                            <div class="mb-4">
                                <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#uploadForm" aria-expanded="false">
                                    <i class="mdi mdi-plus"></i> Upload Dokumen Baru
                                </button>
                                <div class="collapse mt-3" id="uploadForm">
                                    <div class="card card-body">
                                        <form method="POST" action="{{ route('repository.upload') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Judul Dokumen *</label>
                                                        <input type="text" name="title" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Penulis/Author</label>
                                                        <input type="text" name="author" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tahun</label>
                                                        <input type="number" name="year" class="form-control" min="1900" max="{{ date('Y') + 1 }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Kategori</label>
                                                        <input type="text" name="category" class="form-control" placeholder="e.g., Skripsi, Laporan">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>File (PDF, DOC, DOCX) *</label>
                                                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Deskripsi</label>
                                                        <textarea name="description" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-success">Upload</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Results Table -->
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Penulis</th>
                                            <th>Tahun</th>
                                            <th>Tipe</th>
                                            <th>Kategori</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $item)
                                        <tr>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->author ?? '-' }}</td>
                                            <td>{{ $item->year ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ ucfirst($item->type) }}</span>
                                            </td>
                                            <td>{{ $item->category ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('repository.download', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="mdi mdi-download"></i> Download
                                                </a>
                                                <a href="{{ route('repository.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('repository.destroy', $item->id) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="mdi mdi-delete"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada dokumen ditemukan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection