@extends('layouts.app')

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Page</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/kerja-praktik') }}">Kerja Praktik</a></li>
        <li class="breadcrumb-item active" aria-current="page">
          <a href="{{ url('/pendaftaran-kp') }}" style="color:inherit; text-decoration:none;">Pendaftaran KP</a>
        </li>
      </ol>
    </nav>
    <h4 class="mb-2">Pendaftaran KP</h4>
  </div>
</div>

<div class="row mb-3">
   <div class="col-12">
     <div class="kp-tabs">
       <button class="kp-tab ">Informasi Umum</button>
       <a href="{{ url('/pendaftaran-kp') }}" class="kp-tab active">Pendaftaran KP</a>
       <a href="{{ url('/kerja-praktik-mahasiswa-pelaksanaan') }}" class="kp-tab">Pelaksanaan KP</a>
       <a href="{{ url('/kerja-praktik-mahasiswa-seminar') }}" class="kp-tab">Seminar KP</a>
     </div>
   </div>
 </div>

<div class="row mb-3">
  <div class="col-12">
    <div class="kp-form-card">
      <div class="row">
        <div class="col-md-6 pr-md-4">
          <h5 class="kp-form-title mb-3">Surat Permohonan KP</h5>
          <form method="POST" action="{{ route('pendaftaran-kp.permohonan') }}">
            @csrf
            <div class="form-group">
              <label>Pilih Perusahaan</label>
              <select name="perusahaan_id" class="form-control" id="perusahaan_select" required>
                <option value="">Pilih Perusahaan</option>
                @if(isset($perusahaans))
                  @foreach($perusahaans as $perusahaan)
                    <option value="{{ $perusahaan->id }}" data-alamat="{{ $perusahaan->alamat }}">{{ $perusahaan->nama_perusahaan }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="form-group">
              <label>Alamat Perusahaan</label>
              <input type="text" name="alamat_perusahaan" id="alamat_perusahaan" class="form-control" placeholder="Alamat akan terisi otomatis" readonly required>
            </div>
            <div class="form-group">
              <label>Waktu Awal KP</label>
              <input type="date" name="waktu_awal_kp" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Waktu Selesai KP</label>
              <input type="date" name="waktu_selesai_kp" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Tahun Ajaran</label>
              <input type="text" name="tahun_ajaran" class="form-control" placeholder="Ketik tahun ajaran" required>
            </div>
            <!-- <div class="form-group">
              <label>Tambahkan Mahasiswa</label>
              <input type="text" name="mahasiswa" class="form-control" placeholder="Ketik nama mahasiswa" required>
            </div> -->
            <button type="submit" class="btn btn-primary">Req Surat</button>
            @if(isset($permohonan_requests) && $permohonan_requests->where('status', 'pending')->count() > 0)
              @php $pendingPermohonan = $permohonan_requests->where('status', 'pending')->first(); @endphp
              <div class="d-flex justify-content-between align-items-center mt-2">
                <button class="btn btn-secondary btn-sm" onclick="editPermohonanModal({{ $pendingPermohonan->id }})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deletePermohonan({{ $pendingPermohonan->id }})"><i class="bi bi-trash"></i> Delete</button>
              </div>
            @endif
          </form>
        </div>
        <div class="col-md-6 pl-md-4 border-left">
          <h5 class="kp-form-title mb-3">Surat Pengantar KP</h5>
          <form method="POST" action="{{ route('pendaftaran-kp.pengantar') }}">
            @csrf
            <div class="form-group">
              <label>Perusahaan KP</label>
              <select name="perusahaan_id" class="form-control" required>
                <option value="">Pilih Perusahaan KP</option>
                @if(isset($perusahaans))
                  @foreach($perusahaans as $perusahaan)
                    <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_perusahaan }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="form-group">
              <label>Supervisor</label>
              <input type="text" name="nama_supervisor" class="form-control" placeholder="Ketik nama supervisor" required>
            </div>
            <div class="form-group">
              <label>No. Supervisor</label>
              <input type="text" name="no_supervisor" class="form-control" placeholder="Ketik nomor supervisor" required>
            </div>
            <button type="submit" class="btn btn-primary">Req Surat</button>
            @if(isset($pengantar_requests) && $pengantar_requests->where('status', 'pending')->count() > 0)
              @php $pendingPengantar = $pengantar_requests->where('status', 'pending')->first(); @endphp
              <div class="d-flex justify-content-between align-items-center mt-2">
                <button class="btn btn-secondary btn-sm" onclick="editPengantar({{ $pendingPengantar->id }})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deletePengantar({{ $pendingPengantar->id }})"><i class="bi bi-trash"></i> Delete</button>
              </div>
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-4">
    <div class="kp-list-card">
      <h6 class="kp-list-title mb-3">Unduh Surat Permohonan</h6>
      @if(isset($approved_permohonan_requests) && $approved_permohonan_requests->count() > 0)
        @foreach($approved_permohonan_requests as $request)
          <div class="kp-list-item">
            {{ $request->company->nama_perusahaan ?? 'N/A' }}
            <span class="kp-badge kp-badge-unduh"><a href="{{ route('download.permohonan', $request->id) }}" class="text-white">Unduh</a></span>
          </div>
        @endforeach
      @else
        <div class="kp-list-item text-muted">Belum ada surat permohonan yang disetujui</div>
      @endif
    </div>
  </div>
  <div class="col-md-4">
    <div class="kp-list-card">
      <h6 class="kp-list-title mb-3">Unduh Surat Pengantar</h6>
      @if(isset($approved_pengantar_requests) && $approved_pengantar_requests->count() > 0)
        @foreach($approved_pengantar_requests as $request)
          <div class="kp-list-item">
            {{ $request->company->nama_perusahaan ?? 'N/A' }}
            <span class="kp-badge kp-badge-unduh"><a href="{{ route('download.pengantar', $request->id) }}" class="text-white">Unduh</a></span>

          </div>
        @endforeach
      @else
        <div class="kp-list-item text-muted">Belum ada surat pengantar yang disetujui</div>
      @endif
    </div>
  </div>
  <div class="col-md-4">
    <div class="kp-list-card">
      <h6 class="kp-list-title mb-3">Perusahaan KP Final</h6>
      @if(isset($final_companies) && $final_companies->count() > 0)
        @foreach($final_companies as $company)
          <div class="kp-list-item">{{ $company->nama_perusahaan }} <span class="kp-badge kp-badge-hapus"><a href="#" class="text-white">Hapus</a></span></div>
        @endforeach
      @else
        <div class="kp-list-item text-muted">Belum ada perusahaan final</div>
      @endif
    </div>
  </div>
