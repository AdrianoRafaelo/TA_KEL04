<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbkmPelaksanaan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'pendaftaran_mbkm_id',
        'pendaftaran_mbkm_nonmitra_id',
        'minggu',
        'matkul',
        'deskripsi_kegiatan',
        'bimbingan',
        'status',
        'created_by',
        'updated_by',
        'active',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id');
    }
}
