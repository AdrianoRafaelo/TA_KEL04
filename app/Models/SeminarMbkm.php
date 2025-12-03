<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarMbkm extends Model
{
    protected $table = 'seminar_mbkm';

    protected $fillable = [
        'mahasiswa_id',
        'is_magang',
        'cpmk_ekotek',
        'cpmk_pmb',
        'laporan_ekotek_file',
        'laporan_pmb_file',
        'jadwal_seminar_file',
        'active',
        'created_by',
        'updated_by',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id');
    }
}