</div>

<!-- Edit Pengantar Modal – VERSI SUPER MODERN -->
<div class="modal-overlay" id="editPengantarModal" style="display:none;">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="bi bi-file-earmark-text-fill me-2"></i>
                Edit Surat Pengantar KP
            </div>
            <button type="button" class="modal-close-btn btn btn-secondary" onclick="$('#editPengantarModal').hide()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editPengantarForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Perusahaan KP</label>
                        <select name="perusahaan_id" id="edit_perusahaan_id" class="form-select" required>
                            <option value="">Pilih Perusahaan KP</option>
                            @if(isset($perusahaans))
                              @foreach($perusahaans as $perusahaan)
                                <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_perusahaan }}</option>
                              @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Supervisor</label>
                        <input type="text" name="nama_supervisor" id="edit_nama_supervisor" class="form-control" placeholder="Ketik nama supervisor" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Supervisor</label>
                        <input type="text" name="no_supervisor" id="edit_no_supervisor" class="form-control" placeholder="Ketik nomor supervisor" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#editPengantarModal').hide()">
                    Close
                </button>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Permohonan Modal – VERSI SUPER MODERN -->
<div class="modal-overlay" id="editPermohonanModal" style="display:none;">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="bi bi-file-earmark-text-fill me-2"></i>
                Edit Surat Permohonan KP
            </div>
            <button type="button" class="modal-close-btn btn btn-secondary" onclick="$('#editPermohonanModal').hide()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editPermohonanForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Perusahaan</label>
                        <select name="perusahaan_id" id="edit_perusahaan_id_permohonan" class="form-select" required>
                            <option value="">Pilih Perusahaan</option>
                            @if(isset($perusahaans))
                              @foreach($perusahaans as $p)
                                <option value="{{ $p->id }}" data-alamat="{{ $p->alamat }}">{{ $p->nama_perusahaan }}</option>
                              @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat Perusahaan</label>
                        <input type="text" id="edit_alamat_perusahaan" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Waktu Awal KP</label>
                        <input type="date" name="waktu_awal_kp" id="edit_waktu_awal_kp" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Waktu Selesai KP</label>
                        <input type="date" name="waktu_selesai_kp" id="edit_waktu_selesai_kp" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="edit_tahun_ajaran" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mahasiswa (pisahkan dengan koma)</label>
                        <input type="text" name="mahasiswa" id="edit_mahasiswa" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#editPermohonanModal').hide()">
                    Close
                </button>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#perusahaan_select').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const alamat = selectedOption.data('alamat') || '';
        $('#alamat_perusahaan').val(alamat);
    });

    $('#edit_perusahaan_id_permohonan').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const alamat = selectedOption.data('alamat') || '';
        $('#edit_alamat_perusahaan').val(alamat);
    });

    // Click outside to close modal
    $('#editPermohonanModal, #editPengantarModal').click(function(e) {
        if (e.target === this) {
            $(this).hide();
        }
    });

    // Handle edit permohonan form submission
    $('#editPermohonanForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const actionUrl = $(this).attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editPermohonanModal').hide();
                location.reload(); // Reload page to reflect changes
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Gagal menyimpan perubahan.';
                alert(errorMsg);
            }
        });
    });

    // Handle edit pengantar form submission
    $('#editPengantarForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const actionUrl = $(this).attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editPengantarModal').hide();
                location.reload(); // Reload page to reflect changes
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Gagal menyimpan perubahan.';
                alert(errorMsg);
            }
        });
    });
});

