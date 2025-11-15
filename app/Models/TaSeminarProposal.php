<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSeminarProposal extends Model
{
    protected $fillable = [
        'mahasiswa',
        'judul',
        'pembimbing',
        'pengulas_1',
        'pengulas_2',
        'file_proposal',
        'file_persetujuan',
        'form_persetujuan',
        'proposal_penelitian',
        'berita_acara_pembimbing',
        'penilaian_pembimbing',
        'berita_acara_pengulas1',
        'penilaian_pengulas1',
        'berita_acara_pengulas2',
        'penilaian_pengulas2',
        'revisi_dokumen',
        'form_revisi',
        'status',
        'catatan',
    ];

    public function mahasiswaTa()
    {
        return $this->belongsTo(MahasiswaTugasAkhir::class, 'mahasiswa', 'mahasiswa');
    }
}
