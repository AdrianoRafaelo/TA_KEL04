<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbkmPelaksanaan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'minggu',
        'matkul',
        'deskripsi_kegiatan',
        'bimbingan',
        'created_by',
        'updated_by',
        'active',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id');
    }
}