function editPermohonan(id) {
    // Redirect to edit page
    window.location.href = '{{ url("/pendaftaran-kp/edit-permohonan") }}/' + id;
}

function editPermohonanModal(id) {
    // Fetch data for the permohonan request
    $.ajax({
        url: `/pendaftaran-kp/get-permohonan/${id}`,
        type: 'GET',
        success: function(data) {
            $('#edit_perusahaan_id_permohonan').val(data.perusahaan_id);
            $('#edit_alamat_perusahaan').val(data.alamat_perusahaan);
            $('#edit_waktu_awal_kp').val(data.waktu_awal_kp ? data.waktu_awal_kp.substring(0, 10) : '');
            $('#edit_waktu_selesai_kp').val(data.waktu_selesai_kp ? data.waktu_selesai_kp.substring(0, 10) : '');
            $('#edit_tahun_ajaran').val(data.tahun_ajaran);
            $('#edit_mahasiswa').val(data.mahasiswa);
            $('#editPermohonanForm').attr('action', `/pendaftaran-kp/update-permohonan/${id}`);
            $('#editPermohonanModal').css('display', 'flex');
        },
        error: function(xhr) {
            console.log(xhr);
            alert('Gagal mengambil data surat permohonan.');
        }
    });
}

function deletePermohonan(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Ingin menghapus surat permohonan ini? Tindakan ini dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("/pendaftaran-kp/delete-permohonan") }}/' + id;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}" /> <input type="hidden" name="_method" value="DELETE" />';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function editPengantar(id) {
    // Fetch data for the pengantar request
    $.ajax({
        url: `/pendaftaran-kp/get-pengantar/${id}`,
        type: 'GET',
        success: function(data) {
            $('#edit_perusahaan_id').val(data.perusahaan_id);
            $('#edit_nama_supervisor').val(data.nama_supervisor);
            $('#edit_no_supervisor').val(data.no_supervisor);
            $('#editPengantarForm').attr('action', `/pendaftaran-kp/update-pengantar/${id}`);
            $('#editPengantarModal').css('display', 'flex');
        },
        error: function() {
            alert('Gagal mengambil data surat pengantar.');
        }
    });
}

