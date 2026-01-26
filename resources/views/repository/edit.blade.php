@extends('layouts.app')

@section('content')
<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit Dokumen Repository</h4>
                            <p class="card-description">Edit metadata dokumen</p>

                            <form method="POST" action="{{ route('repository.update', $item->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul Dokumen *</label>
                                            <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Penulis/Author</label>
                                            <input type="text" name="author" class="form-control" value="{{ $item->author }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="number" name="year" class="form-control" value="{{ $item->year }}" min="1900" max="{{ date('Y') + 1 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <input type="text" name="category" class="form-control" value="{{ $item->category }}" placeholder="e.g., Skripsi, Laporan">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ganti File (Opsional)</label>
                                            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="3">{{ $item->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <a href="{{ route('repository.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection