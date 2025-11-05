<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserRole;
use App\Models\KpCompany;
use App\Models\KpRequest;
use App\Models\KpSupervisor;
use App\Models\KpDocument;
use App\Models\FtiData;
use Illuminate\Support\Facades\Auth;
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
            ->whereIn('status', ['pending', 'approved'])
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
            ->where('mahasiswa_id', $user['id'])
            ->where('type', 'permohonan')
            ->get();

        // Get approved permohonan requests for download
        $approved_permohonan_requests = $permohonan_requests->where('status', 'approved');

        // Get pengantar requests (type = 'pengantar')
        $pengantar_requests = KpRequest::with('company')
            ->where('mahasiswa_id', $user['id'])
            ->where('type', 'pengantar')
            ->where('status', 'approved')
            ->get();

        // Get final companies (companies with approved requests)
        $final_companies = KpCompany::whereHas('requests', function($query) use ($user) {
            $query->where('mahasiswa_id', $user['id'])
                  ->where('status', 'approved');
        })->get();

        return view('kp.pendaftaran_kp', compact('permohonan_requests', 'approved_permohonan_requests', 'pengantar_requests', 'final_companies'));
    }

    public function storePermohonan(Request $request)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'waktu_awal_kp' => 'required|date',
            'waktu_selesai_kp' => 'required|date|after:waktu_awal_kp',
            'tahun_ajaran' => 'required|string|max:255',
            'mahasiswa' => 'required|string',
        ]);

        // Create company
        $company = KpCompany::create([
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'waktu_awal_kp' => $request->waktu_awal_kp,
            'waktu_selesai_kp' => $request->waktu_selesai_kp,
            'tahun_ajaran' => $request->tahun_ajaran,
            'mahasiswa' => json_encode([$request->mahasiswa]), // Store as JSON array
            'created_by' => $user['id'],
        ]);

        // Create request
        KpRequest::create([
            'type' => 'permohonan',
            'company_id' => $company->id,
            'mahasiswa_id' => $user['id'] ?? 1, // Fallback to admin user if no user id
            'status' => 'pending',
            'created_by' => $user['id'] ?? 1,
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
            'company_id' => 'required|exists:kp_companies,id',
            'nama_supervisor' => 'required|string|max:255',
            'no_supervisor' => 'required|string|max:255',
        ]);

        // Create supervisor
        $supervisor = KpSupervisor::create([
            'company_id' => $request->company_id,
            'nama_supervisor' => $request->nama_supervisor,
            'no_supervisor' => $request->no_supervisor,
            'created_by' => $user['id'],
        ]);

        // Create request
        KpRequest::create([
            'type' => 'pengantar',
            'company_id' => $request->company_id,
            'supervisor_id' => $supervisor->id,
            'mahasiswa_id' => $user['id'] ?? 1, // Fallback to admin user if no user id
            'status' => 'pending',
            'created_by' => $user['id'] ?? 1,
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

        if (!$request || $request->mahasiswa_id != $user['id'] || $request->type != 'permohonan' || $request->status != 'approved') {
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

        if (!$request || $request->mahasiswa_id != $user['id'] || $request->type != 'pengantar' || $request->status != 'approved') {
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
}
