<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranMbkmNonmitra extends Model
{
    protected $table = 'pendaftaran_mbkm_nonmitra';

    protected $fillable = [
        'mahasiswa_id',
        'nonmitra_id',
        'user_id',
        'nama_perusahaan',
        'posisi_mbkm',
        'file_loa',
        'file_proposal',
        'masa_mbkm',
        'matakuliah_ekuivalensi',
        'status',
        'created_by',
        'updated_by',
        'active',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id');
    }

    public function program()
    {
        return $this->belongsTo(MbkmNonMitraProgram::class, 'nonmitra_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
