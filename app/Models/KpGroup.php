<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpGroup extends Model
{
    protected $fillable = [
        'nama_kelompok',
        'mahasiswa',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'mahasiswa' => 'array'
    ];

    public function mahasiswa()
    {
        return \App\Models\FtiData::whereIn('username', $this->mahasiswa ?? [])->get();
    }
}
