<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MbkmMitra;
use App\Models\PendaftaranMbkm;

class MbkmController extends Controller
{
    public function index()
    {
        return view('mbkm.informasi_mhs');
    }

    public function pendaftaran()
    {
        $companies = MbkmMitra::where('active', '1')->get();
        return view('mbkm.pendaftaran_mhs', compact('companies'));
    }
    public function pendaftaranNonMitra()
    {
        return view('mbkm.pendaftaran_nonmitra_mhs');
    }

    public function pelaksanaan()
    {
        return view('mbkm.pelaksanaan_mhs');
    }

    public function seminar()
    {
        return view('mbkm.seminar_mhs');
    }

    public function pendaftarankoordinator()
    {
        $companies = MbkmMitra::where('active', '1')->get();
        return view('mbkm.pendaftaran_koordinator', compact('companies'));
    }

    public function pelaksanaankoordinator()
    {
        return view('mbkm.pelaksanaan_koordinator');        
    }

    public function seminarkoordinator()
    {
        return view('mbkm.seminar_koordinator');
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

    public function storePendaftaranMbkm(Request $request)
    {
        $request->validate([
            'mitra_id' => 'required|exists:mbkm_mitras,id',
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'ipk' => 'required|numeric|min:0|max:4',
            'matakuliah_ekuivalensi' => 'required|string',
            'portofolio_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'cv_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'masa_mbkm' => 'required|string|max:255',
        ]);

        // Handle file uploads
        $portofolioPath = $request->file('portofolio_file')->store('mbkm/portofolio', 'public');
        $cvPath = $request->file('cv_file')->store('mbkm/cv', 'public');

        PendaftaranMbkm::create([
            'mahasiswa_id' => auth()->id(),
            'mitra_id' => $request->mitra_id,
            'nama' => $request->nama,
            'nim' => $request->nim,
            'semester' => $request->semester,
            'ipk' => $request->ipk,
            'matakuliah_ekuivalensi' => $request->matakuliah_ekuivalensi,
            'file_portofolio' => $portofolioPath,
            'file_cv' => $cvPath,
            'masa_mbkm' => $request->masa_mbkm,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'active' => '1',
        ]);

        return redirect()->back()->with('success', 'Pendaftaran MBKM berhasil diajukan.');
    }
}
