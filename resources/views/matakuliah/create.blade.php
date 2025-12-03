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

<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('matakuliah.index') }}">Matakuliah</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Mata Kuliah</li>
      </ol>
    </nav>
    <h4 class="mb-2">Tambah Mata Kuliah</h4>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('matakuliah.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="semester" class="form-label">Semester</label>
            <select name="semester" id="semester" class="form-control" required>
              <option value="">Pilih Semester</option>
              @for($i = 1; $i <= 8; $i++)
              <option value="{{ $i }}">Semester {{ $i }}</option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
            <input type="text" name="kode_mk" id="kode_mk" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
            <input type="text" name="nama_mk" id="nama_mk" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="nama_mk_eng" class="form-label">Nama Mata Kuliah (English)</label>
            <input type="text" name="nama_mk_eng" id="nama_mk_eng" class="form-control">
          </div>

          <div class="mb-3">
            <label for="nama_singkat_mk" class="form-label">Nama Singkat Mata Kuliah</label>
            <input type="text" name="nama_singkat_mk" id="nama_singkat_mk" class="form-control">
          </div>

          <div class="mb-3">
            <label for="sks" class="form-label">SKS</label>
            <input type="number" name="sks" id="sks" class="form-control" min="1" max="6" required>
          </div>

          <div class="mb-3">
            <label for="deskripsi_mk" class="form-label">Deskripsi Mata Kuliah</label>
            <textarea name="deskripsi_mk" id="deskripsi_mk" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label for="dosen_id" class="form-label">Dosen Pengampu</label>
            <select name="dosen_id" id="dosen_id" class="form-control">
              <option value="">Pilih Dosen</option>
              @foreach($lecturers as $lecturer)
              <option value="{{ $lecturer->id }}">{{ $lecturer->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">CPMK (Capaian Pembelajaran Mata Kuliah)</label>
            <div id="cpmkContainer">
              <div class="input-group mb-2 cpmk-item">
                <input type="text" name="cpmk[]" class="form-control" placeholder="Masukkan CPMK 1">
                <button type="button" class="btn btn-danger remove-cpmk">Hapus</button>
              </div>
            </div>
            <button type="button" id="addCpmk" class="btn btn-secondary btn-sm">Tambah CPMK</button>
          </div>

          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

<script>
document.getElementById('addCpmk').addEventListener('click', function() {
  const container = document.getElementById('cpmkContainer');
  const index = container.children.length + 1;
  const div = document.createElement('div');
  div.className = 'input-group mb-2 cpmk-item';
  div.innerHTML = `
    <input type="text" name="cpmk[]" class="form-control" placeholder="Masukkan CPMK ${index}">
    <button type="button" class="btn btn-danger remove-cpmk">Hapus</button>
  `;
  container.appendChild(div);
});

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('remove-cpmk')) {
    e.target.parentElement.remove();
  }
});
</script>