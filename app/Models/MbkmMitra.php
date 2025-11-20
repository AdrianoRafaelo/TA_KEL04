<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbkmMitra extends Model
{
    protected $table = 'mbkm_mitras';

    protected $fillable = [
        'nama_perusahaan',
        'created_by',
        'updated_by',
        'active',
    ];
}
