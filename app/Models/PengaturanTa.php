<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanTa extends Model
{
    protected $table = 'pengaturan_tas';

    protected $fillable = [
        'user_id',
        'batas_waktu_pendaftaran',
        'pendaftaran_ditutup',
        'pesan_penutupan',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
