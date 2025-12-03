<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaBimbingan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'tanggal',
        'topik_pembahasan',
        'tugas_selanjutnya',
        'status',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(FtiData::class, 'dosen_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
