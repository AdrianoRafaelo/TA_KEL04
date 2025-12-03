<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MkKonversi extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'kurikulum_id',
        'deskripsi_kegiatan',
        'alokasi_waktu',
        'status',
        'created_by',
        'updated_by',
        'active',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id');
    }

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
