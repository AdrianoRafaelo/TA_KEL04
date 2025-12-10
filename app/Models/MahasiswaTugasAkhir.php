<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaTugasAkhir extends Model
{
    public $timestamps = false;

    protected $table = 'mahasiswa_tugas_akhirs';
    protected $fillable = ['mahasiswa', 'ta_pendaftaran_id', 'judul', 'pembimbing', 'pengulas_1', 'pengulas_2', 'created_by', 'updated_by', 'active'];

    public function seminarProposal()
    {
        return $this->hasOne(TaSeminarProposal::class, 'mahasiswa', 'mahasiswa');
    }

    public function seminarHasil()
    {
        return $this->hasOne(TaSeminarHasil::class, 'mahasiswa', 'mahasiswa');
    }

    public function sidangAkhir()
    {
        return $this->hasOne(TaSidangAkhir::class, 'mahasiswa', 'mahasiswa');
    }

    public function skripsi()
    {
        return $this->hasOne(TaSkripsi::class, 'mahasiswa_tugas_akhir_id');
    }

    public function taPendaftaran()
    {
        return $this->belongsTo(TaPendaftaran::class, 'ta_pendaftaran_id');
    }

    // Relationships to FtiData
    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa');
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
}
