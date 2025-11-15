<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaTugasAkhir extends Model
{
    protected $table = 'mahasiswa_tugas_akhirs';
    protected $fillable = ['mahasiswa', 'judul', 'pembimbing', 'pengulas_1', 'pengulas_2', 'created_by', 'updated_by', 'active'];

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
}
