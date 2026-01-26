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

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($skripsi) {
            // Create repository items when files are uploaded (TA doesn't require approval)
            if (($skripsi->file_skripsi_pdf || $skripsi->file_skripsi_word) && $skripsi->active) {
                // Get user ID from mahasiswa (assuming mahasiswa is username)
                $user = User::where('nim', $skripsi->mahasiswa)->first();

                $title = optional($skripsi->mahasiswaTugasAkhir)->judul ?? 'Skripsi';

                // Create repository items for each file
                if ($skripsi->file_skripsi_pdf) {
                    RepositoryItem::updateOrCreate(
                        ['file_path' => $skripsi->file_skripsi_pdf],
                        [
                            'type' => 'ta',
                            'title' => $title . ' (PDF)',
                            'author' => $skripsi->mahasiswa,
                            'year' => date('Y'),
                            'file_path' => $skripsi->file_skripsi_pdf,
                            'description' => 'File skripsi dalam format PDF',
                            'category' => 'Skripsi',
                            'user_id' => $user ? $user->id : null,
                        ]
                    );
                }

                if ($skripsi->file_skripsi_word) {
                    RepositoryItem::updateOrCreate(
                        ['file_path' => $skripsi->file_skripsi_word],
                        [
                            'type' => 'ta',
                            'title' => $title . ' (Word)',
                            'author' => $skripsi->mahasiswa,
                            'year' => date('Y'),
                            'file_path' => $skripsi->file_skripsi_word,
                            'description' => 'File skripsi dalam format Word',
                            'category' => 'Skripsi',
                            'user_id' => $user ? $user->id : null,
                        ]
                    );
                }
            }
        });
    }

    public function mahasiswaTugasAkhir()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa_tugas_akhir_id');
    }

    // Direct relationship to FtiData
    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa');
    }
}
