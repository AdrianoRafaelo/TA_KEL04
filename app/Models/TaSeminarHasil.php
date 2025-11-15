<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSeminarHasil extends Model
{
    protected $table = 'ta_seminar_hasils';
    protected $fillable = [
        'mahasiswa',
        'judul',
        'pembimbing',
        'pengulas_1',
        'pengulas_2',
        'file_dokumen_ta',
        'file_log_activity',
        'file_persetujuan',
        'jadwal_seminar_file',
        'rubrik_penilaian',
        'form_review',
        'status',
        'catatan'
    ];

    public function mahasiswaTugasAkhir()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa', 'mahasiswa');
    }
}
