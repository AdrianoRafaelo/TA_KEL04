<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpRequest extends Model
{
    protected $fillable = [
        'type',
        'company_id',
        'supervisor_id',
        'dosen_id',
        'divisi',
        'mahasiswa_id',
        'status',
        'notes',
        'created_by',
        'updated_by',
        'active'
    ];

    public function company()
    {
        return $this->belongsTo(KpCompany::class, 'company_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(KpSupervisor::class, 'supervisor_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(FtiData::class, 'dosen_id');
    }
}
