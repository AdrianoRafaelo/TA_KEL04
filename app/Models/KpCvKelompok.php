<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpCvKelompok extends Model
{
    protected $fillable = [
        'kp_group_id',
        'user_id',
        'file_path',
        'file_name',
        'original_name',
    ];

    public function kpGroup()
    {
        return $this->belongsTo(KpGroup::class, 'kp_group_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'user_id', 'username');
    }
}
