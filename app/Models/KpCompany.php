<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpCompany extends Model
{
    protected $fillable = [
        'kp_perusahaan_id',
        'nama_perusahaan',
        'alamat_perusahaan',
        'waktu_awal_kp',
        'waktu_selesai_kp',
        'tahun_ajaran',
        'mahasiswa',
        'created_by',
        'updated_by',
        'active',
        'reason'
    ];

    protected $casts = [
        'mahasiswa' => 'array',
        'waktu_awal_kp' => 'date',
        'waktu_selesai_kp' => 'date',
    ];

    public function requests()
    {
        return $this->hasMany(KpRequest::class, 'company_id');
    }

    public function supervisors()
    {
        return $this->hasMany(KpSupervisor::class, 'company_id');
    }

    public function kpPerusahaan()
    {
        return $this->belongsTo(KpPerusahaan::class, 'kp_perusahaan_id');
    }
}
