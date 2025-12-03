<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanTa extends Model
{
    protected $table = 'pengaturan_tas';

    protected $fillable = [
        'batas_waktu_pendaftaran',
        'pendaftaran_ditutup',
        'pesan_penutupan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'batas_waktu_pendaftaran' => 'datetime',
        'pendaftaran_ditutup' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
