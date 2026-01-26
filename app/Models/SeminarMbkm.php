<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarMbkm extends Model
{
    protected $table = 'seminar_mbkm';

    protected $fillable = [
        'mahasiswa_id',
        'is_magang',
        'cpmk_ekotek',
        'cpmk_pmb',
        'laporan_ekotek_file',
        'laporan_pmb_file',
        'jadwal_seminar_file',
        'nilai',
        'active',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($seminar) {
            // Only create repository items for completed seminars with nilai
            if ($seminar->nilai && $seminar->active) {
                $user = User::where('nim', $seminar->mahasiswa_id)->first();
                $program = $seminar->is_magang ? 'Magang MBKM' : 'MBKM';

                $files = [
                    'laporan_ekotek_file' => ['title' => 'Laporan Ekotek MBKM', 'desc' => 'Laporan kegiatan ekotek MBKM'],
                    'laporan_pmb_file' => ['title' => 'Laporan PMB MBKM', 'desc' => 'Laporan kegiatan PMB MBKM'],
                    'jadwal_seminar_file' => ['title' => 'Jadwal Seminar MBKM', 'desc' => 'File jadwal seminar MBKM'],
                ];

                foreach ($files as $field => $meta) {
                    if ($seminar->$field) {
                        RepositoryItem::updateOrCreate(
                            ['file_path' => $seminar->$field],
                            [
                                'type' => 'mbkm',
                                'title' => $meta['title'],
                                'author' => $seminar->mahasiswa_id,
                                'year' => date('Y'),
                                'file_path' => $seminar->$field,
                                'description' => $meta['desc'],
                                'category' => $program,
                                'user_id' => $user ? $user->id : null,
                            ]
                        );
                    }
                }
            }
        });
    }

    public function mahasiswa()
    {
        return $this->belongsTo(FtiData::class, 'mahasiswa_id');
    }
}
