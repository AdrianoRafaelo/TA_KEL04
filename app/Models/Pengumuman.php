<?php

// app/Models/Pengumuman.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    // Perhatikan: nama tabel di Laravel umumnya jamak, tapi jika Anda ingin 
    // eksplisit menggunakan 'pengumuman' (tunggal), tambahkan properti ini.
    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'deskripsi',
        'kategori',
    ];

    public const KATEGORI_OPTIONS = ['Kompetisi', 'Magang', 'Umum'];
}