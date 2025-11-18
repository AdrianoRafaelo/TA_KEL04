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
Route::get('/kerja-praktik-mahasiswa-pelaksanaan', [KerjaPraktikController::class, 'mahasiswaPelaksanaanKp'])->middleware('auth.session');
route::get('/kerja-praktik-mahasiswa-seminar', [KerjaPraktikController::class, 'seminarmhs'])->middleware('auth.session');
Route::get('/kerja-praktik-koordinator-pelaksanaan', [KerjaPraktikController::class, 'koordinatorPelaksanaanKp'])->middleware('auth.session');
Route::get('/kerja-praktik-koordinator-seminar', [KerjaPraktikController::class, 'koordinatorSeminarKp'])->middleware('auth.session');
Route::get('/kerja-praktik-dosen-seminar', [KerjaPraktikController::class, 'dosenSeminarKp'])->middleware('auth.session');

Route::get('/informasi-umum', function () {return view('informasi_umum');})->middleware('auth.session');
Route::get('/pendaftaran-kp', [KerjaPraktikController::class, 'pendaftaranKp'])->middleware('auth.session');
Route::post('/pendaftaran-kp/permohonan', [KerjaPraktikController::class, 'storePermohonan'])->name('pendaftaran-kp.permohonan')->middleware('auth.session');
Route::post('/pendaftaran-kp/pengantar', [KerjaPraktikController::class, 'storePengantar'])->name('pendaftaran-kp.pengantar')->middleware('auth.session');
Route::get('/download-permohonan/{id}', [KerjaPraktikController::class, 'downloadPermohonan'])->name('download.permohonan')->middleware('auth.session');
Route::get('/download-pengantar/{id}', [KerjaPraktikController::class, 'downloadPengantar'])->name('download.pengantar')->middleware('auth.session');
Route::post('/kerja-praktik-koordinator/assign-dosen', [KerjaPraktikController::class, 'assignDosen'])->name('kerja-praktik-koordinator.assign-dosen')->middleware('auth.session');
Route::post('/kerja-praktik-koordinator/approve-permohonan', [KerjaPraktikController::class, 'approvePermohonan'])->name('kerja-praktik-koordinator.approve-permohonan')->middleware('auth.session');
Route::post('/kerja-praktik-koordinator/reject-permohonan', [KerjaPraktikController::class, 'rejectPermohonan'])->name('kerja-praktik-koordinator.reject-permohonan')->middleware('auth.session');
Route::post('/kerja-praktik/daftar-kelompok', [KerjaPraktikController::class, 'daftarKelompok'])->name('kerja-praktik.daftar-kelompok')->middleware('auth.session');
Route::get('/api/kp-groups', [KerjaPraktikController::class, 'getKpGroups'])->middleware('auth.session');
Route::post('/kerja-praktik/upload-cv', [KerjaPraktikController::class, 'uploadCv'])->name('kerja-praktik.upload-cv')->middleware('auth.session');
Route::get('/api/cv-kelompok/{groupId}', [KerjaPraktikController::class, 'getCvKelompok'])->name('api.cv-kelompok')->middleware('auth.session');
Route::get('/api/user-group', [KerjaPraktikController::class, 'getUserGroup'])->name('api.user-group')->middleware('auth.session');
Route::get('/api/perusahaans', [KerjaPraktikController::class, 'getPerusahaans'])->name('api.perusahaans')->middleware('auth.session');
Route::post('/kerja-praktik/store-perusahaan', [KerjaPraktikController::class, 'storePerusahaan'])->name('kerja-praktik.store-perusahaan')->middleware('auth.session');
Route::post('/kp/informasi/store', [KerjaPraktikController::class, 'storeInformasiKp'])->name('kp.informasi.store')->middleware('auth.session');
Route::post('/kp/bimbingan/store', [KerjaPraktikController::class, 'storeBimbingan'])->name('kp.bimbingan.store')->middleware('auth.session');
Route::post('/kp/topik-khusus/store', [KerjaPraktikController::class, 'storeTopikKhusus'])->name('kp.topik-khusus.store')->middleware('auth.session');
Route::post('/kp/bimbingan/approve', [KerjaPraktikController::class, 'approveBimbingan'])->name('kp.bimbingan.approve')->middleware('auth.session');
Route::post('/kp/topik-khusus/approve', [KerjaPraktikController::class, 'approveTopikKhusus'])->name('kp.topik-khusus.approve')->middleware('auth.session');
Route::post('/kerja-praktik/store-log-activity', [KerjaPraktikController::class, 'storeLogActivity'])->name('kerja-praktik.store-log-activity')->middleware('auth.session');
Route::get('/kerja-praktik/get-log-activities', [KerjaPraktikController::class, 'getLogActivities'])->name('kerja-praktik.get-log-activities')->middleware('auth.session');
Route::get('/kerja-praktik/view-log-activity/{id}', [KerjaPraktikController::class, 'viewLogActivity'])->name('kerja-praktik.view-log-activity')->middleware('auth.session');
Route::get('/kerja-praktik/download-log-activity/{id}', [KerjaPraktikController::class, 'downloadLogActivity'])->name('kerja-praktik.download-log-activity')->middleware('auth.session');
Route::get('/kerja-praktik/preview-log-activity/{id}', [KerjaPraktikController::class, 'previewLogActivity'])->name('kerja-praktik.preview-log-activity')->middleware('auth.session');
Route::get('/api/kp-student-log-activities/{username}', [KerjaPraktikController::class, 'getStudentLogActivities'])->middleware('auth.session');
Route::post('/koordinator/update-seminar-penguji', [KerjaPraktikController::class, 'updateSeminarKpPenguji'])->name('koordinator.update.seminar.penguji')->middleware('auth.session');
Route::post('/koordinator/upload-jadwal-seminar-kp', [KerjaPraktikController::class, 'uploadJadwalSeminarKp'])->name('koordinator.upload.jadwal.seminar.kp')->middleware('auth.session');
Route::post('/koordinator/approve-seminar-kp', [KerjaPraktikController::class, 'approveSeminarKp'])->name('koordinator.approve.seminar.kp')->middleware('auth.session');
Route::post('/koordinator/upload-seminar-dokumen', [KerjaPraktikController::class, 'uploadSeminarKpDokumen'])->name('koordinator.upload.seminar.dokumen')->middleware('auth.session');

