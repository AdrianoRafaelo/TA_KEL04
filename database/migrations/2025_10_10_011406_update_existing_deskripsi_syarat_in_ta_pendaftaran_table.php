<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('ta_pendaftaran')->whereNull('deskripsi_syarat')->update(['deskripsi_syarat' => 'Memenuhi sks Mahasiswa']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: revert to null, but since default is set, maybe not necessary
        // DB::table('ta_pendaftaran')->where('deskripsi_syarat', 'Memenuhi sks Mahasiswa')->update(['deskripsi_syarat' => null]);
    }
};
