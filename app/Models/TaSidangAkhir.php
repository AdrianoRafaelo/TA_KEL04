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
}
