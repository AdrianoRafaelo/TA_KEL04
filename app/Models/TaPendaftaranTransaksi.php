<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaPendaftaranTransaksi extends Model
{
    protected $table = 'ta_pendaftaran_transaksi';
    protected $fillable = [
        'ta_pendaftaran_id', 'file_portofolio', 'ref_status_ta_id', 'username', 'catatan',
        'created_by', 'updated_by', 'active'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(TaPendaftaran::class, 'ta_pendaftaran_id');
    }

    public function status()
    {
        return $this->belongsTo(RefStatusTa::class, 'ref_status_ta_id');
    }
}
