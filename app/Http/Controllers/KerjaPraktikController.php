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
        return view('kp.kerja_praktik_dosen');
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
            ->whereIn('status', ['pending', 'approved', 'assigned'])
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
            ->where('status', 'approved')
            ->get();

        // Get final companies (companies with approved requests)
        $final_companies = KpCompany::whereHas('requests', function($query) use ($user) {
            $query->where('mahasiswa_id', $user['username'])
                  ->where('status', 'approved');
        })->get();

        // Get all active companies from kp_perusahaans
        $perusahaans = KpPerusahaan::where('active', 1)->get();

        return view('kp.pendaftaran_kp', compact('permohonan_requests', 'approved_permohonan_requests', 'pengantar_requests', 'final_companies', 'perusahaans'));
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
            'mahasiswa' => 'required|string',
        ]);

        $perusahaan = KpPerusahaan::find($request->perusahaan_id);

        // Create company
        $company = KpCompany::create([
            'nama_perusahaan' => $perusahaan->nama_perusahaan,
            'alamat_perusahaan' => $perusahaan->alamat,
            'waktu_awal_kp' => $request->waktu_awal_kp,
            'waktu_selesai_kp' => $request->waktu_selesai_kp,
            'tahun_ajaran' => $request->tahun_ajaran,
            'mahasiswa' => json_encode([$request->mahasiswa]), // Store as JSON array
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
        ]);

        $perusahaan = KpPerusahaan::find($request->perusahaan_id);

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
            'created_by' => $user['username'],
        ]);

        // Create request
        KpRequest::create([
            'type' => 'pengantar',
            'company_id' => $company->id,
            'supervisor_id' => $supervisor->id,
            'mahasiswa_id' => $user['username'],
            'status' => 'pending',
            'created_by' => $user['username'],
        ]);

        return redirect()->back()->with('success', 'Surat pengantar berhasil diajukan');
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
        $kpRequest->status = 'assigned'; // Update status to assigned
        $kpRequest->save();

        return redirect()->back()->with('success', 'Dosen pembimbing berhasil ditentukan');
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

        // Cek apakah ada mahasiswa yang sudah terdaftar di kelompok lain
        $existingMahasiswa = KpGroup::where('active', true)
            ->where(function($query) use ($request) {
                foreach ($request->mahasiswa as $mahasiswa) {
                    $query->orWhereJsonContains('mahasiswa', $mahasiswa);
                }
            })
            ->exists();

        if ($existingMahasiswa) {
            return response()->json(['success' => false, 'message' => 'Salah satu mahasiswa sudah terdaftar di kelompok lain']);
        }

        // Generate nama kelompok otomatis berdasarkan mahasiswa pertama
        $firstMahasiswa = FtiData::where('username', $request->mahasiswa[0])->first();
        $namaKelompok = 'Kelompok ' . ($firstMahasiswa ? $firstMahasiswa->nama : $request->mahasiswa[0]);

        // Buat kelompok baru
        KpGroup::create([
            'nama_kelompok' => $namaKelompok,
            'mahasiswa' => $request->mahasiswa,
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
            'created_by' => $user['username'],
            'updated_by' => $user['username'],
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
            ->where('status', 'approved')
            ->where('type', 'pengantar') // Get pengantar request which has supervisor info
            ->first();

        // Get student data
        $student = FtiData::where('username', $user['username'])->first();

        return view('kp.mahasiswa_pelaksanaan_kp', compact('kpRequest', 'student', 'user'));
    }

    public function koordinatorPelaksanaanKp()
    {
        // Mock data for pelaksanaan KP - in real implementation, this would come from database
        $pelaksanaan_kp = [];

        return view('kp.koordinator_pelaksanaan_kp', compact('pelaksanaan_kp'));
    }
}
