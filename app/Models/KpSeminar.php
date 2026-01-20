<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpSeminar extends Model
{
    protected $fillable = [
        'kp_request_id',
        'mahasiswa_id',
        'status',
        'file_laporan_kp',
        'file_penilaian_perusahaan',
        'file_surat_kp',
        'file_krs_anggota',
        'file_surat_persetujuan',
        'file_lembar_konfirmasi',
        'jadwal_seminar',
        'nilai_kp',
        'catatan',
        'penguji',
        'jadwal_seminar_file',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'jadwal_seminar' => 'datetime',
        'nilai_kp' => 'decimal:2',
        'active' => 'boolean'
    ];

    public function kpRequest()
    {
        return $this->belongsTo(KpRequest::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id', 'username');
    }
}
