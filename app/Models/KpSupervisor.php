<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpSupervisor extends Model
{
    protected $fillable = [
        'company_id',
        'nama_supervisor',
        'no_supervisor',
        'divisi',
        'created_by',
        'updated_by',
        'active'
    ];

    public function company()
    {
        return $this->belongsTo(KpCompany::class, 'company_id');
    }

    public function requests()
    {
        return $this->hasMany(KpRequest::class, 'supervisor_id');
    }
}
