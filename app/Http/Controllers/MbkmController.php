<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MbkmMitra;
use App\Models\PendaftaranMbkm;
use App\Models\PendaftaranMbkmNonmitra;
use App\Models\MkKonversi;
use App\Models\FtiData;
use App\Models\MbkmPelaksanaan;
use App\Models\SeminarMbkm;
use App\Models\Kurikulum;
use App\Models\MbkmNonMitraProgram;

class MbkmController extends Controller
{
    public function index()
    {
        return view('mbkm.informasi_mhs');
    }

    public function informasinonmitra()
    {
        return view('mbkm.informasi_nonmitra_mhs');
    }

    public function konversimatkul()
    {
        // Get current lecturer
        $user = FtiData::where('username', session('username'))->first();
        if (!$user) {
            abort(403, 'User tidak ditemukan.');
        }

        // Get courses taught by this lecturer
        $courses = Kurikulum::where('dosen_id', $user->id)
            ->where('active', 1)
            ->get();

        // Get students who registered for course conversion for these courses
        $pendaftarData = [];
        foreach ($courses as $course) {
            $pendaftar = MkKonversi::with('mahasiswa')
                ->where('kurikulum_id', $course->id)
                ->where('active', '1')
                ->get();
            $pendaftarData[$course->id] = $pendaftar;
        }

        return view('mbkm.dosen_konversi_matkul', compact('courses', 'pendaftarData'));
    }

    public function seminardosen()
    {
        return view('mbkm.seminar_dosen');
    }

    public function pelaksanaandosen()
    {
        return view('mbkm.pelaksanaan_dosen');
    }

    public function seminarnonmitra()
    {
        return view('mbkm.seminar_nonmitra_mhs');
    }

    public function pendaftaran()
    {
        $companies = MbkmMitra::where('active', '1')->get();

        // Get pending registrations for the user
        $user = \App\Models\FtiData::where('username', session('username'))->first();
        $pendaftaran_requests = PendaftaranMbkm::where('mahasiswa_id', $user->id ?? null)->get();

        return view('mbkm.pendaftaran_mhs', compact('companies', 'pendaftaran_requests'));
    }
    public function pendaftaranNonMitra()
    {
        $programs = MbkmNonMitraProgram::where('active', 1)->get();
        return view('mbkm.pendaftaran_nonmitra_mhs', compact('programs'));
    }

    public function pelaksanaan()
    {
        $user = \App\Models\FtiData::where('username', session('username'))->first();
        if (!$user) {
            abort(403, 'User tidak ditemukan.');
        }

        // Check if user has approved MBKM
        $approvedMbkm = PendaftaranMbkm::where('mahasiswa_id', $user->id)
            ->where('status', 'approved')
            ->first();

        // Fetch pelaksanaan data
        $pelaksanaans = MbkmPelaksanaan::where('mahasiswa_id', $user->id)
            ->where('active', true)
            ->orderBy('minggu')
            ->get();

        // Calculate progress (assume 16 weeks total)
        $totalWeeks = 16;
        $completedWeeks = $pelaksanaans->max('minggu') ?? 0;
        $progressPercentage = $totalWeeks > 0 ? min(100, ($completedWeeks / $totalWeeks) * 100) : 0;

        return view('mbkm.pelaksanaan_mhs', compact('user', 'approvedMbkm', 'pelaksanaans', 'progressPercentage', 'completedWeeks', 'totalWeeks'));
    }

    public function seminar()
    {
        $user = \App\Models\FtiData::where('username', session('username'))->first();
        if (!$user) {
            return redirect()->route('mbkm.seminar-mhs')->with('error', 'User tidak ditemukan. Silakan hubungi administrator.');
        }

        $seminar = SeminarMbkm::where('mahasiswa_id', $user->id ?? null)->where('active', true)->first();

        return view('mbkm.seminar_mhs', compact('user', 'seminar'));
    }

    public function pendaftarankoordinator()
    {
        $companies = MbkmMitra::where('active', '1')->get();
        $registrations = PendaftaranMbkm::with('mahasiswa', 'mitra')->where('active', '1')->get();
        $nonmitraRegistrations = PendaftaranMbkmNonmitra::with('mahasiswa', 'program')->where('active', '1')->get();
        return view('mbkm.pendaftaran_koordinator', compact('companies', 'registrations', 'nonmitraRegistrations'));
    }

