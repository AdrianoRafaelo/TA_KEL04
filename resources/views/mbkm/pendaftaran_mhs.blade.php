@extends('layouts.app')

@section('content')
@php
$user = \App\Models\FtiData::where('username', session('username'))->first();
$displayName = $user ? $user->nama : (session('username') ?? 'User');
@endphp
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
            <button class="kp-tab">Informasi Umum</button>
            <a href="{{ url('/mbkm/pendaftaran-mhs') }}" class="kp-tab active">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-mhs')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-mhs')}}" class="kp-tab">Seminar MBKM</a>
        </div>
    </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-8">
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
    @if($pendaftaran_requests->where('status', '!=', 'rejected')->count() == 0)
    <div class="kp-form-card">
      <div class="row justify-content-center">
<div class="col-md-13">
          <h5 class="kp-form-title mb-3">MBKM MITRA</h5>
          <form method="POST" action="{{ route('mbkm.pendaftaran-mhs.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label>Nama</label>
              <input type="text" name="nama" class="form-control" value="{{ $displayName }}" readonly required>
            </div>
            <div class="form-group">
              <label>NIM</label>
              <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" required>
            </div>
            <div class="form-group">
              <label>Semester</label>
              <input type="text" name="semester" class="form-control" placeholder="Masukkan Semester" required>
            </div>
            <div class="form-group">
              <label>IPK</label>
              <input type="number" step="0.01" name="ipk" class="form-control" placeholder="Masukkan IPK" min="0" max="4" required>
            </div>
            <div class="form-group">
              <label>Matakuliah Ekuivalensi</label>
              <textarea name="matakuliah_ekuivalensi" class="form-control" rows="3" placeholder="Masukkan Matakuliah Ekuivalensi" required></textarea>
            </div>
            <div class="form-group">
              <label>Mitra</label>
              <select name="mitra_id" class="form-control" required>
                <option value="">Pilih Mitra</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->nama_perusahaan }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Unggah Portofolio</label>
              <input type="file" name="portofolio_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" required>
            </div>
            <div class="form-group">
              <label>Unggah CV</label>
              <input type="file" name="cv_file" class="form-control" accept=".pdf,.doc,.docx,jpg,png" required>
            </div>
            <div class="form-group">
              <label>Masa MBKM</label>
              <input type="text" name="masa_mbkm" class="form-control" placeholder="Masukkan masa MBKM" required>
            </div>
            <button type="submit" class="btn btn-primary">Req Surat</button>
            @if(isset($pendaftaran_requests) && $pendaftaran_requests->where('status', 'pending')->count() > 0)
              @php $pendingPendaftaran = $pendaftaran_requests->where('status', 'pending')->first(); @endphp
              <button class="btn btn-warning btn-sm ms-1" onclick="editPendaftaranModal({{ $pendingPendaftaran->id }})">Edit</button>
              <button class="btn btn-danger btn-sm ms-1" onclick="deletePendaftaran({{ $pendingPendaftaran->id }})">Delete</button>
            @endif
          </form>
        </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
      <h5>Anda sudah terdaftar MBKM.</h5>
      <p>Jika ingin mendaftar ulang, tunggu keputusan dari koordinator atau jika ditolak.</p>
    </div>
    @endif
  </div>
</div>

<!-- Edit Pendaftaran Modal -->
<div class="modal fade" id="editPendaftaranModal" tabindex="-1" role="dialog" aria-labelledby="editPendaftaranModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPendaftaranModalLabel">Edit Pendaftaran MBKM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editPendaftaranForm">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" id="edit_nama" class="form-control" readonly required>
          </div>
          <div class="form-group">
            <label>NIM</label>
            <input type="text" name="nim" id="edit_nim" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Semester</label>
            <input type="text" name="semester" id="edit_semester" class="form-control" required>
          </div>
          <div class="form-group">
            <label>IPK</label>
            <input type="number" step="0.01" name="ipk" id="edit_ipk" class="form-control" min="0" max="4" required>
          </div>
          <div class="form-group">
            <label>Matakuliah Ekuivalensi</label>
            <textarea name="matakuliah_ekuivalensi" id="edit_matakuliah_ekuivalensi" class="form-control" rows="3" required></textarea>
          </div>
          <div class="form-group">
            <label>Mitra</label>
            <select name="mitra_id" id="edit_mitra_id" class="form-control" required>
              <option value="">Pilih Mitra</option>
              @foreach($companies as $company)
              <option value="{{ $company->id }}">{{ $company->nama_perusahaan }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Masa MBKM</label>
            <input type="text" name="masa_mbkm" id="edit_masa_mbkm" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
Swal.fire({
  title: 'Berhasil!',
  text: '{{ session('success') }}',
  icon: 'success',
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true
});
</script>
@endif
<script>
function editPendaftaranModal(id) {
  // Fetch data for the pendaftaran
  $.ajax({
    url: '{{ route("mbkm.pendaftaran-mhs.get", ":id") }}'.replace(':id', id),
    type: 'GET',
    success: function(data) {
      $('#edit_nama').val(data.nama);
      $('#edit_nim').val(data.nim);
      $('#edit_semester').val(data.semester);
      $('#edit_ipk').val(data.ipk);
      $('#edit_matakuliah_ekuivalensi').val(data.matakuliah_ekuivalensi);
      $('#edit_mitra_id').val(data.mitra_id);
      $('#edit_masa_mbkm').val(data.masa_mbkm);
      $('#editPendaftaranForm').attr('action', '{{ route("mbkm.update.pendaftaran", ":id") }}'.replace(':id', id));
      $('#editPendaftaranModal').modal('show');
    },
    error: function(xhr) {
      console.log(xhr);
      alert('Gagal mengambil data pendaftaran.');
    }
  });
}

function deletePendaftaran(id) {
  if (confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("mbkm.pendaftaran-mhs.delete", ":id") }}'.replace(':id', id);
    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}" /> <input type="hidden" name="_method" value="DELETE" />';
    document.body.appendChild(form);
    form.submit();
  }
}

// Handle edit form submission
$('#editPendaftaranForm').on('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append('_method', 'PUT');
  const actionUrl = $(this).attr('action');

  $.ajax({
    url: actionUrl,
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      $('#editPendaftaranModal').modal('hide');
      Swal.fire({
        title: 'Berhasil!',
        text: 'Pendaftaran berhasil diperbarui.',
        icon: 'success',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      }).then(() => {
        location.reload();
      });
    },
    error: function(xhr) {
      alert('Gagal menyimpan perubahan.');
    }
  });
});
</script>
@endsection
