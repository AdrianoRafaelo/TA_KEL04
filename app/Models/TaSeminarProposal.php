<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSeminarProposal extends Model
{
    protected $fillable = [
        'mahasiswa',
        'ta_pendaftaran_id',
        'judul',
        'pembimbing',
        'pengulas_1',
        'pengulas_2',
        'file_proposal',
        'file_persetujuan',
        'form_persetujuan',
        'proposal_penelitian',
        'berita_acara_pembimbing',
        'penilaian_pembimbing',
        'berita_acara_pengulas1',
        'penilaian_pengulas1',
        'berita_acara_pengulas2',
        'penilaian_pengulas2',
        'revisi_dokumen',
        'form_revisi',
        'status',
        'catatan',
    ];

    public function mahasiswaTa()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa', 'mahasiswa');
    }

    // Direct relationships to FtiData
    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa');
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'mahasiswa', 'nim');
    }

    public function pembimbing()
    {
        return $this->belongsTo(FtiData::class, 'pembimbing');
    }

    public function pengulas1()
    {
        return $this->belongsTo(FtiData::class, 'pengulas_1');
    }

    public function pengulas2()
    {
        return $this->belongsTo(FtiData::class, 'pengulas_2');
    }

    public function taPendaftaran()
    {
        return $this->belongsTo(TaPendaftaran::class);
    }
}
