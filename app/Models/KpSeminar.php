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
        'bimbingan_sebelum_kp',
        'bimbingan_sewaktu_kp',
        'bimbingan_sesudah_kp',
        'total_bimbingan',
        'rekap_submitted_at',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'jadwal_seminar' => 'datetime',
        'nilai_kp' => 'decimal:2',
        'active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($seminar) {
            // Only create repository items for approved seminars
            if ($seminar->status === 'approved' || $seminar->status === 'completed') {
                $user = User::where('nim', $seminar->mahasiswa_id)->first();
                $title = $seminar->kpRequest->topik ?? 'Laporan KP';

                $files = [
                    'file_laporan_kp' => ['title' => $title . ' - Laporan', 'desc' => 'File laporan kerja praktik'],
                    'file_penilaian_perusahaan' => ['title' => $title . ' - Penilaian Perusahaan', 'desc' => 'File penilaian dari perusahaan'],
                    'file_surat_kp' => ['title' => $title . ' - Surat KP', 'desc' => 'Surat kerja praktik'],
                    'file_krs_anggota' => ['title' => $title . ' - KRS Anggota', 'desc' => 'Kartu rencana studi anggota'],
                    'file_surat_persetujuan' => ['title' => $title . ' - Surat Persetujuan', 'desc' => 'Surat persetujuan kerja praktik'],
                    'file_lembar_konfirmasi' => ['title' => $title . ' - Lembar Konfirmasi', 'desc' => 'Lembar konfirmasi kerja praktik'],
                ];

                foreach ($files as $field => $meta) {
                    if ($seminar->$field) {
                        RepositoryItem::updateOrCreate(
                            ['file_path' => $seminar->$field],
                            [
                                'type' => 'kp',
                                'title' => $meta['title'],
                                'author' => $seminar->mahasiswa_id,
                                'year' => date('Y'),
                                'file_path' => $seminar->$field,
                                'description' => $meta['desc'],
                                'category' => 'Kerja Praktik',
                                'user_id' => $user ? $user->id : null,
                            ]
                        );
                    }
                }
            }
        });
    }

    public function kpRequest()
    {
        return $this->belongsTo(KpRequest::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id', 'username');
    }
}
