<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    protected $table = 'kurikulum';

    protected $fillable = [
        'semester',
        'kode_mk',
        'nama_mk',
        'nama_mk_eng',
        'nama_singkat_mk',
        'sks',
        'deskripsi_mk',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
