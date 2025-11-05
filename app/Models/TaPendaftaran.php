<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaPendaftaran extends Model
{
    protected $table = 'ta_pendaftaran';
    protected $fillable = [
        'judul', 'deskripsi', 'file', 'deskripsi_syarat', 'dosen',
        'created_by', 'updated_by', 'active', 'status_id'
    ];

    public function transaksi()
    {
        return $this->hasMany(TaPendaftaranTransaksi::class, 'ta_pendaftaran_id');
    }

    public function status()
    {
        return $this->belongsTo(RefStatusTa::class, 'status_id');
    }
}
