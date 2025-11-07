<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\TaPendaftaran;
use App\Models\TaPendaftaranTransaksi;

class TugasAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $username = Session::get('username') ?? 'system';

        $deskripsiSyarat = $request->deskripsi_syarat;
        if ($request->has('is_dosen') && $request->is_dosen == 1 && empty($deskripsiSyarat)) {
            $deskripsiSyarat = 'Memenuhi sks Mahasiswa';
        }

        $statusId = null;
        if (!$request->has('is_dosen') || $request->is_dosen != 1) {
            // Jika mahasiswa buat judul sendiri, set status menunggu
            $statusMenunggu = \App\Models\RefStatusTa::where('name', 'menunggu')->first();
            if ($statusMenunggu) {
                $statusId = $statusMenunggu->id;
            }
        }

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file' => $request->file('file') ? $request->file('file')->store('ta_files') : null,
            'deskripsi_syarat' => $deskripsiSyarat,
            'status_id' => $statusId,
            'created_by' => $username,
            'active' => 1
        ];

        // Jika pengajuan dari dosen, set field dosen
        if ($request->has('is_dosen') && $request->is_dosen == 1) {
            $data['dosen'] = $username;
        }

        $ta = TaPendaftaran::create($data);
        return redirect()->back()->with('success', 'Judul berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaPendaftaran $taPendaftaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaPendaftaran $taPendaftaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaPendaftaran $taPendaftaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaPendaftaran $taPendaftaran)
    {
        //
    }

    public function indexMahasiswa()
    {
        $username = Session::get('username') ?? null;

        // Tampilkan hanya judul dosen (yang dibuat oleh dosen)
        $judul_dosen = TaPendaftaran::where('active', 1)->whereNotNull('dosen')->get();

        // Tampilkan status pendaftaran mahasiswa (judul yang diajukan mahasiswa sendiri)
        $status_pendaftaran_sendiri = $username ? TaPendaftaran::with('status')->where('active', 1)->where('created_by', $username)->whereNull('dosen')->get() : collect();

        // Tampilkan status pendaftaran mahasiswa yang mengambil tawaran dosen
        $status_pendaftaran_tawaran = $username ? TaPendaftaranTransaksi::with(['pendaftaran', 'status'])->where('active', 1)->where('username', $username)->whereHas('pendaftaran', function ($q) {
            $q->whereNotNull('dosen');
        })->get() : collect();

        return view('tugasakhir.mahasiswa', compact('judul_dosen', 'status_pendaftaran_sendiri', 'status_pendaftaran_tawaran'));
    }

    // Halaman dosen
    public function indexDosen()
    {
        $username = Session::get('username') ?? null;

        // Tampilkan hanya judul mahasiswa (yang dibuat oleh mahasiswa, tanpa dosen)
        $judul_mahasiswa = TaPendaftaran::with('transaksi')->where('active', 1)->whereNull('dosen')->get();

        // Tampilkan mahasiswa yang mendaftar (transaksi untuk tawaran dosen yang diambil mahasiswa)
        $mahasiswa_mendaftar = TaPendaftaranTransaksi::with('pendaftaran')->where('active', 1)->whereHas('pendaftaran', function ($q) {
            $q->whereNotNull('dosen');
        })->get();

        // Tampilkan judul dosen (yang dibuat oleh dosen)
        $judul_dosen = TaPendaftaran::where('active', 1)->whereNotNull('dosen')->get();

        $status_pendaftaran = TaPendaftaranTransaksi::where('active', 1)->get();

        return view('tugasakhir.dosen', compact(
            'judul_dosen',
            'mahasiswa_mendaftar',
            'judul_mahasiswa',
            'status_pendaftaran',
            'username'
        ));
    }

    // Simpan transaksi TA (ambil judul, request, dll)
    public function storeTransaksi(Request $request)
    {
        $username = Session::get('username') ?? $request->username ?? 'system';

        $pendaftaran = TaPendaftaran::find($request->ta_pendaftaran_id);
        $deskripsiSyarat = $pendaftaran ? $pendaftaran->deskripsi_syarat : null;

        TaPendaftaranTransaksi::create([
            'ta_pendaftaran_id' => $request->ta_pendaftaran_id,
            'file_portofolio' => $request->file('file_portofolio') ? $request->file('file_portofolio')->store('ta_portofolio') : null,
            'ref_status_ta_id' => $request->ref_status_ta_id,
            'username' => $username,
            'catatan' => $request->catatan,
            'deskripsi_syarat' => $deskripsiSyarat,
            'created_by' => $username,
            'active' => 1
        ]);

        if ($pendaftaran) {
            if (is_null($pendaftaran->dosen)) {
                // Dosen ambil judul mahasiswa, update status menjadi 'disetujui'
                $statusDisetujui = \App\Models\RefStatusTa::where('name', 'disetujui')->first();
                if ($statusDisetujui) {
                    $pendaftaran->update(['status_id' => $statusDisetujui->id]);
                }
            }
            // Mahasiswa ambil tawaran dosen, hanya masukkan ke transaksi, tidak buat pendaftaran baru
        }

        return redirect()->back()->with('success', 'Transaksi berhasil');
    }

    public function terimaTransaksi($id)
    {
        $transaksi = TaPendaftaranTransaksi::find($id);
        if ($transaksi) {
            $statusDisetujui = \App\Models\RefStatusTa::where('name', 'disetujui')->first();
            if ($statusDisetujui) {
                $transaksi->ref_status_ta_id = $statusDisetujui->id;
                $transaksi->save();

                // Update status pendaftaran juga
                $pendaftaran = $transaksi->pendaftaran;
                if ($pendaftaran) {
                    $pendaftaran->status_id = $statusDisetujui->id;
                    $pendaftaran->save();
                }
            }
        }
        return redirect()->back()->with('success', 'Transaksi diterima');
    }

    public function seminarProposal()
    {
        return view('tugasakhir.seminar_proposal');
    }

    public function seminarProposalMahasiswa()
    {
        return view('tugasakhir.sempro_mahasiswa');
    }

    public function seminarHasilMahasiswa()
    {
        return view('tugasakhir.semhas_mahasiswa');
    }

    public function sidangAkhirMahasiswa()
    {
        return view('tugasakhir.sidang_akhir_mahasiswa');
    }

    public function bimbinganMahasiswa()
    {
        return view('tugasakhir.bimbingan_mahasiswa');
    }
    public function koordinatorPendaftaran()
    {
        // Fetch judul dari dosen (Batch I)
        $judul_dosen = TaPendaftaran::where('active', 1)->whereNotNull('dosen')->get();

        // Fetch judul dari mahasiswa (Batch II)
        $judul_mahasiswa = TaPendaftaran::with('transaksi')->where('active', 1)->whereNull('dosen')->get();

        return view('tugasakhir.koordinator_pendaftaran', compact('judul_dosen', 'judul_mahasiswa'));
    }

    public function koordinatorMahasiswaTa()
    {
        return view('tugasakhir.koordinator_mahasiswa_ta');
    }


    public function koordinatorSempro()
    {
        return view('tugasakhir.koordinator_sempro');
    }

}
