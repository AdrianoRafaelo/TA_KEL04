<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpDocument extends Model
{
    protected $fillable = [
        'request_id',
        'file_path',
        'file_name',
        'file_type',
        'expired_date',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'expired_date' => 'date',
    ];

    public function request()
    {
        return $this->belongsTo(KpRequest::class, 'request_id');
    }
}
