<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FtiData extends Model
{
    protected $table = 'fti_datas';

    protected $fillable = [
        'nama', 'user_id', 'username', 'nim', 'role', 'role_id', 'prodi', 'fakultas', 'status'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

}
