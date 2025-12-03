<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSidangAkhir extends Model
{
    protected $fillable = [
        'mahasiswa',
        'judul',
        'pembimbing',
        'pengulas_1',
        'pengulas_2',
        'file_dokumen_ta',
        'file_log_activity',
        'file_persetujuan',
        'jadwal_sidang_file',
        'berita_acara',
        'form_penilaian',
        'revisi_dokumen',
        'form_revisi',
        'status',
        'catatan',
    ];

    // Relationships
    public function mahasiswaTugasAkhir()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa', 'mahasiswa');
    }

    // Direct relationships to FtiData
    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa');
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'mahasiswa', 'nim');
    }

    public function pembimbing()
    {
        return $this->belongsTo(FtiData::class, 'pembimbing');
    }

    public function pengulas1()
    {
        return $this->belongsTo(FtiData::class, 'pengulas_1');
    }

    public function pengulas2()
    {
        return $this->belongsTo(FtiData::class, 'pengulas_2');
    }
}
