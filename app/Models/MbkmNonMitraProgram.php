<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbkmNonMitraProgram extends Model
{
    protected $table = 'mbkm_non_mitra_programs';

    protected $fillable = [
        'user_id',
        'nama_program',
        'created_by',
        'updated_by',
        'active',
    ];

    // Relationships
    public function mbkmRegistrations()
    {
        return $this->hasMany(PendaftaranMbkmNonmitra::class, 'nonmitra_id');
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
