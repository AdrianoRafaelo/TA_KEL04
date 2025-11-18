<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpAktivitas extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'deskripsi',
        'file_path',
        'file_name',
        'original_name',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'active'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id', 'username');
    }
}
