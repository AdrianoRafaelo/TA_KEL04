@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Page</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">MBKM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Seminar</li>
            </ol>
        </nav>
    </div>
</div>


<div class="row mb-3">
    <div class="col-12">
        <div class="kp-tabs">
            <a href="{{ url('/mbkm/dosen-konversi-matkul') }}" class="kp-tab">Konversi Matakuliah</a>
            <a href="{{ url('/mbkm/dosen-pelaksanaan')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/dosen-seminar')}}" class="kp-tab active">Seminar MBKM</a>
        </div>
    </div>
</div>


   <!-- Header -->
   <div class="d-flex justify-content-between align-items-center mb-3">
       <div>
           <button class="btn btn-sm btn-primary" style="background-color:#1E3A8A;border:none;">
               Seminar MBKM Dosen
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
                   <th style="width: 150px;" class="text-center">Laporan Matakuliah</th>
                   <th style="width: 150px;" class="text-center">Jadwal Seminar</th>
                   <th style="width: 100px;" class="text-center">Nilai</th>
                   <th style="width: 100px;" class="text-center">Aksi</th>
               </tr>
           </thead>
           <tbody style="font-size:14px; color:#111;">
               @forelse($seminars as $index => $seminar)
               <tr>
                   <td class="text-center">{{ $index + 1 }}.</td>
                   <td>{{ $seminar->mahasiswa->nama ?? 'N/A' }}</td>
                   <td>{{ $companies[$seminar->mahasiswa_id] ?? 'N/A' }}</td>
                   <td class="text-center">
                       @if($seminar->laporan_ekotek_file)
                           <a href="{{ asset('storage/' . $seminar->laporan_ekotek_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                       @else
                           -
                       @endif
                   </td>
                   <td class="text-center">
                       @if($seminar->laporan_pmb_file)
                           <a href="{{ asset('storage/' . $seminar->laporan_pmb_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                       @else
                           -
                       @endif
                   </td>
                   <td class="text-center">
                       @if($seminar->jadwal_seminar_file)
                           <a href="{{ asset('storage/' . $seminar->jadwal_seminar_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                       @else
                           -
                       @endif
                   </td>
                   <td class="text-center">
                       <input type="number" class="form-control form-control-sm nilai-input" min="0" max="100" value="{{ $seminar->nilai ?? '' }}" data-id="{{ $seminar->id }}">
                   </td>
                   <td class="text-center">
                       <button class="btn btn-success btn-sm simpan-nilai" data-id="{{ $seminar->id }}">Simpan</button>
                   </td>
               </tr>
               @empty
               <tr>
                   <td colspan="8" class="text-center">Tidak ada data seminar.</td>
               </tr>
               @endforelse
           </tbody>
       </table>
   </div>

   </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
   document.querySelectorAll('.simpan-nilai').forEach(button => {
       button.addEventListener('click', function() {
           const seminarId = this.getAttribute('data-id');
           const nilaiInput = document.querySelector(`.nilai-input[data-id="${seminarId}"]`);
           const nilai = nilaiInput.value;

           if (nilai === '' || nilai < 0 || nilai > 100) {
               alert('Nilai harus diisi antara 0-100');
               return;
           }

           if (confirm('Apakah Anda yakin ingin menyimpan nilai ini?')) {
               fetch(`/mbkm/seminar/update-nilai/${seminarId}`, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                   },
                   body: JSON.stringify({ nilai: nilai })
               })
               .then(response => response.json())
               .then(data => {
                   if (data.success) {
                       alert('Nilai berhasil disimpan');
                   } else {
                       alert(data.error || 'Terjadi kesalahan');
                   }
               })
               .catch(error => {
                   console.error('Error:', error);
                   alert('Terjadi kesalahan saat memproses permintaan');
               });
           }
       });
   });
});
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
    </style>