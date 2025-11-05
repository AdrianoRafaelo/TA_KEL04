<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefStatusTa extends Model
{
    protected $table = 'ref_status_ta';
    protected $fillable = ['name'];

    public function taPendaftarans()
    {
        return $this->hasMany(TaPendaftaran::class, 'status_id');
    }
}