    public function pelaksanaankoordinator()
    {
        $pelaksanaans = MbkmPelaksanaan::with(['mahasiswa'])
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get companies for mitra
        $companies = [];
        foreach ($pelaksanaans as $pelaksanaan) {
            $approvedMbkm = PendaftaranMbkm::where('mahasiswa_id', $pelaksanaan->mahasiswa_id)
                ->where('status', 'approved')
                ->first();
            if ($approvedMbkm) {
                $companies[$pelaksanaan->mahasiswa_id] = $approvedMbkm->mitra->nama_perusahaan ?? 'N/A';
            } else {
                // Check non-mitra
                $approvedNonmitra = PendaftaranMbkmNonmitra::where('mahasiswa_id', $pelaksanaan->mahasiswa_id)
                    ->where('status', 'approved')
                    ->first();
                $companies[$pelaksanaan->mahasiswa_id] = $approvedNonmitra ? ($approvedNonmitra->program->nama_program ?? 'N/A') : 'N/A';
            }
        }

        return view('mbkm.pelaksanaan_koordinator', compact('pelaksanaans', 'companies'));
    }

    public function seminarkoordinator()
    {
        $seminars = SeminarMbkm::with('mahasiswa')->where('active', true)->get();

        // Get company info for each seminar
        $companies = [];
        $konversiCounts = [];
        foreach ($seminars as $seminar) {
            // Get approved MBKM registration
            $approvedMbkm = PendaftaranMbkm::where('mahasiswa_id', $seminar->mahasiswa_id)
                ->where('status', 'approved')
                ->first();

            if ($approvedMbkm) {
                $companies[$seminar->mahasiswa_id] = $approvedMbkm->mitra->nama_perusahaan ?? 'N/A';
            } else {
                $approvedNonmitra = PendaftaranMbkmNonmitra::where('mahasiswa_id', $seminar->mahasiswa_id)
                    ->where('status', 'approved')
                    ->first();
                $companies[$seminar->mahasiswa_id] = $approvedNonmitra ? ($approvedNonmitra->program->nama_program ?? 'N/A') : 'N/A';
            }

            // Count approved MK Konversi
            $konversiCounts[$seminar->mahasiswa_id] = MkKonversi::where('mahasiswa_id', $seminar->mahasiswa_id)
                ->where('status', 'approved')
                ->where('active', '1')
                ->count();
        }

        return view('mbkm.seminar_koordinator', compact('seminars', 'companies', 'konversiCounts'));
    }

