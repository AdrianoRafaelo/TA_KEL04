<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpTopikKhusus extends Model
{
    protected $table = 'kp_topik_khususs';

    protected $fillable = [
        'kp_request_id',
        'topik',
        'status',
        'created_by',
        'updated_by',
        'active'
    ];

    public function kpRequest()
    {
        return $this->belongsTo(KpRequest::class);
    }
}
