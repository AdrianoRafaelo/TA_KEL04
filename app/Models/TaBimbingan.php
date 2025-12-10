<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaBimbingan extends Model
{
    protected $fillable = [
        'ta_pendaftaran_id',
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
    public function taPendaftaran()
    {
        return $this->belongsTo(TaPendaftaran::class, 'ta_pendaftaran_id');
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
