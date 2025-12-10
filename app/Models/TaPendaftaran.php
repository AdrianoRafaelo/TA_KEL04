<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaPendaftaran extends Model
{
    protected $table = 'ta_pendaftaran';
    protected $fillable = [
        'judul', 'user_id', 'deskripsi', 'file', 'deskripsi_syarat', 'dosen',
        'created_by', 'updated_by', 'active', 'status_id', 'user_id'
    ];

    public function transaksi()
    {
        return $this->hasMany(TaPendaftaranTransaksi::class, 'ta_pendaftaran_id');
    }

    public function status()
    {
        return $this->belongsTo(RefStatusTa::class, 'status_id');
    }

    public function mahasiswaTugasAkhir()
    {
        return $this->hasOne(MahasiswaTugasAkhir::class, 'judul', 'judul');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function seminarProposal()
    {
        return $this->hasOne(TaSeminarProposal::class);
    }

    public function taBimbingans()
    {
        return $this->hasMany(TaBimbingan::class, 'ta_pendaftaran_id');
    }
}
