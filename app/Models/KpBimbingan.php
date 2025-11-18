<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpBimbingan extends Model
{
    protected $table = 'kp_bimbingans';

    protected $fillable = [
        'kp_request_id',
        'tanggal',
        'topik',
        'jenis',
        'status',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kpRequest()
    {
        return $this->belongsTo(KpRequest::class);
    }
}