    public function storeTambahMitra(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
        ]);

        MbkmMitra::create([
            'nama_perusahaan' => $request->nama_perusahaan,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'active' => '1',
        ]);

        return redirect()->route('mbkm.pendaftaran-koordinator')->with('success', 'Perusahaan MBKM berhasil ditambahkan.');
    }

    public function updateTambahMitra(Request $request, $id)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
        ]);

        $mitra = MbkmMitra::findOrFail($id);
        $mitra->update([
            'nama_perusahaan' => $request->nama_perusahaan,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('mbkm.pendaftaran-koordinator')->with('success', 'Perusahaan MBKM berhasil diperbarui.');
    }

    public function deleteTambahMitra($id)
    {
        $mitra = MbkmMitra::findOrFail($id);
        $mitra->update(['active' => '0', 'updated_by' => auth()->id()]);

        return redirect()->route('mbkm.pendaftaran-koordinator')->with('success', 'Perusahaan MBKM berhasil dihapus.');
    }

    public function approvePendaftaranMbkm($id)
    {
        $registration = PendaftaranMbkm::findOrFail($id);
        $registration->update(['status' => 'approved', 'updated_by' => auth()->id()]);

        return redirect()->back()->with('success', 'Pendaftaran MBKM berhasil disetujui.');
    }

    public function rejectPendaftaranMbkm($id)
    {
        $registration = PendaftaranMbkm::findOrFail($id);
        $registration->update(['status' => 'rejected', 'updated_by' => auth()->id()]);

        return redirect()->back()->with('success', 'Pendaftaran MBKM berhasil ditolak.');
    }

    public function editPendaftaranMbkm($id)
    {
        // For now, redirect to edit form or handle in modal
        return redirect()->back()->with('info', 'Fitur edit belum diimplementasikan.');
    }

    public function getPendaftaranMbkm($id)
    {
        $user = \App\Models\FtiData::where('username', session('username'))->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $pendaftaran = PendaftaranMbkm::find($id);

        if (!$pendaftaran || $pendaftaran->mahasiswa_id != $user->id || $pendaftaran->status != 'pending') {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json([
            'nama' => $pendaftaran->nama,
            'nim' => $pendaftaran->nim,
            'semester' => $pendaftaran->semester,
            'ipk' => $pendaftaran->ipk,
            'matakuliah_ekuivalensi' => $pendaftaran->matakuliah_ekuivalensi,
            'mitra_id' => $pendaftaran->mitra_id,
            'masa_mbkm' => $pendaftaran->masa_mbkm,
        ]);
    }

    public function deletePendaftaranMbkm($id)
    {
        $user = \App\Models\FtiData::where('username', session('username'))->first();
        if (!$user) {
            abort(403);
        }

        $pendaftaran = PendaftaranMbkm::find($id);

        if (!$pendaftaran || $pendaftaran->mahasiswa_id != $user->id || $pendaftaran->status != 'pending') {
            abort(404);
        }

        $pendaftaran->delete();

        return redirect()->back()->with('success', 'Pendaftaran MBKM berhasil dihapus.');
    }

    public function updatePendaftaranMbkm(Request $request, $id)
    {
        $user = \App\Models\FtiData::where('username', session('username'))->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $pendaftaran = PendaftaranMbkm::find($id);

        if (!$pendaftaran || $pendaftaran->mahasiswa_id != $user->id || $pendaftaran->status != 'pending') {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->validate([
            'mitra_id' => 'required|exists:mbkm_mitras,id',
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'ipk' => 'required|numeric|min:0|max:4',
            'matakuliah_ekuivalensi' => 'required|string',
            'masa_mbkm' => 'required|string|max:255',
        ]);

        $pendaftaran->update([
            'mitra_id' => $request->mitra_id,
            'nama' => $request->nama,
            'nim' => $request->nim,
            'semester' => $request->semester,
            'ipk' => $request->ipk,
            'matakuliah_ekuivalensi' => $request->matakuliah_ekuivalensi,
            'masa_mbkm' => $request->masa_mbkm,
            'updated_by' => $user->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Pendaftaran MBKM berhasil diperbarui.']);
    }

    public function approvePendaftaranMbkmNonmitra($id)
    {
        $registration = PendaftaranMbkmNonmitra::findOrFail($id);
        $registration->update(['status' => 'approved', 'updated_by' => auth()->id()]);

        return redirect()->back()->with('success', 'Pendaftaran MBKM Non-Mitra berhasil disetujui.');
    }

    public function rejectPendaftaranMbkmNonmitra($id)
    {
        $registration = PendaftaranMbkmNonmitra::findOrFail($id);
        $registration->update(['status' => 'rejected', 'updated_by' => auth()->id()]);

        return redirect()->back()->with('success', 'Pendaftaran MBKM Non-Mitra berhasil ditolak.');
    }

    public function editPendaftaranMbkmNonmitra($id)
    {
        // For now, redirect to edit form or handle in modal
        return redirect()->back()->with('info', 'Fitur edit belum diimplementasikan.');
    }

    public function storePendaftaranMbkm(Request $request)
    {
        $user = \App\Models\FtiData::where('username', session('username'))->first();
        $displayName = $user ? $user->nama : (session('username') ?? 'User');
        $request->merge(['nama' => $displayName]);

        $request->validate([
            'mitra_id' => 'required|exists:mbkm_mitras,id',
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'ipk' => 'required|numeric|min:0|max:4',
            'matakuliah_ekuivalensi' => 'required|string',
            'portofolio_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:51200',
            'cv_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:51200',
            'masa_mbkm' => 'required|string|max:255',
        ]);

        // Handle file uploads
        $portofolioPath = $request->file('portofolio_file')->store('mbkm/portofolio', 'public');
        $cvPath = $request->file('cv_file')->store('mbkm/cv', 'public');

        $user = \App\Models\FtiData::where('username', session('username'))->first();
        $userId = $user ? $user->id : null;

        PendaftaranMbkm::create([
            'mahasiswa_id' => $userId,
            'mitra_id' => $request->mitra_id,
            'nama' => $request->nama,
            'nim' => $request->nim,
            'semester' => $request->semester,
            'ipk' => $request->ipk,
            'matakuliah_ekuivalensi' => $request->matakuliah_ekuivalensi,
            'file_portofolio' => $portofolioPath,
            'file_cv' => $cvPath,
            'masa_mbkm' => $request->masa_mbkm,
            'created_by' => $userId,
            'updated_by' => $userId,
            'active' => '1',
        ]);

        return redirect()->back()->with('success', 'Pendaftaran MBKM berhasil diajukan.');
    }

    public function storePendaftaranMbkmNonmitra(Request $request)
    {
        // Check if this is from coordinator (has mahasiswa_id) or student
        $isCoordinator = $request->has('mahasiswa_id') && !empty($request->mahasiswa_id);

        if ($isCoordinator) {
            // Coordinator form validation
            $request->validate([
                'mahasiswa_id' => 'required|exists:fti_datas,id',
                'mitra_id' => 'nullable|exists:mbkm_mitras,id',
                'nama_perusahaan' => 'required|string|max:255',
                'posisi_mbkm' => 'required|string|max:255',
                'file_loa' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:51200',
                'file_proposal' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:51200',
                'masa_mbkm' => 'required|string|max:255',
                'matakuliah_ekuivalensi' => 'required|in:ya,tidak',
            ]);

            $mahasiswaId = $request->mahasiswa_id;
            $createdBy = auth()->id() ?? session('username');
        } else {
            // Student form validation
            $request->validate([
                'program_id' => 'required|exists:mbkm_non_mitra_programs,id',
                'nama_perusahaan' => 'required|string|max:255',
                'posisi_mbkm' => 'required|string|max:255',
                'file_loa' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:51200',
                'file_proposal' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:51200',
                'masa_mbkm' => 'required|string|max:255',
                'matakuliah_ekuivalensi' => 'required|in:ya,tidak',
            ]);

            $user = \App\Models\FtiData::where('username', session('username'))->first();
            $mahasiswaId = $user ? $user->id : null;
            $createdBy = $mahasiswaId;
        }

        // Handle file uploads
        $loaPath = $request->file('file_loa')->store('mbkm/loa', 'public');
        $proposalPath = $request->file('file_proposal')->store('mbkm/proposal', 'public');

        PendaftaranMbkmNonmitra::create([
            'mahasiswa_id' => $mahasiswaId,
            'mitra_id' => $request->program_id, // Using mitra_id field to store program_id
            'nama_perusahaan' => $request->nama_perusahaan,
            'posisi_mbkm' => $request->posisi_mbkm,
            'file_loa' => $loaPath,
            'file_proposal' => $proposalPath,
            'masa_mbkm' => $request->masa_mbkm,
            'matakuliah_ekuivalensi' => $request->matakuliah_ekuivalensi,
            'created_by' => $createdBy,
            'updated_by' => $createdBy,
            'active' => '1',
        ]);

        $message = $isCoordinator
            ? 'Pendaftaran MBKM Non-Mitra berhasil ditambahkan untuk mahasiswa.'
            : 'Pendaftaran MBKM Non-Mitra berhasil diajukan.';

        return redirect()->back()->with('success', $message);
    }

    public function storePelaksanaan(Request $request)
    {
        $request->validate([
            'minggu' => 'required|integer|min:1|max:16',
            'matkul' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'bimbingan' => 'nullable|string',
        ]);

        $user = \App\Models\FtiData::where('username', session('username'))->first();
        if (!$user) {
            return redirect()->route('mbkm.pendaftaran-mhs')->with('error', 'User tidak ditemukan.');
        }

        MbkmPelaksanaan::create([
            'mahasiswa_id' => $user->id,
            'minggu' => $request->minggu,
            'matkul' => $request->matkul,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'bimbingan' => $request->bimbingan,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Log-activity berhasil ditambahkan.');
    }

    public function storeKonversiMk(Request $request)
    {
        $request->validate([
            'kurikulum_id' => 'required|exists:kurikulum,id',
            'deskripsi_kegiatan' => 'required|string',
            'alokasi_waktu' => 'required|integer|min:1',
        ]);

        $user = \App\Models\FtiData::where('username', session('username'))->first();
        $userId = $user ? $user->id : null;

        MkKonversi::create([
            'mahasiswa_id' => $userId,
            'kurikulum_id' => $request->kurikulum_id,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'alokasi_waktu' => $request->alokasi_waktu,
            'created_by' => $userId,
            'updated_by' => $userId,
            'active' => '1',
        ]);

        return redirect()->back()->with('success', 'Konversi MK berhasil diajukan.');
    }

    public function storeProgramNonmitra(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255|unique:mbkm_non_mitra_programs,nama_program',
        ]);

        MbkmNonMitraProgram::create([
            'nama_program' => $request->nama_program,
            'created_by' => auth()->id() ?? session('username'),
            'updated_by' => auth()->id() ?? session('username'),
            'active' => 1,
        ]);

        return redirect()->back()->with('success', 'Program MBKM Non-Mitra berhasil ditambahkan.');
    }

    public function updateProgramNonmitra(Request $request, $id)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255|unique:mbkm_non_mitra_programs,nama_program,' . $id,
        ]);

        $program = MbkmNonMitraProgram::findOrFail($id);
        $program->update([
            'nama_program' => $request->nama_program,
            'updated_by' => auth()->id() ?? session('username'),
        ]);

        return redirect()->back()->with('success', 'Program MBKM Non-Mitra berhasil diperbarui.');
    }

    public function deleteProgramNonmitra($id)
    {
        $program = MbkmNonMitraProgram::findOrFail($id);
        $program->update(['active' => 0, 'updated_by' => auth()->id() ?? session('username')]);

        return redirect()->back()->with('success', 'Program MBKM Non-Mitra berhasil dihapus.');
    }

    public function storeSeminarEkotek(Request $request)
    {
        $request->validate([
            'cpmk_ekotek' => 'required|string',
            'laporan_ekotek_file' => 'required|file|mimes:pdf,doc,docx|max:51200',
        ]);

        $user = \App\Models\FtiData::where('username', session('username'))->first();
        if (!$user) {
            return redirect()->route('mbkm.seminar-mhs')->with('error', 'User tidak ditemukan. Silakan hubungi administrator.');
        }

        $seminar = SeminarMbkm::firstOrNew(['mahasiswa_id' => $user->id, 'active' => true]);

        if ($request->hasFile('laporan_ekotek_file')) {
            $ekotekPath = $request->file('laporan_ekotek_file')->store('mbkm/seminar/ekotek', 'public');
            $seminar->laporan_ekotek_file = $ekotekPath;
        }

        $seminar->is_magang = $request->is_magang ?? false;
        $seminar->cpmk_ekotek = $request->cpmk_ekotek;
        $seminar->created_by = $user->id;
        $seminar->updated_by = $user->id;
        $seminar->save();

        return redirect()->back()->with('success', 'Laporan EKOTEK berhasil diunggah.');
    }

    public function storeSeminarPmb(Request $request)
    {
        $request->validate([
            'cpmk_pmb' => 'required|string',
            'laporan_pmb_file' => 'required|file|mimes:pdf,doc,docx|max:51200',
        ]);

        $user = \App\Models\FtiData::where('username', session('username'))->first();
        if (!$user) {
            return redirect()->route('mbkm.seminar-mhs')->with('error', 'User tidak ditemukan. Silakan hubungi administrator.');
        }

        $seminar = SeminarMbkm::firstOrNew(['mahasiswa_id' => $user->id, 'active' => true]);

        if ($request->hasFile('laporan_pmb_file')) {
            $pmbPath = $request->file('laporan_pmb_file')->store('mbkm/seminar/pmb', 'public');
            $seminar->laporan_pmb_file = $pmbPath;
        }

        $seminar->is_magang = $request->is_magang ?? false;
        $seminar->cpmk_pmb = $request->cpmk_pmb;
        $seminar->created_by = $user->id;
        $seminar->updated_by = $user->id;
        $seminar->save();

        return redirect()->back()->with('success', 'Laporan PMB berhasil diunggah.');
    }

    public function downloadJadwalSeminar()
    {
        // For now, return a placeholder. In real implementation, return the actual file.
        return response()->download(public_path('path/to/jadwal_seminar.pdf'));
    }

    public function updateCpmk(Request $request, $courseId)
    {
        $request->validate([
            'cpmk' => 'required|array',
            'cpmk.*' => 'required|string',
        ]);

        // Get current lecturer
        $user = FtiData::where('username', session('username'))->first();
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 403);
        }

        // Check if lecturer teaches this course
        $course = Kurikulum::where('id', $courseId)
            ->where('dosen_id', $user->id)
            ->where('active', 1)
            ->first();

        if (!$course) {
            return response()->json(['error' => 'Anda tidak memiliki akses untuk mengedit mata kuliah ini.'], 403);
        }

        // Update CPMK
        $course->update([
            'cpmk' => array_filter($request->cpmk),
            'updated_by' => $user->id,
        ]);

        return response()->json(['success' => 'CPMK berhasil diperbarui.']);
    }

    public function getPendaftarKonversi($courseId)
    {
        // Get current lecturer
        $user = FtiData::where('username', session('username'))->first();
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 403);
        }

        // Check if lecturer teaches this course
        $course = Kurikulum::where('id', $courseId)
            ->where('dosen_id', $user->id)
            ->where('active', 1)
            ->first();

        if (!$course) {
            return response()->json(['error' => 'Anda tidak memiliki akses untuk melihat pendaftar mata kuliah ini.'], 403);
        }

        // Get pendaftar for this course
        $pendaftar = MkKonversi::with('mahasiswa')
            ->where('kurikulum_id', $courseId)
            ->where('active', '1')
            ->get();

        return response()->json($pendaftar);
    }
}
