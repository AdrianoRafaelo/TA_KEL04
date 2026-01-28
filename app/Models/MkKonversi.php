<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MkKonversi extends Model
{
    protected $fillable = [
        'user_id',
        'mahasiswa_id',
        'kurikulum_id',
        'minggu',
        'matkul',
        'deskripsi_kegiatan',
        'bimbingan',
        'alokasi_waktu',
        'file_kesesuaian',
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
