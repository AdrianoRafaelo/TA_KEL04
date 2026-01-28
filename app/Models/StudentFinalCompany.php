<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFinalCompany extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'company_id',
        'reason',
    ];

    public function company()
    {
        return $this->belongsTo(KpCompany::class, 'company_id');
    }
}
