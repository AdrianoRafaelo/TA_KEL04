<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSeminarHasil extends Model
{
    protected $table = 'ta_seminar_hasils';
    protected $fillable = [
        'mahasiswa',
        'ta_pendaftaran_id',
        'ta_seminar_proposals_id',
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
        'berita_acara_pembimbing',
        'penilaian_pembimbing',
        'berita_acara_pengulas1',
        'penilaian_pengulas1',
        'berita_acara_pengulas2',
        'penilaian_pengulas2',
        'revisi_dokumen',
        'form_revisi',
        'status',
        'catatan'
    ];

    public function mahasiswaTugasAkhir()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa', 'mahasiswa');
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

    public function taPendaftaran()
    {
        return $this->belongsTo(TaPendaftaran::class);
    }

    public function taSeminarProposal()
    {
        return $this->belongsTo(TaSeminarProposal::class, 'ta_seminar_proposals_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($seminar) {
            // Create repository items when TA document is uploaded
            if ($seminar->file_dokumen_ta && $seminar->status !== 'rejected') {
                $user = User::where('nim', $seminar->mahasiswa)->first();
                $title = $seminar->judul ?? 'Dokumen TA';

                RepositoryItem::updateOrCreate(
                    ['file_path' => $seminar->file_dokumen_ta],
                    [
                        'type' => 'ta',
                        'title' => $title,
                        'author' => $seminar->mahasiswa,
                        'year' => date('Y'),
                        'file_path' => $seminar->file_dokumen_ta,
                        'description' => 'Dokumen Tugas Akhir - Seminar Hasil',
                        'category' => 'Tugas Akhir',
                        'user_id' => $user ? $user->id : null,
                    ]
                );
            }
        });
    }
}
