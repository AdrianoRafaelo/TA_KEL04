<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kurikulum;
use App\Models\FtiData;

class MatakuliahController extends Controller
{
    public function index()
    {
        $kurikulum = Kurikulum::leftJoin('fti_datas', 'kurikulum.dosen_id', '=', 'fti_datas.id')
            ->select('kurikulum.*', 'fti_datas.nama as dosen_nama')
            ->where('kurikulum.active', 1)
            ->orderBy('kurikulum.semester')
            ->get()
            ->groupBy('semester');

        $lecturers = FtiData::where('role', 'lecturer')->get();

        // Check user role and show appropriate view
        if (session('role') == 'Admin' || session('role') == 'Koordinator' || session('role') == 'Dosen') {
            return view('matakuliah', compact('kurikulum', 'lecturers'));
        } else {
            return view('matakuliah_student', compact('kurikulum'));
        }
    }

    public function create()
    {
        $lecturers = FtiData::where('role', 'lecturer')->get();
        return view('matakuliah.create', compact('lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester' => 'required|integer|min:1|max:8',
            'kode_mk' => 'required|string|max:20|unique:kurikulum,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'nama_mk_eng' => 'nullable|string|max:255',
            'nama_singkat_mk' => 'nullable|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'deskripsi_mk' => 'nullable|string',
            'dosen_id' => 'nullable|exists:fti_datas,id',
            'cpmk' => 'nullable|array',
            'cpmk.*' => 'nullable|string',
        ]);

        // Filter out empty CPMK
        $cpmk = array_filter($request->cpmk ?? []);

        Kurikulum::create([
            'semester' => $request->semester,
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'nama_mk_eng' => $request->nama_mk_eng,
            'nama_singkat_mk' => $request->nama_singkat_mk,
            'sks' => $request->sks,
            'deskripsi_mk' => $request->deskripsi_mk,
            'dosen_id' => $request->dosen_id,
            'cpmk' => $cpmk,
            'created_by' => auth()->id() ?? session('username'),
            'updated_by' => auth()->id() ?? session('username'),
            'active' => 1,
        ]);

        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $matakuliah = Kurikulum::findOrFail($id);
        $lecturers = FtiData::where('role', 'lecturer')->get();
        return view('matakuliah.edit', compact('matakuliah', 'lecturers'));
    }

    public function update(Request $request, $id)
    {
        $matakuliah = Kurikulum::findOrFail($id);

        $request->validate([
            'semester' => 'required|integer|min:1|max:8',
            'kode_mk' => 'required|string|max:20|unique:kurikulum,kode_mk,' . $id,
            'nama_mk' => 'required|string|max:255',
            'nama_mk_eng' => 'nullable|string|max:255',
            'nama_singkat_mk' => 'nullable|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'deskripsi_mk' => 'nullable|string',
            'dosen_id' => 'nullable|exists:fti_datas,id',
            'cpmk' => 'nullable|array',
            'cpmk.*' => 'nullable|string',
        ]);

        // Filter out empty CPMK
        $cpmk = array_filter($request->cpmk ?? []);

        $matakuliah->update([
            'semester' => $request->semester,
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'nama_mk_eng' => $request->nama_mk_eng,
            'nama_singkat_mk' => $request->nama_singkat_mk,
            'sks' => $request->sks,
            'deskripsi_mk' => $request->deskripsi_mk,
            'dosen_id' => $request->dosen_id,
            'cpmk' => $cpmk,
            'updated_by' => auth()->id() ?? session('username'),
        ]);

        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $matakuliah = Kurikulum::findOrFail($id);
        $matakuliah->update(['active' => 0, 'updated_by' => auth()->id() ?? session('username')]);

        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
