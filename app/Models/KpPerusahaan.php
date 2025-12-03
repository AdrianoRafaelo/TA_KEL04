<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpPerusahaan extends Model
{
    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'kontak',
        'created_by',
        'updated_by',
        'active',
    ];

    public function kpCompanies()
    {
        return $this->hasMany(KpCompany::class, 'kp_perusahaan_id');
    }
}
