<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaBimbingan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
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
    public function mahasiswaTa()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa_id');
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
