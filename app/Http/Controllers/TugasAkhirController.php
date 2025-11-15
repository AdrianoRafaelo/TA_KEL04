<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\TaPendaftaran;
use App\Models\TaPendaftaranTransaksi;
use App\Models\MahasiswaTugasAkhir;
use App\Models\FtiData;
use App\Models\TaSeminarProposal;
use App\Models\TaSeminarHasil;
use App\Models\TaSidangAkhir;
use App\Models\TaBimbingan;
use App\Models\TaSkripsi;
use App\Models\PengaturanTa;

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
        $judul_dosen = TaPendaftaran::leftJoin('fti_datas', 'ta_pendaftaran.dosen', '=', 'fti_datas.username')
            ->select(
                'ta_pendaftaran.*',
                \DB::raw('COALESCE(fti_datas.nama, ta_pendaftaran.dosen) as dosen_nama')
            )
            ->where('ta_pendaftaran.active', 1)
            ->whereNotNull('ta_pendaftaran.dosen')
            ->get();

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
        $judul_mahasiswa = TaPendaftaran::leftJoin('fti_datas', 'ta_pendaftaran.created_by', '=', 'fti_datas.username')
            ->select(
                'ta_pendaftaran.*',
                \DB::raw('COALESCE(fti_datas.nama, ta_pendaftaran.created_by) as nama'),
                \DB::raw('COALESCE(fti_datas.nim, "") as nim')
            )
            ->with('transaksi')
            ->where('ta_pendaftaran.active', 1)
            ->whereNull('ta_pendaftaran.dosen')
            ->get();

        // Tampilkan mahasiswa yang mendaftar (transaksi untuk tawaran dosen yang diambil mahasiswa)
        $mahasiswa_mendaftar = TaPendaftaranTransaksi::leftJoin('fti_datas', 'ta_pendaftaran_transaksi.username', '=', 'fti_datas.username')
            ->select(
                'ta_pendaftaran_transaksi.*',
                \DB::raw('COALESCE(fti_datas.nama, ta_pendaftaran_transaksi.username) as nama'),
                \DB::raw('COALESCE(fti_datas.nim, "") as nim')
            )
            ->with('pendaftaran')
            ->where('ta_pendaftaran_transaksi.active', 1)
            ->whereHas('pendaftaran', function ($q) {
                $q->whereNotNull('dosen');
            })
            ->get();

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

    public function cancelTransaksi($id)
    {
        $transaksi = TaPendaftaranTransaksi::find($id);
        $username = Session::get('username') ?? null;

        // Check if the transaction belongs to the current user
        if ($transaksi && $transaksi->username === $username) {
            // Deactivate the transaction instead of deleting it
            $transaksi->active = 0;
            $transaksi->save();

            // Also deactivate the corresponding pendaftaran if it was created by the lecturer
            $pendaftaran = $transaksi->pendaftaran;
            if ($pendaftaran && $pendaftaran->dosen === $username) {
                $pendaftaran->active = 0;
                $pendaftaran->save();
            }
        }

        return redirect()->back()->with('success', 'Judul berhasil dibatalkan');
    }

    public function seminarProposal()
    {
        $username = Session::get('username') ?? null;

        // Get the nama from fti_datas for the logged-in user
        $ftiData = \App\Models\FtiData::where('username', $username)->first();
        $nama = $ftiData ? $ftiData->nama : $username;

        // Fetch mahasiswa tugas akhir where user is pembimbing, join with fti_datas
        $pembimbingAssignments = MahasiswaTugasAkhir::leftJoin('fti_datas', 'mahasiswa_tugas_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select('mahasiswa_tugas_akhirs.*', 'fti_datas.nama', 'fti_datas.nim')
            ->with('seminarProposal')
            ->where('pembimbing', $nama)->where('active', true)->get();

        // Fetch mahasiswa tugas akhir where user is pengulas_1 or pengulas_2, join with fti_datas
        $pengujiAssignments = MahasiswaTugasAkhir::leftJoin('fti_datas', 'mahasiswa_tugas_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select('mahasiswa_tugas_akhirs.*', 'fti_datas.nama', 'fti_datas.nim')
            ->with('seminarProposal')
            ->where(function ($query) use ($nama) {
                $query->where('pengulas_1', $nama)
                      ->orWhere('pengulas_2', $nama);
            })->where('active', true)->get();

        return view('tugasakhir.seminar_proposal', compact('pembimbingAssignments', 'pengujiAssignments'));
    }

    public function seminarHasilDosen()
    {
        $username = Session::get('username') ?? null;

        // Get the nama from fti_datas for the logged-in user
        $ftiData = \App\Models\FtiData::where('username', $username)->first();
        $nama = $ftiData ? $ftiData->nama : $username;

        // Fetch mahasiswa tugas akhir where user is pembimbing, join with fti_datas
        $pembimbingAssignments = MahasiswaTugasAkhir::leftJoin('fti_datas', 'mahasiswa_tugas_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select('mahasiswa_tugas_akhirs.*', 'fti_datas.nama', 'fti_datas.nim')
            ->with('seminarHasil')
            ->where('pembimbing', $nama)->where('active', true)->get();

        // Fetch mahasiswa tugas akhir where user is pengulas_1 or pengulas_2, join with fti_datas
        $pengujiAssignments = MahasiswaTugasAkhir::leftJoin('fti_datas', 'mahasiswa_tugas_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select('mahasiswa_tugas_akhirs.*', 'fti_datas.nama', 'fti_datas.nim')
            ->with('seminarHasil')
            ->where(function ($query) use ($nama) {
                $query->where('pengulas_1', $nama)
                      ->orWhere('pengulas_2', $nama);
            })->where('active', true)->get();

        return view('tugasakhir.semhas_dosen', compact('pembimbingAssignments', 'pengujiAssignments'));
    }

    public function sidangAkhirDosen()
    {
        $username = Session::get('username') ?? null;

        // Get the nama from fti_datas for the logged-in user
        $ftiData = \App\Models\FtiData::where('username', $username)->first();
        $nama = $ftiData ? $ftiData->nama : $username;

        // Fetch mahasiswa tugas akhir where user is pembimbing, join with fti_datas
        $pembimbingAssignments = MahasiswaTugasAkhir::leftJoin('fti_datas', 'mahasiswa_tugas_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select('mahasiswa_tugas_akhirs.*', 'fti_datas.nama', 'fti_datas.nim')
            ->with('sidangAkhir')
            ->where('pembimbing', $nama)->where('active', true)->get();

        // Fetch mahasiswa tugas akhir where user is pengulas_1 or pengulas_2, join with fti_datas
        $pengujiAssignments = MahasiswaTugasAkhir::leftJoin('fti_datas', 'mahasiswa_tugas_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select('mahasiswa_tugas_akhirs.*', 'fti_datas.nama', 'fti_datas.nim')
            ->with('sidangAkhir')
            ->where(function ($query) use ($nama) {
                $query->where('pengulas_1', $nama)
                      ->orWhere('pengulas_2', $nama);
            })->where('active', true)->get();

        return view('tugasakhir.sidang_dosen', compact('pembimbingAssignments', 'pengujiAssignments'));
    }

    public function bimbinganDosen()
    {
        $username = Session::get('username') ?? null;

        // Get the nama from fti_datas for the logged-in user
        $ftiData = \App\Models\FtiData::where('username', $username)->first();
        $nama = $ftiData ? $ftiData->nama : $username;

        // Fetch mahasiswa tugas akhir where user is pembimbing, join with fti_datas to get nama and prodi
        $mahasiswa_bimbingan = MahasiswaTugasAkhir::leftJoin('fti_datas', 'mahasiswa_tugas_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select('mahasiswa_tugas_akhirs.*', 'fti_datas.nama', 'fti_datas.nim', 'fti_datas.prodi')
            ->where('mahasiswa_tugas_akhirs.pembimbing', $nama)
            ->where('mahasiswa_tugas_akhirs.active', true)
            ->get();

        return view('tugasakhir.bimbingan_dosen', compact('mahasiswa_bimbingan'));
    }

    public function seminarProposalMahasiswa()
    {
        $username = Session::get('username') ?? null;

        // Ambil data mahasiswa tugas akhir yang sudah diterima oleh koordinator
        $mahasiswaTa = MahasiswaTugasAkhir::with('seminarProposal')->where('mahasiswa', $username)->where('active', true)->first();

        // Ambil data seminar proposal terlengkap
        $seminarProposal = null;
        if ($mahasiswaTa) {
            $seminarProposal = TaSeminarProposal::where('mahasiswa', $username)->first();
        }

        return view('tugasakhir.sempro_mahasiswa', compact('mahasiswaTa', 'seminarProposal'));
    }

    public function storeSeminarProposal(Request $request)
    {
        $request->validate([
            'file_proposal' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'file_persetujuan' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $username = Session::get('username') ?? null;
        $mahasiswaTa = MahasiswaTugasAkhir::where('mahasiswa', $username)->where('active', true)->first();

        if (!$mahasiswaTa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Simpan file proposal
        $fileProposalPath = $request->file('file_proposal')->store('seminar_proposals', 'public');

        // Simpan file persetujuan
        $filePersetujuanPath = $request->file('file_persetujuan')->store('seminar_proposals', 'public');

        // Simpan data ke database
        TaSeminarProposal::create([
            'mahasiswa' => $mahasiswaTa->mahasiswa,
            'judul' => $mahasiswaTa->judul,
            'pembimbing' => $mahasiswaTa->pembimbing,
            'file_proposal' => $fileProposalPath,
            'file_persetujuan' => $filePersetujuanPath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Proposal seminar berhasil diunggah.');
    }

    public function uploadRevisiSeminarProposal(Request $request)
    {
        try {
            \Log::info('Upload revisi called', [
                'username' => Session::get('username'),
                'has_form_revisi' => $request->hasFile('form_revisi'),
                'has_revisi_dokumen' => $request->hasFile('revisi_dokumen'),
                'all_files' => $request->allFiles(),
                'all_input' => $request->all()
            ]);

            $request->validate([
                'form_revisi' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'revisi_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
            ]);

            $username = Session::get('username') ?? null;
            \Log::info('Username from session: ' . $username);

            $seminarProposal = TaSeminarProposal::where('mahasiswa', $username)->first();
            \Log::info('Seminar proposal found: ' . ($seminarProposal ? 'yes' : 'no'));

            if (!$seminarProposal) {
                \Log::error('Seminar proposal not found for username: ' . $username);
                return redirect()->back()->with('error', 'Data seminar proposal tidak ditemukan.');
            }

        // Simpan file form revisi
        if ($request->hasFile('form_revisi')) {
            if ($seminarProposal->form_revisi) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $seminarProposal->form_revisi);
            }
            $seminarProposal->form_revisi = $request->file('form_revisi')->store('dokumen/sempro', 'public');
        }

        // Simpan file revisi dokumen
        if ($request->hasFile('revisi_dokumen')) {
            if ($seminarProposal->revisi_dokumen) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $seminarProposal->revisi_dokumen);
            }
            $seminarProposal->revisi_dokumen = $request->file('revisi_dokumen')->store('dokumen/sempro', 'public');
        }

        $seminarProposal->save();

        \Log::info('Upload revisi success for username: ' . $username);
        return redirect()->back()->with('success', 'Revisi seminar proposal berhasil diunggah.');
        } catch (\Exception $e) {
            \Log::error('Upload revisi error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'username' => Session::get('username')
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function seminarHasilMahasiswa()
    {
        $username = Session::get('username') ?? null;

        // Ambil data mahasiswa tugas akhir yang sudah diterima oleh koordinator
        $mahasiswaTa = MahasiswaTugasAkhir::with('seminarHasil')->where('mahasiswa', $username)->where('active', true)->first();

        return view('tugasakhir.semhas_mahasiswa', compact('mahasiswaTa'));
    }

    public function sidangAkhirMahasiswa()
    {
        $username = Session::get('username') ?? null;

        // Ambil data mahasiswa tugas akhir yang sudah diterima oleh koordinator
        $mahasiswaTa = MahasiswaTugasAkhir::with('sidangAkhir')->where('mahasiswa', $username)->where('active', true)->first();

        return view('tugasakhir.sidang_akhir_mahasiswa', compact('mahasiswaTa'));
    }

    public function storeSidangAkhir(Request $request)
    {
        $request->validate([
            'file_dokumen_ta' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_log_activity' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_persetujuan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $username = Session::get('username') ?? null;
        $mahasiswaTa = MahasiswaTugasAkhir::where('mahasiswa', $username)->where('active', true)->first();

        if (!$mahasiswaTa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Check if sidang akhir already exists
        $sidangAkhir = TaSidangAkhir::where('mahasiswa', $mahasiswaTa->mahasiswa)->first();

        if (!$sidangAkhir) {
            $sidangAkhir = new TaSidangAkhir();
            $sidangAkhir->mahasiswa = $mahasiswaTa->mahasiswa;
            $sidangAkhir->judul = $mahasiswaTa->judul;
            $sidangAkhir->pembimbing = $mahasiswaTa->pembimbing;
            $sidangAkhir->pengulas_1 = $mahasiswaTa->pengulas_1;
            $sidangAkhir->pengulas_2 = $mahasiswaTa->pengulas_2;
            $sidangAkhir->status = 'pending';
        }

        // Handle file uploads
        if ($request->hasFile('file_dokumen_ta')) {
            if ($sidangAkhir->file_dokumen_ta) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $sidangAkhir->file_dokumen_ta);
            }
            $sidangAkhir->file_dokumen_ta = $request->file('file_dokumen_ta')->store('dokumen/sidang', 'public');
        }

        if ($request->hasFile('file_log_activity')) {
            if ($sidangAkhir->file_log_activity) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $sidangAkhir->file_log_activity);
            }
            $sidangAkhir->file_log_activity = $request->file('file_log_activity')->store('dokumen/sidang', 'public');
        }

        if ($request->hasFile('file_persetujuan')) {
            if ($sidangAkhir->file_persetujuan) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $sidangAkhir->file_persetujuan);
            }
            $sidangAkhir->file_persetujuan = $request->file('file_persetujuan')->store('dokumen/sidang', 'public');
        }

        $sidangAkhir->save();

        return redirect()->back()->with('success', 'Data sidang akhir berhasil disimpan!');
    }

    public function uploadRevisiSidangAkhir(Request $request)
    {
        $request->validate([
            'form_revisi' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'revisi_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $username = Session::get('username') ?? null;
        $sidangAkhir = TaSidangAkhir::where('mahasiswa', $username)->first();

        if (!$sidangAkhir) {
            return redirect()->back()->with('error', 'Data sidang akhir tidak ditemukan.');
        }

        // Simpan file form revisi
        if ($request->hasFile('form_revisi')) {
            if ($sidangAkhir->form_revisi) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $sidangAkhir->form_revisi);
            }
            $sidangAkhir->form_revisi = $request->file('form_revisi')->store('dokumen/sidang', 'public');
        }

        // Simpan file revisi dokumen
        if ($request->hasFile('revisi_dokumen')) {
            if ($sidangAkhir->revisi_dokumen) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $sidangAkhir->revisi_dokumen);
            }
            $sidangAkhir->revisi_dokumen = $request->file('revisi_dokumen')->store('dokumen/sidang', 'public');
        }

        $sidangAkhir->save();

        return redirect()->back()->with('success', 'Revisi sidang akhir berhasil diunggah.');
    }

    public function bimbinganMahasiswa()
    {
        $username = Session::get('username') ?? null;

        // Ambil data bimbingan mahasiswa dari database
        $bimbingans = TaBimbingan::where('mahasiswa', $username)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Ambil data skripsi mahasiswa
        $skripsi = TaSkripsi::where('mahasiswa', $username)->first();

        return view('tugasakhir.bimbingan_mahasiswa', compact('bimbingans', 'skripsi'));
    }

    public function storeBimbingan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'topik_pembahasan' => 'required|string|max:1000',
            'tugas_selanjutnya' => 'nullable|string|max:1000',
        ]);

        $username = Session::get('username') ?? null;

        TaBimbingan::create([
            'mahasiswa' => $username,
            'tanggal' => $request->tanggal,
            'topik_pembahasan' => $request->topik_pembahasan,
            'tugas_selanjutnya' => $request->tugas_selanjutnya,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'message' => 'Log bimbingan berhasil ditambahkan!']);
    }

    public function terimaJudulBatch1(Request $request)
    {
        $ids = $request->input('ids');
        foreach ($ids as $id) {
            $pendaftaran = TaPendaftaran::find($id);
            if ($pendaftaran) {
                $statusDisetujui = \App\Models\RefStatusTa::where('name', 'disetujui')->first();
                if ($statusDisetujui) {
                    $pendaftaran->status_id = $statusDisetujui->id;
                    $pendaftaran->save();
                }
            }
        }
        return redirect()->back()->with('success', 'Judul Batch I berhasil diterima');
    }

    public function terimaJudulBatch2(Request $request)
    {
        $ids = $request->input('ids');
        foreach ($ids as $id) {
            $pendaftaran = TaPendaftaran::find($id);
            if ($pendaftaran) {
                $statusDisetujui = \App\Models\RefStatusTa::where('name', 'disetujui')->first();
                if ($statusDisetujui) {
                    $pendaftaran->status_id = $statusDisetujui->id;
                    $pendaftaran->save();
                }
            }
        }
        return redirect()->back()->with('success', 'Judul Batch II berhasil diterima');
    }

    public function koordinatorPendaftaran()
    {
        // Fetch judul dari dosen (Batch I) dengan mahasiswa tertarik
        $judul_dosen = TaPendaftaran::where('active', 1)->whereNotNull('dosen')->with(['transaksi' => function($q) {
            $q->where('active', 1);
        }])->get()->map(function($jd) {
            $jd->interested_students = $jd->transaksi->pluck('username')->toArray();
            return $jd;
        });

        // Fetch judul dari mahasiswa (Batch II)
        $judul_mahasiswa = TaPendaftaran::leftJoin('fti_datas', 'ta_pendaftaran.created_by', '=', 'fti_datas.username')
            ->select(
                'ta_pendaftaran.*',
                \DB::raw('COALESCE(fti_datas.nama, ta_pendaftaran.created_by) as nama'),
                \DB::raw('COALESCE(fti_datas.nim, "") as nim')
            )
            ->with('transaksi')
            ->where('ta_pendaftaran.active', 1)
            ->whereNull('ta_pendaftaran.dosen')
            ->get();

        // Get TA settings
        $pengaturanTa = PengaturanTa::first();

        return view('tugasakhir.koordinator_pendaftaran', compact('judul_dosen', 'judul_mahasiswa', 'pengaturanTa'));
    }

    public function koordinatorMahasiswaTa()
    {
        $statusDisetujui = \App\Models\RefStatusTa::where('name', 'disetujui')->first();
        $accepted_titles = TaPendaftaran::leftJoin('fti_datas', 'ta_pendaftaran.created_by', '=', 'fti_datas.username')
            ->select(
                'ta_pendaftaran.*',
                \DB::raw('COALESCE(fti_datas.nama, ta_pendaftaran.created_by) as nama'),
                \DB::raw('COALESCE(fti_datas.nim, "") as nim')
            )
            ->where('ta_pendaftaran.status_id', $statusDisetujui->id)
            ->with(['transaksi'])
            ->get();

        // Fetch lecturers from fti_datas where role is 'lecturer'
        $lecturers = \App\Models\FtiData::where('role', 'lecturer')->get();

        return view('tugasakhir.koordinator_mahasiswa_ta', compact('accepted_titles', 'lecturers'));
    }


    public function saveKoordinatorMahasiswaTa(Request $request)
    {
        $titles = $request->input('titles', []);
        $username = Session::get('username') ?? 'system';

        foreach ($titles as $titleId => $data) {
            $taPendaftaran = TaPendaftaran::find($titleId);
            if (!$taPendaftaran) continue;

            // Delete existing assignments for this title
            MahasiswaTugasAkhir::where('judul', $taPendaftaran->judul)->delete();

            // Create new assignment
            MahasiswaTugasAkhir::create([
                'mahasiswa' => $taPendaftaran->created_by,
                'judul' => $taPendaftaran->judul,
                'pembimbing' => $data['pembimbing'] ?? null,
                'pengulas_1' => $data['pengulas1'] ?? null,
                'pengulas_2' => $data['pengulas2'] ?? null,
                'created_by' => $username,
                'updated_by' => $username,
                'active' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Data dosen berhasil disimpan');
    }

    public function koordinatorSempro()
    {
        // Ambil data seminar proposal dari tabel ta_seminar_proposals dengan join ke mahasiswa_tugas_akhirs dan fti_datas
        // Gunakan COALESCE untuk mengambil pengulas dari ta_seminar_proposals, jika kosong ambil dari mahasiswa_tugas_akhirs
        $seminarProposals = TaSeminarProposal::leftJoin('mahasiswa_tugas_akhirs', 'ta_seminar_proposals.mahasiswa', '=', 'mahasiswa_tugas_akhirs.mahasiswa')
            ->leftJoin('fti_datas', 'ta_seminar_proposals.mahasiswa', '=', 'fti_datas.username')
            ->select(
                'ta_seminar_proposals.*',
                \DB::raw('COALESCE(fti_datas.nama, ta_seminar_proposals.mahasiswa) as nama'),
                \DB::raw('COALESCE(fti_datas.nim, "") as nim'),
                \DB::raw('COALESCE(ta_seminar_proposals.pengulas_1, mahasiswa_tugas_akhirs.pengulas_1) as pengulas_1_merged'),
                \DB::raw('COALESCE(ta_seminar_proposals.pengulas_2, mahasiswa_tugas_akhirs.pengulas_2) as pengulas_2_merged')
            )
            ->get()
            ->map(function ($proposal) {
                // Override nilai pengulas_1 dan pengulas_2 dengan nilai merged
                $proposal->pengulas_1 = $proposal->pengulas_1_merged;
                $proposal->pengulas_2 = $proposal->pengulas_2_merged;
                return $proposal;
            });

        // Fetch lecturers from fti_datas where role is 'lecturer'
        $lecturers = \App\Models\FtiData::where('role', 'lecturer')->get();

        return view('tugasakhir.koordinator_sempro', compact('seminarProposals', 'lecturers'));
    }

    public function approveSeminarProposals(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada proposal yang dipilih.']);
        }

        // Validate that all ids are integers
        $validatedIds = array_filter($ids, 'is_int');
        if (count($validatedIds) !== count($ids)) {
            return response()->json(['success' => false, 'message' => 'ID tidak valid.']);
        }

        TaSeminarProposal::whereIn('id', $ids)->update(['status' => 'approved']);

        return response()->json(['success' => true, 'message' => count($ids) . ' seminar proposal berhasil diterima.']);
    }

    public function uploadSemproDokumen(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'field' => 'required|string|in:form_persetujuan,proposal_penelitian,berita_acara_pembimbing,penilaian_pembimbing,berita_acara_pengulas1,penilaian_pengulas1,berita_acara_pengulas2,penilaian_pengulas2,revisi_dokumen,form_revisi,jadwal_seminar_file,rubrik_penilaian,form_review',
            'proposal_id' => 'required|exists:ta_seminar_proposals,id'
        ]);

        $proposal = TaSeminarProposal::findOrFail($request->proposal_id);
        $field = $request->field;

        // Hapus file lama jika ada
        if ($proposal->$field) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $proposal->$field);
        }

        $path = $request->file('file')->store('dokumen/sempro', 'public');
        $proposal->$field = $path;
        $proposal->save();

        return response()->json([
            'success' => true,
            'file_path' => asset('storage/' . $path)
        ]);
    }

    public function updateSemproPengulas(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:ta_seminar_proposals,id',
            'field' => 'required|in:pengulas_1,pengulas_2',
            'value' => 'nullable|string'
        ]);

        $proposal = TaSeminarProposal::findOrFail($request->proposal_id);
        $proposal->{$request->field} = $request->value;
        $proposal->save();

        return response()->json(['success' => true]);
    }

    public function uploadJadwalSeminarDokumen(Request $request)
    {
        $request->validate([
            'jadwal_seminar_file' => 'required|file|mimes:pdf,doc,docx',
            'proposal_id' => 'required|integer',
        ]);

        $proposal = TaSeminarProposal::find($request->proposal_id);
        if (!$proposal) {
            return response()->json(['success' => false, 'message' => 'Proposal tidak ditemukan.']);
        }

        $file = $request->file('jadwal_seminar_file');
        $path = $file->store('jadwal_seminar', 'public');
        $proposal->jadwal_seminar_file = $path;
        $proposal->save();

        return response()->json([
            'success' => true,
            'file_path' => asset('storage/' . $path),
        ]);
    }

    public function serveStorageFile($path)
    {
        $filePath = storage_path('app/public/' . $path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($filePath);
    }

    public function koordinatorSemhas()
    {
        \Log::info('koordinatorSemhas called');

        $mahasiswaTas = MahasiswaTugasAkhir::all();
        $seminarHasils = TaSeminarHasil::leftJoin('fti_datas', 'ta_seminar_hasils.mahasiswa', '=', 'fti_datas.username')
            ->select(
                'ta_seminar_hasils.*',
                \DB::raw('COALESCE(fti_datas.nama, ta_seminar_hasils.mahasiswa) as nama'),
                \DB::raw('COALESCE(fti_datas.nim, "") as nim')
            )
            ->get();

        \Log::info('MahasiswaTugasAkhir count: ' . $mahasiswaTas->count());
        \Log::info('TaSeminarHasil count: ' . $seminarHasils->count());

        // Merge data - ambil pengulas dari MahasiswaTugasAkhir jika kosong di TaSeminarHasil
        // Dan simpan ke database agar data tersimpan permanen
        foreach ($seminarHasils as $hasil) {
            $mahasiswaTa = $mahasiswaTas->where('mahasiswa', $hasil->mahasiswa)->first();
            if ($mahasiswaTa) {
                $updated = false;
                \Log::info('Processing mahasiswa: ' . $hasil->mahasiswa);
                \Log::info('Current pengulas_1: ' . ($hasil->pengulas_1 ?? 'null') . ', mahasiswaTa pengulas_1: ' . ($mahasiswaTa->pengulas_1 ?? 'null'));

                // Jika pengulas kosong di seminarHasil, ambil dari mahasiswaTa dan simpan
                if (!$hasil->pengulas_1 && $mahasiswaTa->pengulas_1) {
                    $hasil->pengulas_1 = $mahasiswaTa->pengulas_1;
                    $updated = true;
                    \Log::info('Updated pengulas_1 for: ' . $hasil->mahasiswa);
                }
                if (!$hasil->pengulas_2 && $mahasiswaTa->pengulas_2) {
                    $hasil->pengulas_2 = $mahasiswaTa->pengulas_2;
                    $updated = true;
                    \Log::info('Updated pengulas_2 for: ' . $hasil->mahasiswa);
                }
                // Simpan perubahan ke database jika ada update
                if ($updated) {
                    $hasil->save();
                    \Log::info('Saved changes for mahasiswa: ' . $hasil->mahasiswa);
                }
            } else {
                \Log::info('No matching mahasiswaTa found for: ' . $hasil->mahasiswa);
            }
        }

        return view('tugasakhir.koordinator_semhas', compact('seminarHasils'));
    }

    public function koordinatorSidang()
    {
        $mahasiswaTas = MahasiswaTugasAkhir::all();
        $sidangAkhirs = TaSidangAkhir::leftJoin('fti_datas', 'ta_sidang_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select(
                'ta_sidang_akhirs.*',
                \DB::raw('COALESCE(fti_datas.nama, ta_sidang_akhirs.mahasiswa) as nama'),
                \DB::raw('COALESCE(fti_datas.nim, "") as nim')
            )
            ->get();

        // Merge data - ambil pengulas dari MahasiswaTugasAkhir jika kosong di TaSidangAkhir
        // Dan simpan ke database agar data tersimpan permanen
        foreach ($sidangAkhirs as $sidang) {
            $mahasiswaTa = $mahasiswaTas->where('mahasiswa', $sidang->mahasiswa)->first();
            if ($mahasiswaTa) {
                $updated = false;
                // Jika pengulas kosong di sidangAkhir, ambil dari mahasiswaTa dan simpan
                if (!$sidang->pengulas_1 && $mahasiswaTa->pengulas_1) {
                    $sidang->pengulas_1 = $mahasiswaTa->pengulas_1;
                    $updated = true;
                }
                if (!$sidang->pengulas_2 && $mahasiswaTa->pengulas_2) {
                    $sidang->pengulas_2 = $mahasiswaTa->pengulas_2;
                    $updated = true;
                }
                // Simpan perubahan ke database jika ada update
                if ($updated) {
                    $sidang->save();
                }
            }
        }

        return view('tugasakhir.koordinator_sidang', compact('sidangAkhirs'));
    }

    public function approveSidangAkhirs(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada sidang akhir yang dipilih.']);
        }

        TaSidangAkhir::whereIn('id', $ids)->update(['status' => 'approved']);

        return response()->json(['success' => true, 'message' => count($ids) . ' sidang akhir berhasil diterima.']);
    }

    public function uploadJadwalSidangDokumen(Request $request)
    {
        $request->validate([
            'jadwal_sidang_file' => 'required|file|mimes:pdf,doc,docx',
            'sidang_id' => 'required|integer',
        ]);

        $sidang = TaSidangAkhir::find($request->sidang_id);
        if (!$sidang) {
            return response()->json(['success' => false, 'message' => 'Data sidang akhir tidak ditemukan.']);
        }

        $file = $request->file('jadwal_sidang_file');
        $path = $file->store('jadwal_sidang_akhir', 'public');
        $sidang->jadwal_sidang_file = $path;
        $sidang->save();

        return response()->json([
            'success' => true,
            'file_path' => route('storage.file', ['path' => $path]),
        ]);
    }

    public function uploadSidangDokumen(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'field' => 'required|string|in:file_dokumen_ta,file_log_activity,file_persetujuan,jadwal_sidang_file,berita_acara,form_penilaian,revisi_dokumen,form_revisi',
            'sidang_id' => 'required|exists:ta_sidang_akhirs,id'
        ]);

        $sidang = TaSidangAkhir::findOrFail($request->sidang_id);

        $field = $request->field;

        // Hapus file lama jika ada
        if ($sidang->$field) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $sidang->$field);
        }

        $path = $request->file('file')->store('dokumen/sidang', 'public');
        $sidang->$field = $path;
        $sidang->save();

        return response()->json([
            'success' => true,
            'file_path' => route('storage.file', ['path' => $path])
        ]);
    }

    public function approveSeminarHasils(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada seminar hasil yang dipilih.']);
        }

        TaSeminarHasil::whereIn('id', $ids)->update(['status' => 'approved']);

        return response()->json(['success' => true, 'message' => count($ids) . ' seminar hasil berhasil diterima.']);
    }

    public function uploadJadwalSeminarHasil(Request $request)
    {
        $request->validate([
            'jadwal_seminar_file' => 'required|file|mimes:pdf,doc,docx',
            'hasil_id' => 'required|integer',
        ]);

        $hasil = TaSeminarHasil::find($request->hasil_id);
        if (!$hasil) {
            return response()->json(['success' => false, 'message' => 'Data seminar hasil tidak ditemukan.']);
        }

        $file = $request->file('jadwal_seminar_file');
        $path = $file->store('jadwal_seminar_hasil', 'public');
        $hasil->jadwal_seminar_file = $path;
        $hasil->save();

        return response()->json([
            'success' => true,
            'file_path' => route('storage.file', ['path' => $path]),
        ]);
    }

    public function uploadSemhasDokumen(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'field' => 'required|string|in:file_dokumen_ta,file_log_activity,file_persetujuan,jadwal_seminar_file,rubrik_penilaian,form_review',
            'hasil_id' => 'required|exists:ta_seminar_hasils,id'
        ]);

        $hasil = TaSeminarHasil::findOrFail($request->hasil_id);

        $field = $request->field;

        // Hapus file lama jika ada
        if ($hasil->$field) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $hasil->$field);
        }

        $path = $request->file('file')->store('dokumen/semhas', 'public');
        $hasil->$field = $path;
        $hasil->save();

        return response()->json([
            'success' => true,
            'file_path' => route('storage.file', ['path' => $path])
        ]);
    }

    public function storeSeminarHasil(Request $request)
    {
        $request->validate([
            'file_dokumen_ta' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_log_activity' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_persetujuan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $username = Session::get('username') ?? 'system';
        
        // Get mahasiswa TA data
        $mahasiswaTa = MahasiswaTugasAkhir::where('mahasiswa', $username)->first();
        
        if (!$mahasiswaTa) {
            return redirect()->back()->with('error', 'Data mahasiswa TA tidak ditemukan');
        }

        // Check if seminar hasil already exists
        $seminarHasil = TaSeminarHasil::where('mahasiswa', $mahasiswaTa->mahasiswa)->first();
        
        if (!$seminarHasil) {
            $seminarHasil = new TaSeminarHasil();
            $seminarHasil->mahasiswa = $mahasiswaTa->mahasiswa;
            $seminarHasil->judul = $mahasiswaTa->judul;
            $seminarHasil->pembimbing = $mahasiswaTa->pembimbing;
            $seminarHasil->status = 'pending';
        }

        // Handle file uploads
        if ($request->hasFile('file_dokumen_ta')) {
            if ($seminarHasil->file_dokumen_ta) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $seminarHasil->file_dokumen_ta);
            }
            $seminarHasil->file_dokumen_ta = $request->file('file_dokumen_ta')->store('dokumen/semhas', 'public');
        }

        if ($request->hasFile('file_log_activity')) {
            if ($seminarHasil->file_log_activity) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $seminarHasil->file_log_activity);
            }
            $seminarHasil->file_log_activity = $request->file('file_log_activity')->store('dokumen/semhas', 'public');
        }

        if ($request->hasFile('file_persetujuan')) {
            if ($seminarHasil->file_persetujuan) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $seminarHasil->file_persetujuan);
            }
            $seminarHasil->file_persetujuan = $request->file('file_persetujuan')->store('dokumen/semhas', 'public');
        }

        $seminarHasil->save();

        return redirect()->back()->with('success', 'Data seminar hasil berhasil disimpan!');
    }

    public function uploadRevisiSeminarHasil(Request $request)
    {
        $request->validate([
            'form_revisi' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'revisi_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $username = Session::get('username') ?? null;
        $seminarHasil = TaSeminarHasil::where('mahasiswa', $username)->first();

        if (!$seminarHasil) {
            return redirect()->back()->with('error', 'Data seminar hasil tidak ditemukan.');
        }

        // Simpan file form revisi
        if ($request->hasFile('form_revisi')) {
            if ($seminarHasil->form_revisi) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $seminarHasil->form_revisi);
            }
            $seminarHasil->form_revisi = $request->file('form_revisi')->store('dokumen/semhas', 'public');
        }

        // Simpan file revisi dokumen
        if ($request->hasFile('revisi_dokumen')) {
            if ($seminarHasil->revisi_dokumen) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $seminarHasil->revisi_dokumen);
            }
            $seminarHasil->revisi_dokumen = $request->file('revisi_dokumen')->store('dokumen/semhas', 'public');
        }

        $seminarHasil->save();

        return redirect()->back()->with('success', 'Revisi seminar hasil berhasil diunggah.');
    }
    public function uploadSkripsiMahasiswa(Request $request)
    {
        $request->validate([
            'file_skripsi_word' => 'nullable|file|mimes:doc,docx|max:10240',
            'file_skripsi_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'form_bimbingan' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $username = Session::get('username') ?? null;

        // Cari data mahasiswa tugas akhir
        $mahasiswaTa = MahasiswaTugasAkhir::where('mahasiswa', $username)->where('active', true)->first();

        if (!$mahasiswaTa) {
            return redirect()->back()->with('error', 'Data mahasiswa tugas akhir tidak ditemukan.');
        }

        // Cari atau buat record skripsi
        $skripsi = TaSkripsi::where('mahasiswa_tugas_akhir_id', $mahasiswaTa->id)->first();

        if (!$skripsi) {
            $skripsi = new TaSkripsi();
            $skripsi->mahasiswa_tugas_akhir_id = $mahasiswaTa->id;
            $skripsi->mahasiswa = $mahasiswaTa->mahasiswa;
            $skripsi->status = 'draft';
        }

        $message = '';

        // Handle upload file Word
        if ($request->hasFile('file_skripsi_word')) {
            // Hapus file lama jika ada
            if ($skripsi->file_skripsi_word) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $skripsi->file_skripsi_word);
            }
            $skripsi->file_skripsi_word = $request->file('file_skripsi_word')->store('skripsi/word', 'public');
            $message .= 'File skripsi Word berhasil diunggah. ';
        }

        // Handle upload file PDF
        if ($request->hasFile('file_skripsi_pdf')) {
            // Hapus file lama jika ada
            if ($skripsi->file_skripsi_pdf) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $skripsi->file_skripsi_pdf);
            }
            $skripsi->file_skripsi_pdf = $request->file('file_skripsi_pdf')->store('skripsi/pdf', 'public');
            $message .= 'File skripsi PDF berhasil diunggah. ';
        }

        // Handle upload form bimbingan
        if ($request->hasFile('form_bimbingan')) {
            // Hapus file lama jika ada
            if ($skripsi->file_form_bimbingan) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $skripsi->file_form_bimbingan);
            }
            $skripsi->file_form_bimbingan = $request->file('form_bimbingan')->store('bimbingan/form', 'public');
            $message .= 'Form bimbingan berhasil diunggah. ';
        }

        if (!empty($message)) {
            $skripsi->save();
            return redirect()->back()->with('success', trim($message));
        } else {
            return redirect()->back()->with('warning', 'Tidak ada file yang dipilih untuk diunggah.');
        }
    }

    public function koordinatorSkripsi()
    {
        // Ambil data skripsi mahasiswa dengan join ke mahasiswa_tugas_akhirs dan fti_datas
        $skripsis = TaSkripsi::leftJoin('mahasiswa_tugas_akhirs', 'ta_skripsis.mahasiswa_tugas_akhir_id', '=', 'mahasiswa_tugas_akhirs.id')
            ->leftJoin('fti_datas', 'mahasiswa_tugas_akhirs.mahasiswa', '=', 'fti_datas.username')
            ->select(
                'ta_skripsis.*',
                'mahasiswa_tugas_akhirs.judul',
                'mahasiswa_tugas_akhirs.pembimbing',
                \DB::raw('COALESCE(fti_datas.nama, mahasiswa_tugas_akhirs.mahasiswa) as nama'),
                \DB::raw('COALESCE(fti_datas.nim, "") as nim')
            )
            ->where('mahasiswa_tugas_akhirs.active', true)
            ->get();

        return view('tugasakhir.koordinator_skripsi', compact('skripsis'));
    }

    public function updatePengaturanTa(Request $request)
    {
        $request->validate([
            'batas_waktu_pendaftaran' => 'nullable|date',
            'pendaftaran_ditutup' => 'boolean',
            'pesan_penutupan' => 'nullable|string|max:1000'
        ]);

        $pengaturanTa = PengaturanTa::first();
        if (!$pengaturanTa) {
            $pengaturanTa = new PengaturanTa();
        }

        $pengaturanTa->batas_waktu_pendaftaran = $request->batas_waktu_pendaftaran;
        $pengaturanTa->pendaftaran_ditutup = $request->has('pendaftaran_ditutup');
        $pengaturanTa->pesan_penutupan = $request->pesan_penutupan;
        $pengaturanTa->save();

        return redirect()->back()->with('success', 'Pengaturan pendaftaran TA berhasil disimpan');
    }

}