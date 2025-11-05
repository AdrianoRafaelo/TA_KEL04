@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <small>Admin / Pengumuman / <b>Edit</b></small>
        <h2 class="fw-bold mt-1">Edit Pengumuman: {{ $pengumuman->judul }}</h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            **Gagal memperbarui data. Mohon periksa input Anda:**
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Pengumuman</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori', $pengumuman->kategori) == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi/Detail</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $pengumuman->deskripsi) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui Pengumuman</button>
                <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection