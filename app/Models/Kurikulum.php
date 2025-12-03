<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    protected $table = 'kurikulum';

    protected $fillable = [
        'semester',
        'kode_mk',
        'nama_mk',
        'nama_mk_eng',
        'nama_singkat_mk',
        'sks',
        'deskripsi_mk',
        'cpmk',
        'dosen_id',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'cpmk' => 'array',
    ];

    // Relationships
    public function dosen()
    {
        return $this->belongsTo(FtiData::class, 'dosen_id');
    }

    public function mkKonversis()
    {
        return $this->hasMany(MkKonversi::class, 'kurikulum_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(FtiData::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(FtiData::class, 'updated_by');
    }
}
