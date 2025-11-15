<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaBimbingan extends Model
{
    protected $fillable = [
        'mahasiswa',
        'tanggal',
        'topik_pembahasan',
        'tugas_selanjutnya',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
