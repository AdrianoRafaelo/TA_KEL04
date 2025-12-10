<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSeminarHasil extends Model
{
    protected $table = 'ta_seminar_hasils';
    protected $fillable = [
        'mahasiswa',
        'ta_pendaftaran_id',
        'ta_seminar_proposals_id',
        'judul',
        'pembimbing',
        'pengulas_1',
        'pengulas_2',
        'file_dokumen_ta',
        'file_log_activity',
        'file_persetujuan',
        'jadwal_seminar_file',
        'rubrik_penilaian',
        'form_review',
        'status',
        'catatan'
    ];

    public function mahasiswaTugasAkhir()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa', 'mahasiswa');
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

    public function taSeminarProposal()
    {
        return $this->belongsTo(TaSeminarProposal::class, 'ta_seminar_proposals_id');
    }
}
