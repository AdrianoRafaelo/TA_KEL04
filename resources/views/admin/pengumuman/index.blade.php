@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <small>Admin / Pengumuman / <b>Daftar</b></small>
        <h2 class="fw-bold mt-1">Manajemen Pengumuman/Informasi</h2>
    </div>

    <a href="{{ route('pengumuman.create') }}" class="btn btn-primary mb-3">Tambah Pengumuman Baru</a>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">{{ $message }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal Posting</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengumuman as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <b>{{ $item->judul }}</b><br>
                            <small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                        </td>
                        <td><span class="badge bg-primary">{{ $item->kategori }}</span></td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td style="width: 150px;">
                            <form action="{{ route('pengumuman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman: {{ $item->judul }}?');">
                                <a href="{{ route('pengumuman.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada pengumuman yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection