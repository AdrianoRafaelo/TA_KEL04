<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSidangAkhir extends Model
{
    protected $fillable = [
        'mahasiswa',
        'ta_pendaftaran_id',
        'ta_seminar_hasils_id',
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

    public function tapendaftaran()
    {
        return $this->belongsTo(TaPendaftaran::class);
    }

    public function taSeminarHasil()
    {
        return $this->belongsTo(TaSeminarHasil::class, 'ta_seminar_hasils_id');
    }



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

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($sidang) {
            // Create repository items when final TA document is uploaded
            if ($sidang->file_dokumen_ta && $sidang->status !== 'rejected') {
                $user = User::where('nim', $sidang->mahasiswa)->first();
                $title = $sidang->judul ?? 'Dokumen TA Final';

                RepositoryItem::updateOrCreate(
                    ['file_path' => $sidang->file_dokumen_ta],
                    [
                        'type' => 'ta',
                        'title' => $title,
                        'author' => $sidang->mahasiswa,
                        'year' => date('Y'),
                        'file_path' => $sidang->file_dokumen_ta,
                        'description' => 'Dokumen Tugas Akhir - Sidang Akhir',
                        'category' => 'Tugas Akhir',
                        'user_id' => $user ? $user->id : null,
                    ]
                );
            }
        });
    }
}
