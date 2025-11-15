<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSkripsi extends Model
{
    protected $table = 'ta_skripsis';

    protected $fillable = [
        'mahasiswa_tugas_akhir_id',
        'mahasiswa',
        'file_skripsi_word',
        'file_skripsi_pdf',
        'file_form_bimbingan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function mahasiswaTugasAkhir()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa_tugas_akhir_id');
    }
}
