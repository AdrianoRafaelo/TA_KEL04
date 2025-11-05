 <?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\ManageRoleController;
use App\Http\Controllers\FtiRoleController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\KerjaPraktikController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth.session');
Route::get('/welcome', [LoginController::class, 'home'])->name(name: 'welcome')->middleware('auth.session');
Route::get('/dosen', [LoginController::class, 'home'])->name('dosen.home')->middleware('auth.session'); // <--- DIUBAH
Route::get('/mahasiswa', [LoginController::class, 'home'])->name('mahasiswa.home')->middleware('auth.session'); // <--- DIUBAH
// Route::get('/dosen', function () {return view('welcome');})->name('dosen.home')->middleware('auth.session');
// Route::get('/mahasiswa', function () {return view('welcome');})->name('mahasiswa.home')->middleware('auth.session');

// Halaman utama
Route::get('/', function () { if (Session::has('api_token')) { return redirect()->route('welcome');} else {return redirect('/login');}});
Route::get('/kerja-praktik', [KerjaPraktikController::class, 'index'])->middleware('auth.session');
Route::get('/kerja-praktik-dosen', [KerjaPraktikController::class, 'dosen'])->middleware('auth.session');
Route::get('/kerja-praktik-koordinator', [KerjaPraktikController::class, 'koordinator'])->middleware('auth.session');

Route::get('/informasi-umum', function () {return view('informasi_umum');})->middleware('auth.session');
Route::get('/pendaftaran-kp', [KerjaPraktikController::class, 'pendaftaranKp'])->middleware('auth.session');
Route::post('/pendaftaran-kp/permohonan', [KerjaPraktikController::class, 'storePermohonan'])->name('pendaftaran-kp.permohonan')->middleware('auth.session');
Route::post('/pendaftaran-kp/pengantar', [KerjaPraktikController::class, 'storePengantar'])->name('pendaftaran-kp.pengantar')->middleware('auth.session');
Route::get('/download-permohonan/{id}', [KerjaPraktikController::class, 'downloadPermohonan'])->name('download.permohonan')->middleware('auth.session');
Route::get('/download-pengantar/{id}', [KerjaPraktikController::class, 'downloadPengantar'])->name('download.pengantar')->middleware('auth.session');
Route::post('/kerja-praktik-koordinator/assign-dosen', [KerjaPraktikController::class, 'assignDosen'])->name('kerja-praktik-koordinator.assign-dosen')->middleware('auth.session');
Route::post('/kerja-praktik-koordinator/approve-permohonan', [KerjaPraktikController::class, 'approvePermohonan'])->name('kerja-praktik-koordinator.approve-permohonan')->middleware('auth.session');
Route::post('/kerja-praktik-koordinator/reject-permohonan', [KerjaPraktikController::class, 'rejectPermohonan'])->name('kerja-praktik-koordinator.reject-permohonan')->middleware('auth.session');

// Tugas Akhir
Route::get('/ta-mahasiswa', [TugasAkhirController::class, 'indexMahasiswa'])->middleware('auth.session');
Route::get('/matakuliah', [MatakuliahController::class, 'index'])->middleware('auth.session');
Route::get('/ta-dosen', [TugasAkhirController::class, 'indexDosen'])->name('ta-dosen')->middleware('auth.session');
Route::post('/ta/store', [TugasAkhirController::class, 'store'])->middleware('auth.session');
Route::post('/ta/store-transaksi', [TugasAkhirController::class, 'storeTransaksi'])->middleware('auth.session');
Route::post('/ta/terima-transaksi/{id}', [TugasAkhirController::class, 'terimaTransaksi'])->name('ta.terimaTransaksi')->middleware('auth.session');
Route::get('/seminar-proposal', [TugasAkhirController::class, 'seminarProposal'])->name('seminar.proposal')->middleware('auth.session');


Route::get('/admin/roles', [FtiRoleController::class, 'index'])->name('admin.fti_roles.index')->middleware('auth.session');
Route::put('/admin/roles/{id}', [FtiRoleController::class, 'updateRole'])->name('admin.fti_roles.update')->middleware('auth.session');



Route::get('/fti-data', [ManageRoleController::class, 'getFtiData'])->name('fti-data');
Route::post('/fti-data/refresh', [ManageRoleController::class, 'refreshData'])->name('fti-data.refresh');


Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index')->middleware('auth.session');
Route::get('/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create')->middleware('auth.session');
Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store')->middleware('auth.session');
Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show')->middleware('auth.session');
Route::get('/pengumuman/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit')->middleware('auth.session');
Route::put('/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.update')->middleware('auth.session');
Route::delete('/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy')->middleware('auth.session');