// Seminar KP routes
Route::post('/kp/seminar/store', [KerjaPraktikController::class, 'storeSeminarKp'])->name('kp.seminar.store')->middleware('auth.session');
Route::post('/kp/seminar/update-status', [KerjaPraktikController::class, 'updateSeminarKpStatus'])->name('kp.seminar.update-status')->middleware('auth.session');
Route::get('/kp/seminar/download/{filename}', [KerjaPraktikController::class, 'downloadSeminarFile'])->name('kp.seminar.download')->middleware('auth.session');
Route::delete('/kp/seminar/delete-file', [KerjaPraktikController::class, 'deleteSeminarFile'])->name('kp.seminar.delete-file')->middleware('auth.session');

// Tugas Akhir
Route::get('/ta-mahasiswa', [TugasAkhirController::class, 'indexMahasiswa'])->middleware('auth.session');
Route::get('/matakuliah', [MatakuliahController::class, 'index'])->middleware('auth.session');
Route::get('/ta-dosen', [TugasAkhirController::class, 'indexDosen'])->name('ta-dosen')->middleware('auth.session');
Route::post('/ta/store', [TugasAkhirController::class, 'store'])->middleware('auth.session');
Route::post('/ta/store-transaksi', [TugasAkhirController::class, 'storeTransaksi'])->middleware('auth.session');
Route::post('/ta/terima-transaksi/{id}', [TugasAkhirController::class, 'terimaTransaksi'])->name('ta.terimaTransaksi')->middleware('auth.session');
Route::delete('/ta/cancel-transaksi/{id}', [TugasAkhirController::class, 'cancelTransaksi'])->name('ta.cancel-transaksi')->middleware('auth.session');
Route::post('/ta/update-pengaturan', [TugasAkhirController::class, 'updatePengaturanTa'])->name('ta.update-pengaturan')->middleware('auth.session');
Route::get('/seminar-proposal', [TugasAkhirController::class, 'seminarProposal'])->name('seminar.proposal')->middleware('auth.session');
Route::get('/seminar-hasil-dosen', [TugasAkhirController::class, 'seminarHasilDosen'])->name('seminar.hasil.dosen')->middleware('auth.session');
Route::get('/sidang-akhir-dosen', [TugasAkhirController::class, 'sidangAkhirDosen'])->name('sidang.akhir.dosen')->middleware('auth.session');
Route::get('/bimbingan-dosen', [TugasAkhirController::class, 'bimbinganDosen'])->name('bimbingan.dosen')->middleware('auth.session');
Route::get('/seminar-proposal-mahasiswa', [TugasAkhirController::class, 'seminarProposalMahasiswa'])->name('seminar.proposal.mahasiswa')->middleware('auth.session');
Route::post('/seminar-proposal-mahasiswa/store', [TugasAkhirController::class, 'storeSeminarProposal'])->name('seminar.proposal.mahasiswa.store')->middleware('auth.session');
Route::post('/seminar-proposal-mahasiswa/upload-revisi', [TugasAkhirController::class, 'uploadRevisiSeminarProposal'])->name('seminar.proposal.mahasiswa.upload.revisi')->middleware('auth.session');
Route::get('/seminar-hasil-mahasiswa', [TugasAkhirController::class, 'seminarHasilMahasiswa'])->name('seminar.hasil.mahasiswa')->middleware('auth.session');
Route::post('/seminar-hasil-mahasiswa/store', [TugasAkhirController::class, 'storeSeminarHasil'])->name('seminar.hasil.mahasiswa.store')->middleware('auth.session');
Route::post('/seminar-hasil-mahasiswa/upload-revisi', [TugasAkhirController::class, 'uploadRevisiSeminarHasil'])->name('seminar.hasil.mahasiswa.upload.revisi')->middleware('auth.session');
Route::get('/sidang-akhir-mahasiswa', [TugasAkhirController::class, 'sidangAkhirMahasiswa'])->name('sidang.akhir.mahasiswa')->middleware('auth.session');
Route::post('/sidang-akhir-mahasiswa/store', [TugasAkhirController::class, 'storeSidangAkhir'])->name('sidang.akhir.mahasiswa.store')->middleware('auth.session');
Route::post('/sidang-akhir-mahasiswa/upload-revisi', [TugasAkhirController::class, 'uploadRevisiSidangAkhir'])->name('sidang.akhir.mahasiswa.upload.revisi')->middleware('auth.session');
Route::get('/bimbingan-mahasiswa', [TugasAkhirController::class, 'bimbinganMahasiswa'])->name('bimbingan.mahasiswa')->middleware('auth.session');
Route::post('/bimbingan-mahasiswa/store', [TugasAkhirController::class, 'storeBimbingan'])->name('bimbingan.mahasiswa.store')->middleware('auth.session');
Route::post('/bimbingan-mahasiswa/upload-skripsi', [TugasAkhirController::class, 'uploadSkripsiMahasiswa'])->name('bimbingan.mahasiswa.upload.skripsi')->middleware('auth.session');
Route::get('/koordinator-pendaftaran', [TugasAkhirController::class, 'koordinatorPendaftaran'])->name('koordinator.pendaftaran')->middleware('auth.session');
Route::post('/koordinator/terima-judul-batch1', [TugasAkhirController::class, 'terimaJudulBatch1'])->name('koordinator.terima.judul.batch1')->middleware('auth.session');
Route::post('/koordinator/terima-judul-batch2', [TugasAkhirController::class, 'terimaJudulBatch2'])->name('koordinator.terima.judul.batch2')->middleware('auth.session');
Route::get('/koordinator-mahasiswa-ta', [TugasAkhirController::class, 'koordinatorMahasiswaTa'])->name('koordinator.mahasiswa.ta')->middleware('auth.session');
Route::post('/koordinator-mahasiswa-ta/save', [TugasAkhirController::class, 'saveKoordinatorMahasiswaTa'])->name('koordinator.save.mahasiswa.ta')->middleware('auth.session');
Route::get('/koordinator-sempro', [TugasAkhirController::class, 'koordinatorSempro'])->name('koordinator.sempro')->middleware('auth.session');
Route::post('/koordinator-sempro/approve', [TugasAkhirController::class, 'approveSeminarProposals'])->name('koordinator.approve.sempro')->middleware('auth.session');
Route::post('/koordinator-sempro/upload-dokumen', [TugasAkhirController::class, 'uploadSemproDokumen'])->name('koordinator.upload.sempro.dokumen')->middleware('auth.session');
Route::post('/koordinator-sempro/update-pengulas', [TugasAkhirController::class, 'updateSemproPengulas'])->name('koordinator.update.sempro.pengulas')->middleware('auth.session');
Route::post('/koordinator/upload-jadwal-seminar', [TugasAkhirController::class, 'uploadJadwalSeminarDokumen'])->name('koordinator.upload.jadwal.seminar');
Route::get('/koordinator-semhas', [TugasAkhirController::class, 'koordinatorSemhas'])->name('koordinator.semhas')->middleware('auth.session');
Route::post('/koordinator-semhas/approve', [TugasAkhirController::class, 'approveSeminarHasils'])->name('koordinator.approve.semhas')->middleware('auth.session');
Route::post('/koordinator-semhas/upload-jadwal', [TugasAkhirController::class, 'uploadJadwalSeminarHasil'])->name('koordinator.upload.semhas.jadwal')->middleware('auth.session');
Route::post('/koordinator-semhas/upload-dokumen', [TugasAkhirController::class, 'uploadSemhasDokumen'])->name('koordinator.upload.semhas.dokumen')->middleware('auth.session');
Route::get('/koordinator-sidang', [TugasAkhirController::class, 'koordinatorSidang'])->name('koordinator.sidang')->middleware('auth.session');
Route::post('/koordinator-sidang/approve', [TugasAkhirController::class, 'approveSidangAkhirs'])->name('koordinator.approve.sidang')->middleware('auth.session');
Route::post('/koordinator-sidang/upload-jadwal', [TugasAkhirController::class, 'uploadJadwalSidangDokumen'])->name('koordinator.upload.sidang.jadwal')->middleware('auth.session');
Route::post('/koordinator-sidang/upload-dokumen', [TugasAkhirController::class, 'uploadSidangDokumen'])->name('koordinator.upload.sidang.dokumen')->middleware('auth.session');
Route::get('/storage-file/{path}', [TugasAkhirController::class, 'serveStorageFile'])->where('path', '.*')->name('storage.file')->middleware('auth.session');
route::get('/koordinator-skripsi', [TugasAkhirController::class, 'koordinatorSkripsi'])->name('koordinator.skripsi')->middleware('auth.session');


Route::get('/admin/roles', [FtiRoleController::class, 'index'])->name('admin.fti_roles.index')->middleware('auth.session');
Route::put('/admin/roles/{id}', [FtiRoleController::class, 'updateRole'])->name('admin.fti_roles.update')->middleware('auth.session');



Route::get('/fti-data', [ManageRoleController::class, 'getFtiData'])->name('fti-data');
Route::get('/api/mahasiswa', [ManageRoleController::class, 'getMahasiswaData'])->name('api.mahasiswa')->middleware('auth.session');
Route::post('/fti-data/refresh', [ManageRoleController::class, 'refreshData'])->name('fti-data.refresh');


Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index')->middleware('auth.session');
Route::get('/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create')->middleware('auth.session');
Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store')->middleware('auth.session');
Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show')->middleware('auth.session');
Route::get('/pengumuman/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit')->middleware('auth.session');
Route::put('/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.update')->middleware('auth.session');
Route::delete('/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy')->middleware('auth.session');


Route::get('/ta/pendaftaran', [TugasAkhirController::class, 'pendaftaran'])->name('pendaftaran.ta');
Route::get('/ta/seminar-proposal', [TugasAkhirController::class, 'seminarProposal'])->name('seminar.proposal');
