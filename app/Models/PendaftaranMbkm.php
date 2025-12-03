<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranMbkm extends Model
{
    protected $table = 'pendaftaran_mbkm';

    protected $fillable = [
        'mahasiswa_id',
        'mitra_id',
        'nama',
        'nim',
        'semester',
        'ipk',
        'matakuliah_ekuivalensi',
        'file_portofolio',
        'file_cv',
        'masa_mbkm',
        'status',
        'created_by',
        'updated_by',
        'active',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id');
    }

    public function mitra()
    {
        return $this->belongsTo(\App\Models\MbkmMitra::class, 'mitra_id');
    }
}
