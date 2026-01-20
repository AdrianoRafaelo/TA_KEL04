<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserRole;
use App\Models\KpCompany;
use App\Models\KpRequest;
use App\Models\KpSupervisor;
use App\Models\KpDocument;
use App\Models\KpGroup;
use App\Models\KpCvKelompok;
use App\Models\KpPerusahaan;
use App\Models\KpAktivitas;
use App\Models\KpBimbingan;
use App\Models\KpTopikKhusus;
use App\Models\KpSeminar;
use App\Models\FtiData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class KerjaPraktikController extends Controller
{
    public function index()
    {
        return view('kp.kerja_praktik');
    }

    public function dosen()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        // Get the lecturer's FTI data ID
        $lecturer = FtiData::where('username', $user['username'])->first();
        if (!$lecturer) {
            return view('kp.kerja_praktik_dosen', ['supervisedStudents' => collect()]);
        }

        // Get supervised students (KP requests assigned to this lecturer)
        $supervisedRequests = KpRequest::with(['company', 'mahasiswa'])
            ->where('dosen_id', $lecturer->id)
            ->whereIn('status', ['assigned', 'approved'])
            ->where('type', 'pengantar')
            ->get();

        // Group by company
        $supervisedStudents = $supervisedRequests->groupBy('company_id')->map(function ($requests, $companyId) {
            $company = $requests->first()->company;
            $students = $requests->map(function ($request) {
                $student = $request->mahasiswa;
                if ($student) {
                    // Get group members
                    $group = KpGroup::where('active', true)
                        ->whereJsonContains('mahasiswa', $student->username)
                        ->first();
                    $groupMembers = [];
                    if ($group) {
                        $groupMembers = FtiData::whereIn('username', $group->mahasiswa)
                            ->where('username', '!=', $student->username)
                            ->pluck('nama')
                            ->toArray();
                    }
                    $student->group_members = $groupMembers;

                    // Get bimbingan data for this student
                    $student->bimbingan = KpBimbingan::where('kp_request_id', $request->id)
                        ->where('active', 'active')
                        ->orderBy('tanggal', 'desc')
                        ->get();

                    // Get topik khusus data for this student
                    $student->topik_khusus = KpTopikKhusus::where('kp_request_id', $request->id)
                        ->where('active', 'active')
                        ->orderBy('created_at', 'desc')
                        ->get();

                    // Get log activities for this student
                    $student->aktivitas = KpAktivitas::where('mahasiswa_id', $student->username)
                        ->where('active', true)
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
                return $student;
            })->filter();
            return [
                'company' => $company,
                'students' => $students
            ];
        })->values();

        return view('kp.kerja_praktik_dosen', compact('supervisedStudents'));
    }

    public function koordinator()
    {
        // Fetch permohonan requests with relations for approval
        $permohonan_requests = KpRequest::with(['company', 'mahasiswa'])
            ->where('type', 'permohonan')
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->get();

        // Fetch pengantar requests with relations for dosen assignment
        $pengantar_requests = KpRequest::with(['company', 'mahasiswa', 'supervisor', 'dosen'])
            ->where('type', 'pengantar')
            ->whereIn('status', ['pending', 'assigned', 'approved'])
            ->get();

        // Fetch lecturers from fti_datas where role is 'lecturer'
        $lecturers = FtiData::where('role', 'lecturer')->get();

        return view('kp.kerja_praktik_koordinator', compact('permohonan_requests', 'pengantar_requests', 'lecturers'));
    }

    public function pendaftaranKp()
    {
        // Get user from session (since middleware checks session)
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('login');
        }

        // Get permohonan requests (type = 'permohonan')
        $permohonan_requests = KpRequest::with('company')
            ->where('mahasiswa_id', $user['username'])
            ->where('type', 'permohonan')
            ->get();

        // Get approved permohonan requests for download
        $approved_permohonan_requests = $permohonan_requests->where('status', 'approved');

        // Get pengantar requests (type = 'pengantar')
        $pengantar_requests = KpRequest::with('company')
            ->where('mahasiswa_id', $user['username'])
            ->where('type', 'pengantar')
            ->get();

        // Get approved pengantar requests for download
        $approved_pengantar_requests = $pengantar_requests->where('status', 'approved');

        // Get final companies (companies with approved requests)
        $final_companies = KpCompany::whereHas('requests', function($query) use ($user) {
            $query->where('mahasiswa_id', $user['username'])
                  ->where('status', 'approved');
        })->get();

        // Get all active companies from kp_perusahaans
        $perusahaans = KpPerusahaan::where('active', 1)->get();

        return view('kp.pendaftaran_kp', compact('permohonan_requests', 'approved_permohonan_requests', 'pengantar_requests', 'approved_pengantar_requests', 'final_companies', 'perusahaans'));
    }

    public function storePermohonan(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'perusahaan_id' => 'required|exists:kp_perusahaans,id',
            'waktu_awal_kp' => 'required|date',
            'waktu_selesai_kp' => 'required|date|after:waktu_awal_kp',
            'tahun_ajaran' => 'required|string|max:255',
            'mahasiswa' => 'nullable|string',
        ]);

        $perusahaan = KpPerusahaan::find($request->perusahaan_id);

        // Get user's group if exists
        $group = KpGroup::where('active', true)->whereJsonContains('mahasiswa', $user['username'])->first();
        $mahasiswa = $group ? $group->mahasiswa : [$request->mahasiswa ?: $user['username']];

        // Create company
        $company = KpCompany::create([
            'nama_perusahaan' => $perusahaan->nama_perusahaan,
            'alamat_perusahaan' => $perusahaan->alamat,
            'waktu_awal_kp' => $request->waktu_awal_kp,
            'waktu_selesai_kp' => $request->waktu_selesai_kp,
            'tahun_ajaran' => $request->tahun_ajaran,
            'mahasiswa' => json_encode($mahasiswa), // Store group members or single mahasiswa
            'created_by' => $user['username'],
        ]);

        // Create request
        KpRequest::create([
            'type' => 'permohonan',
            'company_id' => $company->id,
            'mahasiswa_id' => $user['username'],
            'status' => 'pending',
            'created_by' => $user['username'],
        ]);

        return redirect()->back()->with('success', 'Surat permohonan berhasil diajukan');
    }

    public function storePengantar(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'perusahaan_id' => 'required|exists:kp_perusahaans,id',
            'nama_supervisor' => 'required|string|max:255',
            'no_supervisor' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'surat_keterangan_diterima' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $perusahaan = KpPerusahaan::find($request->perusahaan_id);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('surat_keterangan_diterima')) {
            $file = $request->file('surat_keterangan_diterima');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('kp_supervisor', $fileName, 'public');
        }

        // Create or find company
        $company = KpCompany::firstOrCreate(
            ['nama_perusahaan' => $perusahaan->nama_perusahaan],
            [
                'alamat_perusahaan' => $perusahaan->alamat,
                'waktu_awal_kp' => now(), // Default
                'waktu_selesai_kp' => now()->addMonths(3), // Default
                'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                'mahasiswa' => json_encode([$user['username']]),
                'created_by' => $user['username'],
            ]
        );

        // Create supervisor
        $supervisor = KpSupervisor::create([
            'company_id' => $company->id,
            'nama_supervisor' => $request->nama_supervisor,
            'no_supervisor' => $request->no_supervisor,
            'file_surat_keterangan' => $filePath,
            'created_by' => $user['username'],
        ]);

        // Create request
        KpRequest::create([
            'type' => 'pengantar',
            'company_id' => $company->id,
            'supervisor_id' => $supervisor->id,
            'mahasiswa_id' => $user['username'],
            'divisi' => $request->divisi,
            'status' => 'pending',
            'created_by' => $user['username'],
        ]);

        return redirect()->back()->with('success', 'Surat pengantar berhasil diajukan');
    }

    public function editPermohonan($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $request = KpRequest::with('company')->find($id);

        if (!$request || $request->mahasiswa_id != $user['username'] || $request->type != 'permohonan' || $request->status != 'pending') {
            abort(404);
        }

        // Get all active companies from kp_perusahaans
        $perusahaans = KpPerusahaan::where('active', 1)->get();

        return view('kp.edit_permohonan', compact('request', 'perusahaans'));
    }



    public function deletePermohonan(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $kpRequest = KpRequest::find($id);

        if (!$kpRequest || $kpRequest->mahasiswa_id != $user['username'] || $kpRequest->type != 'permohonan' || $kpRequest->status != 'pending') {
            abort(404);
        }

        // Delete the request first, then associated company
        $kpRequest->delete();
        $kpRequest->company->delete();

        return redirect()->route('pendaftaran-kp')->with('success', 'Permohonan KP berhasil dihapus');
    }

    public function getPengantar($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $kpRequest = KpRequest::with('company', 'supervisor')->find($id);

        if (!$kpRequest || $kpRequest->mahasiswa_id != $user['username'] || $kpRequest->type != 'pengantar' || $kpRequest->status != 'pending') {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json([
            'perusahaan_id' => $kpRequest->company->kp_perusahaan_id ?? null,
            'nama_supervisor' => $kpRequest->supervisor->nama_supervisor ?? '',
            'no_supervisor' => $kpRequest->supervisor->no_supervisor ?? '',
            'divisi' => $kpRequest->divisi ?? '',
        ]);
    }

    public function getPermohonan($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $kpRequest = KpRequest::with('company')->find($id);

        if (!$kpRequest || $kpRequest->mahasiswa_id != $user['username'] || $kpRequest->type != 'permohonan' || $kpRequest->status != 'pending') {
            return response()->json(['error' => 'Not found'], 404);
        }

        $company = $kpRequest->company;
        $perusahaan = null;
        $alamat_perusahaan = '';
        $waktu_awal_kp = '';
        $waktu_selesai_kp = '';
        $tahun_ajaran = '';
        $mahasiswa = '';

        if ($company) {
            $perusahaan = KpPerusahaan::where('nama_perusahaan', $company->nama_perusahaan)->first();
            $alamat_perusahaan = $company->alamat_perusahaan;
            $waktu_awal_kp = $company->waktu_awal_kp;
            $waktu_selesai_kp = $company->waktu_selesai_kp;
            $tahun_ajaran = $company->tahun_ajaran;
            $mahasiswaData = $company->mahasiswa;
            if (is_string($mahasiswaData)) {
                $mahasiswaArray = json_decode($mahasiswaData, true);
                $mahasiswa = is_array($mahasiswaArray) ? ($mahasiswaArray[0] ?? '') : '';
            } else {
                $mahasiswa = is_array($mahasiswaData) ? ($mahasiswaData[0] ?? '') : $mahasiswaData;
            }
        }

        return response()->json([
            'perusahaan_id' => $perusahaan ? $perusahaan->id : null,
            'alamat_perusahaan' => $alamat_perusahaan,
            'waktu_awal_kp' => $waktu_awal_kp,
            'waktu_selesai_kp' => $waktu_selesai_kp,
            'tahun_ajaran' => $tahun_ajaran,
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function updatePermohonan(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $kpRequest = KpRequest::find($id);

        if (!$kpRequest || $kpRequest->mahasiswa_id != $user['username'] || $kpRequest->type != 'permohonan' || $kpRequest->status != 'pending') {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->validate([
            'perusahaan_id' => 'required|exists:kp_perusahaans,id',
            'waktu_awal_kp' => 'required|date',
            'waktu_selesai_kp' => 'required|date|after:waktu_awal_kp',
            'tahun_ajaran' => 'required|string|max:255',
            'mahasiswa' => 'required|string',
        ]);

        $perusahaan = KpPerusahaan::find($request->perusahaan_id);

        // Update company
        $company = $kpRequest->company;
        $company->update([
            'nama_perusahaan' => $perusahaan->nama_perusahaan,
            'alamat_perusahaan' => $perusahaan->alamat,
            'waktu_awal_kp' => $request->waktu_awal_kp,
            'waktu_selesai_kp' => $request->waktu_selesai_kp,
            'tahun_ajaran' => $request->tahun_ajaran,
            'mahasiswa' => json_encode([$request->mahasiswa]),
        ]);

        return response()->json(['success' => true, 'message' => 'Permohonan KP berhasil diperbarui']);
    }

    public function updatePengantar(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $kpRequest = KpRequest::find($id);

        if (!$kpRequest || $kpRequest->mahasiswa_id != $user['username'] || $kpRequest->type != 'pengantar' || $kpRequest->status != 'pending') {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->validate([
            'perusahaan_id' => 'required|exists:kp_perusahaans,id',
            'nama_supervisor' => 'required|string|max:255',
            'no_supervisor' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'surat_keterangan_diterima' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $perusahaan = KpPerusahaan::find($request->perusahaan_id);

        // Handle file upload
        $filePath = $kpRequest->supervisor->file_surat_keterangan;
        if ($request->hasFile('surat_keterangan_diterima')) {
            // Delete old file if exists
            if ($filePath) {
                Storage::delete('public/' . $filePath);
            }
            $file = $request->file('surat_keterangan_diterima');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('kp_supervisor', $fileName, 'public');
        }

        // Update company
        $company = $kpRequest->company;
        $company->update([
            'nama_perusahaan' => $perusahaan->nama_perusahaan,
            'alamat_perusahaan' => $perusahaan->alamat,
        ]);

        // Update request
        $kpRequest->divisi = $request->divisi;
        $kpRequest->save();

        // Update supervisor
        $supervisor = $kpRequest->supervisor;
        $supervisor->update([
            'nama_supervisor' => $request->nama_supervisor,
            'no_supervisor' => $request->no_supervisor,
            'file_surat_keterangan' => $filePath,
        ]);

        return response()->json(['success' => true, 'message' => 'Surat pengantar berhasil diperbarui']);
    }

    public function deletePengantar(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $kpRequest = KpRequest::find($id);

        if (!$kpRequest || $kpRequest->mahasiswa_id != $user['username'] || $kpRequest->type != 'pengantar' || $kpRequest->status != 'pending') {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Delete the request first
        $kpRequest->delete();
        // Then delete supervisor
        $kpRequest->supervisor->delete();
        // Then delete company if no other requests use it
        if (KpRequest::where('company_id', $kpRequest->company_id)->count() == 0) {
            $kpRequest->company->delete();
        }

        return response()->json(['success' => true, 'message' => 'Surat pengantar berhasil dihapus']);
    }

    public function downloadPermohonan($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $request = KpRequest::with('company')->find($id);

        if (!$request || $request->mahasiswa_id != $user['username'] || $request->type != 'permohonan' || $request->status != 'approved') {
            abort(404);
        }

        $pdf = Pdf::loadView('kp.surat_permohonan_pdf', compact('request', 'user'));

        return $pdf->download('surat_permohonan_kp_' . $request->id . '.pdf');
    }

    public function downloadPengantar($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $request = KpRequest::with(['company', 'supervisor'])->find($id);

        if (!$request || $request->mahasiswa_id != $user['username'] || $request->type != 'pengantar' || $request->status != 'approved') {
            abort(404);
        }

        $pdf = Pdf::loadView('kp.surat_pengantar_pdf', compact('request', 'user'));

        return $pdf->download('surat_pengantar_kp_' . $request->id . '.pdf');
    }

    public function assignDosen(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:kp_requests,id',
            'dosen_id' => 'required|exists:fti_datas,id',
            'divisi' => 'nullable|string|max:255',
        ]);

        $kpRequest = KpRequest::find($request->request_id);
        $kpRequest->dosen_id = $request->dosen_id;
        $kpRequest->divisi = $request->divisi;
        $kpRequest->status = 'approved'; // Update status to approved (include approval)
        $kpRequest->save();

        return redirect()->back()->with('success', 'Dosen pembimbing berhasil ditentukan dan surat pengantar disetujui');
    }

    public function approvePermohonan(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:kp_requests,id',
        ]);

        $kpRequest = KpRequest::find($request->request_id);
        $kpRequest->status = 'approved'; // Update status to approved
        $kpRequest->save();

        return redirect()->back()->with('success', 'Permohonan KP berhasil disetujui');
    }

    public function rejectPermohonan(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:kp_requests,id',
        ]);

        $kpRequest = KpRequest::find($request->request_id);
        $kpRequest->status = 'rejected'; // Update status to rejected
        $kpRequest->save();

        return redirect()->back()->with('success', 'Permohonan KP berhasil ditolak');
    }

    public function approvePengantar(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:kp_requests,id',
        ]);

        $kpRequest = KpRequest::find($request->request_id);
        $kpRequest->status = 'approved'; // Update status to approved
        $kpRequest->save();

        return redirect()->back()->with('success', 'Surat pengantar KP berhasil disetujui');
    }

    public function daftarKelompok(Request $request)
    {
        $request->validate([
            'mahasiswa' => 'required|array|min:1',
            'mahasiswa.*' => 'required|string|exists:fti_datas,username'
        ]);

        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        // Tambahkan user yang membuat kelompok ke dalam array mahasiswa jika belum ada
        $mahasiswa = $request->mahasiswa;
        if (!in_array($user['username'], $mahasiswa)) {
            $mahasiswa[] = $user['username'];
        }

        // Cek apakah ada mahasiswa yang sudah terdaftar di kelompok lain
        $existingMahasiswa = KpGroup::where('active', true)
            ->where(function($query) use ($mahasiswa) {
                foreach ($mahasiswa as $mhs) {
                    $query->orWhereJsonContains('mahasiswa', $mhs);
                }
            })
            ->exists();

        if ($existingMahasiswa) {
            return response()->json(['success' => false, 'message' => 'Salah satu mahasiswa sudah terdaftar di kelompok lain']);
        }

        // Generate nama kelompok otomatis berdasarkan mahasiswa pertama
        $firstMahasiswa = FtiData::where('username', $mahasiswa[0])->first();
        $namaKelompok = 'Kelompok ' . ($firstMahasiswa ? $firstMahasiswa->nama : $mahasiswa[0]);

        // Buat kelompok baru
        KpGroup::create([
            'nama_kelompok' => $namaKelompok,
            'mahasiswa' => $mahasiswa,
            'created_by' => $user['username'] ?? $user['id'],
            'updated_by' => $user['username'] ?? $user['id'],
            'active' => true
        ]);

        return response()->json(['success' => true, 'message' => 'Kelompok KP berhasil dibuat']);
    }

    public function getKpGroups()
    {
        $groups = KpGroup::where('active', true)->get();

        $formattedGroups = $groups->map(function ($group) {
            $mahasiswaNames = [];
            foreach ($group->mahasiswa as $username) {
                $mahasiswa = FtiData::where('username', $username)->first();
                $mahasiswaNames[] = $mahasiswa ? $mahasiswa->nama : $username;
            }

            return [
                'id' => $group->id,
                'nama_kelompok' => $group->nama_kelompok,
                'mahasiswa' => $mahasiswaNames,
                'jumlah_mahasiswa' => count($group->mahasiswa),
                'created_at' => $group->created_at->format('d/m/Y'),
            ];
        });

        return response()->json($formattedGroups);
    }

    public function uploadCv(Request $request)
    {
        $request->validate([
            'kp_group_id' => 'required|exists:kp_groups,id',
            'cv_files' => 'required|array',
            'cv_files.*' => 'required|file|mimes:pdf|max:5120', // 5MB max
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|string|exists:fti_datas,username',
        ]);

        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        // Cek apakah user adalah bagian dari kelompok
        $group = KpGroup::find($request->kp_group_id);
        if (!$group || !in_array($user['username'], $group->mahasiswa)) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke kelompok ini']);
        }

        $uploaded = [];
        foreach ($request->user_ids as $index => $userId) {
            if (isset($request->cv_files[$index])) {
                $file = $request->cv_files[$index];

                // Generate unique filename
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $userId . '_' . uniqid() . '.pdf';
                $path = $file->storeAs('cv_kelompok', $fileName, 'public');

                // Simpan ke database
                KpCvKelompok::create([
                    'kp_group_id' => $request->kp_group_id,
                    'user_id' => $userId,
                    'file_path' => $path,
                    'file_name' => $fileName,
                    'original_name' => $originalName,
                ]);

                $uploaded[] = $userId;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'CV berhasil diunggah untuk ' . count($uploaded) . ' mahasiswa',
            'uploaded' => $uploaded
        ]);
    }

    public function getCvKelompok($groupId)
    {
        $cvs = KpCvKelompok::where('kp_group_id', $groupId)
            ->with('mahasiswa')
            ->get()
            ->map(function ($cv) {
                return [
                    'user_id' => $cv->user_id,
                    'nama' => $cv->mahasiswa->nama ?? $cv->user_id,
                    'nim' => $cv->mahasiswa->nim ?? '',
                    'cv' => true,
                    'file_path' => $cv->file_path,
                    'original_name' => $cv->original_name,
                ];
            });

        return response()->json($cvs);
    }

    public function getUserGroup()
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        $group = KpGroup::where('active', true)
            ->whereJsonContains('mahasiswa', $user['username'])
            ->first();

        if (!$group) {
            return response()->json(['success' => false, 'message' => 'Anda belum terdaftar dalam kelompok KP']);
        }

        $mahasiswa = [];
        foreach ($group->mahasiswa as $username) {
            $m = FtiData::where('username', $username)->first();
            if ($m) {
                $mahasiswa[] = [
                    'nama' => $m->nama,
                    'nim' => $m->nim,
                    'username' => $m->username,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'group' => [
                'id' => $group->id,
                'nama_kelompok' => $group->nama_kelompok,
                'mahasiswa' => $mahasiswa,
            ]
        ]);
    }

    public function getPerusahaans()
    {
        $perusahaans = KpPerusahaan::where('active', 1)->get();

        return response()->json($perusahaans);
    }

    public function storePerusahaan(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:255',
        ]);

        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        KpPerusahaan::create([
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'created_by' => null,
            'updated_by' => null,
            'active' => 1,
        ]);

        return response()->json(['success' => true, 'message' => 'Perusahaan berhasil ditambahkan']);
    }

    public function mahasiswaPelaksanaanKp()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        // Get approved KP request for the current student
        $kpRequest = KpRequest::with(['company', 'supervisor', 'dosen'])
            ->where('mahasiswa_id', $user['username'])
            ->whereIn('status', ['approved', 'assigned'])
            ->where('type', 'pengantar')
            ->first();

        // Get student data
        $student = FtiData::where('username', $user['username'])->first();

        // Get log activities
        $aktivitas = KpAktivitas::where('mahasiswa_id', $user['username'])
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get bimbingan data
        $bimbingan = KpBimbingan::where('kp_request_id', $kpRequest->id ?? null)
            ->where('active', 'active')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Get topik khusus data
        $topikKhusus = KpTopikKhusus::where('kp_request_id', $kpRequest->id ?? null)
            ->where('active', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate progress based on log activities (assuming 16 weeks total)
        $totalWeeks = 16;
        $currentWeek = $aktivitas->count(); // Each activity represents a week
        $progressPercentage = min(($currentWeek / $totalWeeks) * 100, 100);

        // Get group members (excluding current user)
        $groupMembers = collect();
        $group = KpGroup::where('active', true)
            ->whereJsonContains('mahasiswa', $user['username'])
            ->first();

        if ($group) {
            $groupMembers = FtiData::whereIn('username', $group->mahasiswa)
                ->where('username', '!=', $user['username'])
                ->get();
        }

        return view('kp.mahasiswa_pelaksanaan_kp', compact('kpRequest', 'student', 'user', 'aktivitas', 'groupMembers', 'bimbingan', 'topikKhusus', 'progressPercentage', 'currentWeek', 'totalWeeks'));
    }

    public function koordinatorPelaksanaanKp()
    {
        // Get all students with approved KP requests who are in execution phase
        $approvedRequests = KpRequest::with(['company', 'mahasiswa', 'dosen'])
            ->where('type', 'pengantar')
            ->whereIn('status', ['approved', 'assigned'])
            ->get();

        $pelaksanaan_kp = $approvedRequests->map(function ($request) {
            $student = $request->mahasiswa;
            $company = $request->company;
            $dosen = $request->dosen;

            if (!$student) return null;

            // Get log activities count for current week calculation
            $aktivitasCount = KpAktivitas::where('mahasiswa_id', $student->username)
                ->where('active', true)
                ->count();

            // Get bimbingan count
            $bimbinganCount = KpBimbingan::where('kp_request_id', $request->id)
                ->where('active', 'active')
                ->count();

            // Calculate current week (assuming each activity represents a week)
            $currentWeek = min($aktivitasCount, 16); // Max 16 weeks

            return (object) [
                'id' => $request->id,
                'mahasiswa' => $student->nama,
                'username' => $student->username,
                'perusahaan' => $company->nama_perusahaan ?? 'N/A',
                'minggu' => $currentWeek,
                'bimbingan' => $bimbinganCount,
                'dosen' => $dosen->nama ?? 'Belum ditentukan',
                'aktivitas' => KpAktivitas::where('mahasiswa_id', $student->username)
                    ->where('active', true)
                    ->orderBy('created_at', 'desc')
                    ->get()
            ];
        })->filter()->values();

        return view('kp.koordinator_pelaksanaan_kp', compact('pelaksanaan_kp'));
    }

    public function koordinatorSeminarKp()
    {
        // Get all students with approved KP requests who have registered for seminar
        $approvedRequests = KpRequest::with(['company', 'mahasiswa', 'dosen', 'seminar'])
            ->where('type', 'pengantar')
            ->whereIn('status', ['approved', 'assigned'])
            ->whereHas('seminar', function($q) {
                $q->where('active', true);
            })
            ->get();

        // Get lecturers from fti_datas where role is 'lecturer'
        $lecturers = FtiData::where('role', 'lecturer')->get();

        $seminarKp = $approvedRequests->map(function ($request) {
            $student = $request->mahasiswa;
            $company = $request->company;
            $dosen = $request->dosen;
            $seminar = $request->seminar;

            if (!$student) return null;

            return (object) [
                'id' => $request->id,
                'nama' => $student->nama,
                'nim' => $student->nim,
                'perusahaan' => $company->nama_perusahaan ?? 'N/A',
                'pembimbing' => $dosen->nama ?? 'Belum ditentukan',
                'penguji' => $seminar ? $seminar->penguji : null,
                'file_laporan_kp' => $seminar ? $seminar->file_laporan_kp : null,
                'file_krs_anggota' => $seminar ? $seminar->file_krs_anggota : null,
                'file_surat_persetujuan' => $seminar ? $seminar->file_surat_persetujuan : null,
                'file_lembar_konfirmasi' => $seminar ? $seminar->file_lembar_konfirmasi : null,
                'jadwal_seminar_file' => $seminar ? $seminar->jadwal_seminar_file : null,
                'status' => $seminar ? $seminar->status : 'pending'
            ];
        })->filter()->values();

        return view('kp.koordinator_seminar', compact('seminarKp', 'lecturers'));
    }



    public function storeLogActivity(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'aktivitas_id' => 'nullable|exists:kp_aktivitas,id'
        ]);

        $aktivitas = null;
        if ($request->aktivitas_id) {
            $aktivitas = KpAktivitas::where('id', $request->aktivitas_id)
                ->where('mahasiswa_id', $user['username'])
                ->first();

            if (!$aktivitas) {
                return response()->json(['success' => false, 'message' => 'Aktivitas tidak ditemukan']);
            }
        }

        $filePath = null;
        $fileName = null;
        $originalName = null;

        if ($request->hasFile('file')) {
            // Delete old file if updating
            if ($aktivitas && $aktivitas->file_path) {
                Storage::delete('public/' . $aktivitas->file_path);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $user['username'] . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('kp_aktivitas', $fileName, 'public');
        }

        if ($aktivitas) {
            // Update
            $aktivitas->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_path' => $filePath ?: $aktivitas->file_path,
                'file_name' => $fileName ?: $aktivitas->file_name,
                'original_name' => $originalName ?: $aktivitas->original_name,
                'updated_by' => $user['username'],
            ]);
        } else {
            // Create
            KpAktivitas::create([
                'mahasiswa_id' => $user['username'],
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'original_name' => $originalName,
                'created_by' => $user['username'],
                'active' => true,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Log activity berhasil disimpan']);
    }

    public function getLogActivities()
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        $aktivitas = KpAktivitas::where('mahasiswa_id', $user['username'])
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $aktivitas]);
    }

    public function viewLogActivity($id)
    {
        $user = Session::get('user');
        if (!$user) abort(403);

        $aktivitas = KpAktivitas::find($id);

        if (!$aktivitas || !$aktivitas->file_path) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $aktivitas->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File fisik tidak ada di server');
        }

        // Return file for inline viewing
        $filename = $aktivitas->original_name ?: $aktivitas->file_name ?: basename($aktivitas->file_path);
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function downloadLogActivity($id)
    {
        $user = Session::get('user');
        if (!$user) abort(403);

        // Hanya koordinator & admin
        $userData = FtiData::where('username', $user['username'])->first();
        if (!$userData || !in_array($userData->role, ['koordinator', 'admin', 'Koordinator', 'Admin'])) {
            abort(403);
        }

        $aktivitas = KpAktivitas::find($id);
        if (!$aktivitas || !$aktivitas->file_path) {
            abort(404, 'File tidak ditemukan');
        }

        $fullPath = storage_path('app/public/' . $aktivitas->file_path);

        if (!file_exists($fullPath)) {
            abort(404, 'File fisik tidak ada di server');
        }

        $downloadName = $aktivitas->original_name
            ? $aktivitas->original_name
            : pathinfo($aktivitas->file_path, PATHINFO_BASENAME);

        return response()->download($fullPath, $downloadName, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function previewLogActivity($id)
    {
        $user = Session::get('user');
        if (!$user) {
            abort(403);
        }

        // Check if user is coordinator or admin
        $userData = FtiData::where('username', $user['username'])->first();
        $allowedRoles = ['Koordinator', 'Admin'];
        if (!$userData || !in_array($userData->role, $allowedRoles)) {
            abort(403);
        }

        $aktivitas = KpAktivitas::where('id', $id)->first();

        if (!$aktivitas) {
            abort(404);
        }

        if ($aktivitas->file_path) {
            $filePath = storage_path('app/public/' . $aktivitas->file_path);

            if (file_exists($filePath)) {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $aktivitas->original_name . '"'
                ]);
            }
        }

        abort(404);
    }

    public function storeInformasiKp(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'dokumen_*' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'expired_*' => 'nullable|date',
        ]);

        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        // Here you can store the informasi in a database table, e.g., KpInformasi
        // For now, just return success

        return response()->json(['success' => true, 'message' => 'Informasi KP berhasil disimpan']);
    }

    public function seminarmhs()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        // Get student data
        $student = FtiData::where('username', $user['username'])->first();

        // Get KP request data
        $kpRequest = KpRequest::with(['company', 'supervisor', 'dosen'])
            ->where('mahasiswa_id', $user['username'])
            ->whereIn('status', ['approved', 'assigned'])
            ->where('type', 'pengantar')
            ->first();

        // Get topik khusus
        $topikKhusus = KpTopikKhusus::where('kp_request_id', $kpRequest->id ?? null)
            ->where('active', 'active')
            ->where('status', 'diterima')
            ->first();

        // Get bimbingan count
        $totalBimbingan = KpBimbingan::where('kp_request_id', $kpRequest->id ?? null)
            ->where('active', 'active')
            ->count();

        $selesaiBimbingan = KpBimbingan::where('kp_request_id', $kpRequest->id ?? null)
            ->where('active', 'active')
            ->where('status', 'diterima')
            ->count();

        // Get group information
        $group = KpGroup::where('active', true)
            ->whereJsonContains('mahasiswa', $user['username'])
            ->first();

        $groupMembers = collect();
        if ($group) {
            $groupMembers = FtiData::whereIn('username', $group->mahasiswa)
                ->where('username', '!=', $user['username'])
                ->get(['nama', 'nim']);
        }

        // Get seminar data
        $seminar = KpSeminar::where('kp_request_id', $kpRequest->id ?? null)
            ->where('active', true)
            ->first();

        return view('kp.mahasiswa_seminar', compact('student', 'kpRequest', 'topikKhusus', 'totalBimbingan', 'selesaiBimbingan', 'group', 'groupMembers', 'seminar'));
    }

    public function storeBimbingan(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:1000',
            'jenis' => 'required|in:sebelum_kp,sesudah_kp',
        ]);

        // Get KP request for the user
        $kpRequest = KpRequest::where('mahasiswa_id', $user['username'])
            ->whereIn('status', ['approved', 'assigned'])
            ->where('type', 'pengantar')
            ->first();

        if (!$kpRequest) {
            return response()->json(['success' => false, 'message' => 'KP request tidak ditemukan']);
        }

        KpBimbingan::create([
            'kp_request_id' => $kpRequest->id,
            'tanggal' => $request->tanggal,
            'topik' => $request->topik,
            'jenis' => $request->jenis,
            'status' => 'menunggu',
            'created_by' => $user['username'],
            'active' => 'active',
        ]);

        return response()->json(['success' => true, 'message' => 'Bimbingan berhasil diajukan']);
    }

    public function storeTopikKhusus(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        $request->validate([
            'topik' => 'required|string|max:1000',
        ]);

        // Get KP request for the user
        $kpRequest = KpRequest::where('mahasiswa_id', $user['username'])
            ->whereIn('status', ['approved', 'assigned'])
            ->where('type', 'pengantar')
            ->first();

        if (!$kpRequest) {
            return response()->json(['success' => false, 'message' => 'KP request tidak ditemukan']);
        }

        KpTopikKhusus::create([
            'kp_request_id' => $kpRequest->id,
            'topik' => $request->topik,
            'status' => 'menunggu',
            'created_by' => $user['username'],
            'active' => 'active',
        ]);

        return response()->json(['success' => true, 'message' => 'Topik khusus berhasil diajukan']);
    }

    public function approveBimbingan(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kp_bimbingans,id',
            'action' => 'required|in:approve,reject',
        ]);

        $bimbingan = KpBimbingan::find($request->id);
        $bimbingan->status = $request->action === 'approve' ? 'diterima' : 'ditolak';
        $bimbingan->save();

        return response()->json(['success' => true, 'message' => 'Status bimbingan berhasil diperbarui']);
    }

    public function approveTopikKhusus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kp_topik_khususs,id',
            'action' => 'required|in:approve,reject',
        ]);

        $topik = KpTopikKhusus::find($request->id);
        $topik->status = $request->action === 'approve' ? 'diterima' : 'ditolak';
        $topik->save();

        return response()->json(['success' => true, 'message' => 'Status topik khusus berhasil diperbarui']);
    }

    public function getStudentLogActivities($username)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        $aktivitas = KpAktivitas::where('mahasiswa_id', $username)
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $aktivitas = $aktivitas->map(function ($activity) {
            $activity->file_exists = $activity->file_path ? file_exists(storage_path('app/public/') . $activity->file_path) : false;
            return $activity;
        });

        return response()->json(['success' => true, 'data' => $aktivitas]);
    }

    // Placeholder methods for KP Seminar functionality
    // These will need to be implemented when KpSeminar model is created

    public function updateSeminarKpPenguji(Request $request)
    {
        $request->validate([
            'seminar_id' => 'required|exists:kp_requests,id',
            'field' => 'required|in:penguji',
            'value' => 'nullable|string',
        ]);

        $seminar = KpSeminar::where('kp_request_id', $request->seminar_id)->where('active', true)->first();

        if (!$seminar) {
            // Create seminar record if not exists
            $kpRequest = KpRequest::find($request->seminar_id);
            if (!$kpRequest) {
                return response()->json(['success' => false, 'message' => 'KP Request tidak ditemukan']);
            }
            $seminar = KpSeminar::create([
                'kp_request_id' => $request->seminar_id,
                'mahasiswa_id' => $kpRequest->mahasiswa_id,
                'status' => 'pending',
                'active' => true,
                'created_by' => auth()->id() ?? 'system',
                'updated_by' => auth()->id() ?? 'system',
            ]);
        }

        $seminar->update([$request->field => $request->value]);

        return response()->json(['success' => true, 'message' => 'Penguji berhasil diperbarui']);
    }

    public function uploadJadwalSeminarKp(Request $request)
    {
        $request->validate([
            'seminar_id' => 'required|exists:kp_requests,id',
            'jadwal_seminar_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $seminar = KpSeminar::where('kp_request_id', $request->seminar_id)->where('active', true)->first();

        if (!$seminar) {
            return response()->json(['success' => false, 'message' => 'Seminar tidak ditemukan']);
        }

        $file = $request->file('jadwal_seminar_file');
        $filename = 'jadwal_seminar_' . time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->makeDirectory('seminar');
        Storage::disk('public')->put('seminar/' . $filename, file_get_contents($file));

        $seminar->update(['jadwal_seminar_file' => 'seminar/' . $filename]);

        return response()->json([
            'success' => true,
            'message' => 'File jadwal seminar berhasil diunggah',
            'file_path' => asset('storage/seminar/' . $filename)
        ]);
    }

    public function approveSeminarKp(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kp_requests,id',
        ]);

        $seminars = KpSeminar::whereIn('kp_request_id', $request->ids)->where('active', true)->get();

        foreach ($seminars as $seminar) {
            $seminar->update(['status' => 'approved']);
        }

        return response()->json(['success' => true, 'message' => count($request->ids) . ' seminar berhasil disetujui']);
    }

    public function uploadSeminarKpDokumen(Request $request)
    {
        // TODO: Implement when KpSeminar model is created
        return response()->json(['success' => true, 'message' => 'Dokumen berhasil diunggah']);
    }

    // Seminar KP Methods
    public function storeSeminarKp(Request $request)
    {
        $request->validate([
            'kp_request_id' => 'required|exists:kp_requests,id',
            'file_laporan_kp' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'file_penilaian_perusahaan' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'file_surat_kp' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'file_krs_anggota' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'file_surat_persetujuan' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'file_lembar_konfirmasi' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        // Check if seminar already exists
        $seminar = KpSeminar::where('kp_request_id', $request->kp_request_id)
            ->where('active', true)
            ->first();

        $data = [
            'kp_request_id' => $request->kp_request_id,
            'mahasiswa_id' => $user['username'],
            'status' => 'pending',
            'created_by' => $user['username'],
            'updated_by' => $user['username'],
        ];

        // Handle file uploads
        if ($request->hasFile('file_laporan_kp')) {
            $file = $request->file('file_laporan_kp');
            $filename = 'laporan_kp_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->makeDirectory('seminar');
            Storage::disk('public')->put('seminar/' . $filename, file_get_contents($file));
            $data['file_laporan_kp'] = 'seminar/' . $filename;
        }

        if ($request->hasFile('file_penilaian_perusahaan')) {
            $file = $request->file('file_penilaian_perusahaan');
            $filename = 'penilaian_perusahaan_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->makeDirectory('seminar');
            Storage::disk('public')->put('seminar/' . $filename, file_get_contents($file));
            $data['file_penilaian_perusahaan'] = 'seminar/' . $filename;
        }

        if ($request->hasFile('file_surat_kp')) {
            $file = $request->file('file_surat_kp');
            $filename = 'surat_kp_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->makeDirectory('seminar');
            Storage::disk('public')->put('seminar/' . $filename, file_get_contents($file));
            $data['file_surat_kp'] = 'seminar/' . $filename;
        }

        if ($request->hasFile('file_krs_anggota')) {
            $file = $request->file('file_krs_anggota');
            $filename = 'krs_anggota_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->makeDirectory('seminar');
            Storage::disk('public')->put('seminar/' . $filename, file_get_contents($file));
            $data['file_krs_anggota'] = 'seminar/' . $filename;
        }

        if ($request->hasFile('file_surat_persetujuan')) {
            $file = $request->file('file_surat_persetujuan');
            $filename = 'surat_persetujuan_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->makeDirectory('seminar');
            Storage::disk('public')->put('seminar/' . $filename, file_get_contents($file));
            $data['file_surat_persetujuan'] = 'seminar/' . $filename;
        }

        if ($request->hasFile('file_lembar_konfirmasi')) {
            $file = $request->file('file_lembar_konfirmasi');
            $filename = 'lembar_konfirmasi_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->makeDirectory('seminar');
            Storage::disk('public')->put('seminar/' . $filename, file_get_contents($file));
            $data['file_lembar_konfirmasi'] = 'seminar/' . $filename;
        }

        if ($seminar) {
            $seminar->update($data);
        } else {
            KpSeminar::create($data);
        }

        return response()->json(['success' => true, 'message' => 'Dokumen seminar berhasil diunggah']);
    }

    public function updateSeminarKpStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kp_seminars,id',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        $seminar = KpSeminar::find($request->id);
        $seminar->update([
            'status' => $request->status,
            'updated_by' => $user['username']
        ]);

        return response()->json(['success' => true, 'message' => 'Status seminar berhasil diperbarui']);
    }

    public function dosenSeminarKp()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        // Get the lecturer's FTI data ID
        $lecturer = FtiData::where('username', $user['username'])->first();
        if (!$lecturer) {
            return view('kp.dosen_seminar', ['seminarKp' => collect()]);
        }

        // Get supervised students with seminar data
        $seminarKp = KpRequest::with(['company', 'mahasiswa', 'dosen', 'seminar', 'topikKhusus'])
            ->where('dosen_id', $lecturer->id)
            ->where('type', 'pengantar')
            ->whereIn('status', ['approved', 'assigned'])
            ->whereHas('seminar', function($q) {
                $q->where('status', 'approved');
            })
            ->get()
            ->map(function ($request) {
                $student = $request->mahasiswa;
                $company = $request->company;
                $dosen = $request->dosen;
                $seminar = $request->seminar;
                $topikKhusus = $request->topikKhusus->where('active', 'active')->where('status', 'diterima')->first();

                if (!$student) return null;

                // Get group members
                $group = KpGroup::where('active', true)
                    ->whereJsonContains('mahasiswa', $student->username)
                    ->first();
                $groupMembers = [];
                if ($group) {
                    $groupMembers = FtiData::whereIn('username', $group->mahasiswa)
                        ->where('username', '!=', $student->username)
                        ->whereNotNull('nama')
                        ->where('nama', '!=', '')
                        ->get()
                        ->map(function ($member) {
                            return $member->nama . ' (' . $member->nim . ')';
                        })
                        ->toArray();
                }

                return (object) [
                    'nama' => $student->nama,
                    'nim' => $student->nim,
                    'anggota_kelompok' => $groupMembers,
                    'perusahaan' => $company->nama_perusahaan ?? 'N/A',
                    'topik_khusus' => $topikKhusus ? $topikKhusus->topik : 'Belum ada',
                    'pembimbing' => $dosen->nama ?? 'Belum ditentukan',
                    'penguji' => $seminar ? $seminar->penguji : 'Belum ditentukan',
                    'jadwal_seminar' => $seminar ? $seminar->jadwal_seminar_file : null,
                ];
            })->filter()->values();

        return view('kp.dosen_seminar', compact('seminarKp'));
    }

    public function downloadSeminarFile($filename)
    {
        $path = storage_path('app/public/seminar/' . $filename);
        if (file_exists($path)) {
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }
        return abort(404);
    }

    public function deleteSeminarFile(Request $request)
    {
        $request->validate([
            'kp_request_id' => 'required|exists:kp_requests,id',
            'field' => 'required|in:file_laporan_kp,file_penilaian_perusahaan,file_surat_kp,file_krs_anggota,file_surat_persetujuan,file_lembar_konfirmasi',
        ]);

        $user = Session::get('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi']);
        }

        $seminar = KpSeminar::where('kp_request_id', $request->kp_request_id)
            ->where('mahasiswa_id', $user['username'])
            ->where('active', true)
            ->first();

        if (!$seminar) {
            return response()->json(['success' => false, 'message' => 'Seminar tidak ditemukan']);
        }

        $filePath = $seminar->{$request->field};
        if ($filePath) {
            // Delete file from storage
            Storage::disk('public')->delete($filePath);
            // Update database
            $seminar->update([$request->field => null]);
        }

        return response()->json(['success' => true, 'message' => 'File berhasil dihapus']);
    }

    public function seminardosen()
    {
        return view('kp.dosen_seminar');
    }
}
