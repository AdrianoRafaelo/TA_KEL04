@extends('layouts.app')

@section('content')
@php
$user = \App\Models\FtiData::where('username', session('username'))->first();
$displayName = $user ? $user->nama : (session('username') ?? 'User');
@endphp
<div class="row mb-3">
    <div class="col-12">
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
    </div>
</div>
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
            <a href="{{ url('/mbkm/pendaftaran-nonmitra-mhs') }}" class="kp-tab active">Pendaftaran MBKM</a>
            <a href="{{ url('/mbkm/pelaksanaan-nonmitra-mhs')}}" class="kp-tab">Pelaksanaan MBKM</a>
            <a href="{{ url('/mbkm/seminar-mhs')}}" class="kp-tab">Seminar MBKM</a>
        </div>
    </div>
</div>

<div class="row">
    <!-- mbkm non mitra -->
    <div class="col-md-6 mb-4">
        <form method="POST" action="{{ route('mbkm.pendaftaran-nonmitra-mhs.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="cardd border-0 shadow-sm p-4" style="border-radius: 16px;">
                <h4 class="fw-bold mb-4">MBKM Non-Mitra</h4>

            <div class="mb-3">
                <label class="fw-bold">Program MBKM Non-Mitra</label>
                <select name="program_id" class="form-select" required>
                    <option value="">Pilih Program</option>
                    @foreach($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->nama_program }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Nama Perusahaan/Nama universitas</label>
                <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukkan nama perusahaan" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Posisi MBKM</label>
                <input type="text" name="posisi_mbkm" class="form-control" placeholder="Masukkan posisi MBKM" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Unggah LOA</label>
                <input type="file" name="file_loa" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Unggah Proposal</label>
                <input type="file" name="file_proposal" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Masa MBKM</label>
                <input type="text" name="masa_mbkm" class="form-control" placeholder="Masukkan masa MBKM" required>
            </div>

            <div class="mb-4">
                <label class="fw-bold">Matakuliah Ekuivalensi</label><br>
                <label class="me-3"><input type="radio" name="matakuliah_ekuivalensi" value="ya" required> Ya</label>
                <label><input type="radio" name="matakuliah_ekuivalensi" value="tidak" required> Tidak</label>
            </div>

            <button type="submit" class="btn btn-dark px-4">Daftar</button>
            </div>
        </form>
    </div>

    <!-- input konversi mk -->
    <div class="col-md-6 mb-4">
        <form method="POST" action="{{ route('mbkm.konversi-mk.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="cardd border-0 shadow-sm p-4" style="border-radius: 16px;">
                <h5 class="fw-bold mb-4 px-3 py-2 text-white"
                    style="background:#1c1b3b; display:inline-block; border-radius:12px;">
                    Konversi MK
                </h5>

                <div class="mb-3">
                    <label class="fw-bold">Pilih MK</label>
                    <select name="kurikulum_id" id="kurikulumSelect" class="form-select" style="max-width: 200px;" required>
                        <option value="">Pilih MK</option>
                        @foreach(\App\Models\Kurikulum::where('active', 1)->get() as $mk)
                        <option value="{{ $mk->id }}" data-cpmk="{{ $mk->cpmk ? json_encode($mk->cpmk) : '[]' }}">{{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- CPMK Container - appears here after selecting MK -->
                <div id="cpmkContainer" class="mb-3" style="min-height: 40px;">
                    <p class="text-muted small">Pilih mata kuliah untuk melihat Capaian Pembelajaran Mata Kuliah (CPMK)</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" class="form-control" rows="3" placeholder="Deskripsi kegiatan selama MBKM" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Alokasi Waktu</label>
                    <div class="input-group" style="max-width: 200px;">
                        <input type="number" name="alokasi_waktu" class="form-control" required>
                        <span class="input-group-text">hr</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Unggah Form Kesesuaian Aktivitas MBKM dengan CPMK</label>
                    <input type="file" name="file_kesesuaian" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" required>
                </div>

                <button type="submit" class="btn btn-dark px-4">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- konversi mk -->
<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">

            <h5 class="fw-bold mb-4 px-3 py-2 text-white"
                style="background:#1c1b3b; display:inline-block; border-radius:12px;">
                Konversi MK
            </h5>

            <table class="table align-middle">
                <tbody>
                    @forelse(\App\Models\MkKonversi::with('kurikulum')->where('mahasiswa_id', $user->id ?? null)->where('active', '1')->get() as $konversi)
                    <tr>
                        <td>{{ $konversi->kurikulum->nama_mk ?? 'N/A' }}</td>
                        <td>
                            @if($konversi->status == 'pending')
                                <span class="badge bg-secondary">Menunggu</span>
                            @elseif($konversi->status == 'approved')
                                <span class="badge bg-success">Diterima</span>
                            @elseif($konversi->status == 'rejected')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>{{ $konversi->kurikulum->nama_singkat_mk ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada konversi MK.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="fw-bold mt-3">
                Total SKS Konversi: <span class="text-dark">{{ \App\Models\MkKonversi::join('kurikulum', 'mk_konversis.kurikulum_id', '=', 'kurikulum.id')->where('mk_konversis.mahasiswa_id', $user->id ?? null)->where('mk_konversis.status', 'approved')->where('mk_konversis.active', '1')->sum('kurikulum.sks') }} SKS</span>
            </div>

        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kurikulumSelect = document.getElementById('kurikulumSelect');
    const cpmkContainer = document.getElementById('cpmkContainer');

    if (kurikulumSelect) {
        kurikulumSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const cpmkData = selectedOption.getAttribute('data-cpmk');

            console.log('Selected MK:', selectedOption.text);
            console.log('CPMK Data:', cpmkData);

            if (cpmkData && cpmkData !== '[]') {
                try {
                    const cpmkArray = JSON.parse(cpmkData);
                    console.log('Parsed CPMK:', cpmkArray);

                    if (cpmkArray && cpmkArray.length > 0) {
                        let html = '';
                        cpmkArray.forEach((cpmk, index) => {
                            html += `
                                <div class="mb-3">
                                    <label class="fw-bold">CPMK ${index + 1}</label>
                                    <p class="text-muted">${cpmk}</p>
                                </div>
                            `;
                        });
                        cpmkContainer.innerHTML = html;
                    } else {
                        cpmkContainer.innerHTML = '<p class="text-muted">Tidak ada CPMK untuk mata kuliah ini.</p>';
                    }
                } catch (error) {
                    console.error('Error parsing CPMK data:', error);
                    cpmkContainer.innerHTML = '<p class="text-danger">Error loading CPMK data.</p>';
                }
            } else {
                cpmkContainer.innerHTML = '<p class="text-muted">Pilih mata kuliah untuk melihat CPMK.</p>';
            }
        });
    } else {
        console.error('kurikulumSelect element not found');
    }
});
</script>

<style>
    .cardd {
        background-color: #ffffff;
    }
</style>