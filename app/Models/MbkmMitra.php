<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbkmMitra extends Model
{
    protected $table = 'mbkm_mitras';

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'created_by',
        'updated_by',
        'active',
    ];

    // Relationships
    public function mbkmRegistrations()
    {
        return $this->hasMany(PendaftaranMbkm::class, 'mitra_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(FtiData::class, 'created_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(FtiData::class, 'updated_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