function deletePengantar(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Ingin menghapus surat pengantar ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("/pendaftaran-kp/delete-pengantar") }}/' + id;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}" /> <input type="hidden" name="_method" value="DELETE" />';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// SweetAlert2 toast notifications
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ session("error") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: '{{ session("warning") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('info'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: '{{ session("info") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('message'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session("message") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif
});
</script>
@endsection


<style>
/* Import Modern Font */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

/* Global Typography */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
    font-size: 14px;
    line-height: 1.6;
    color: #374151;
}

/* Typography Hierarchy */
h4 {
    font-size: 2rem !important;
    font-weight: 700 !important;
    color: #1f2937 !important;
    margin-bottom: 1rem !important;
    letter-spacing: -0.025em !important;
}

h5 {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 1rem !important;
    letter-spacing: -0.02em !important;
}

.kp-form-title, .kp-list-title {
    color: white !important;
}

.kp-tab {
    font-weight: 700 !important;
    font-size: 1.1rem !important;
}

h6 {
    font-size: 1.125rem !important;
    font-weight: 600 !important;
    color: #4b5563 !important;
    margin-bottom: 0.75rem !important;
    letter-spacing: -0.01em !important;
}

p, .form-group label, .kp-list-item {
    font-size: 0.875rem !important;
    line-height: 1.5 !important;
    color: #6b7280 !important;
}

.form-control, .form-select {
    font-size: 0.875rem !important;
    font-weight: 400 !important;
}

.btn {
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    letter-spacing: 0.025em !important;
}

/* Glassmorphism Card Styles */
.kp-form-card, .kp-list-card {
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(10px) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 16px !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 4px 16px rgba(0, 0, 0, 0.05) !important;
    transition: all 0.3s ease !important;
}

.kp-form-card:hover, .kp-list-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15), 0 6px 20px rgba(0, 0, 0, 0.1) !important;
}

/* Form Controls with Rounded Corners */
.form-control, .form-select {
    border-radius: 12px !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    background: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(5px) !important;
    transition: all 0.3s ease !important;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    background: rgba(255, 255, 255, 0.95) !important;
}

/* Buttons with Rounded Corners */
.btn {
    border-radius: 12px !important;
}

/* Custom Modal Styles like koordinator_sempro */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease-out;
}

.modal-container {
    background: white;
    border-radius: 16px;
    width: 95%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s ease-out;
}
.modal-content {
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s ease-out;
    border: none;
    overflow: hidden;
}

.modal-backdrop {
    backdrop-filter: blur(4px);
}

.modal-header {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    color: white;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-bottom: none;
}

.modal-title {
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    margin: 0;
}

.btn-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    opacity: 1;
}

.btn-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.btn-close:focus {
    box-shadow: none;
}

.modal-body {
    padding: 24px;
    overflow-y: auto;
    max-height: calc(90vh - 140px);
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    text-align: right;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }

    .modal-body {
        padding: 16px;
        max-height: calc(90vh - 120px);
    }
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%) !important;
    color: white !important;
    border: none !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
    color: white !important;
    border: none !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
    color: #212529 !important;
    border: none !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%) !important;
    color: white !important;
    border: none !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
}
</style>
@endsection