<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource (READ ALL for Admin).
     */
    public function index()
    {
        $pengumuman = Pengumuman::latest()->get();
        return view('admin.pengumuman.index', compact('pengumuman')); 
    }

    /**
     * Show the form for creating a new resource (CREATE Form).
     */
    public function create()
    {
        $kategoris = Pengumuman::KATEGORI_OPTIONS;
        return view('admin.pengumuman.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage (CREATE Logic).
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|in:' . implode(',', Pengumuman::KATEGORI_OPTIONS),
        ]);

        Pengumuman::create($request->all());

        return redirect()->route('pengumuman.index')
                         ->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource (UPDATE Form).
     */
    public function edit(Pengumuman $pengumuman)
    {
        $kategoris = Pengumuman::KATEGORI_OPTIONS;
        return view('admin.pengumuman.edit', compact('pengumuman', 'kategoris'));
    }

    /**
     * Update the specified resource in storage (UPDATE Logic).
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|in:' . implode(',', Pengumuman::KATEGORI_OPTIONS),
        ]);

        $pengumuman->update($request->all());

        return redirect()->route('pengumuman.index')
                         ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage (DELETE Logic).
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')
                         ->with('success', 'Pengumuman berhasil dihapus!');
    }
}