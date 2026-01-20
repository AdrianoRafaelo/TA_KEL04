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
        // Copy data from mbkm_pelaksanaans where pendaftaran_mbkm_nonmitra_id is not null to mbkm_pelaksanaan_non_mitra
        DB::statement("
            INSERT INTO mbkm_pelaksanaan_non_mitra (
                mahasiswa_id,
                pendaftaran_mbkm_nonmitra_id,
                minggu,
                matkul,
                deskripsi_kegiatan,
                bimbingan,
                status,
                created_by,
                updated_by,
                active,
                created_at,
                updated_at
            )
            SELECT
                mahasiswa_id,
                pendaftaran_mbkm_nonmitra_id,
                minggu,
                matkul,
                deskripsi_kegiatan,
                bimbingan,
                status,
                created_by,
                updated_by,
                active,
                created_at,
                updated_at
            FROM mbkm_pelaksanaans
            WHERE pendaftaran_mbkm_nonmitra_id IS NOT NULL
        ");

        // Delete the copied data from the old table
        DB::statement("
            DELETE FROM mbkm_pelaksanaans
            WHERE pendaftaran_mbkm_nonmitra_id IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new', function (Blueprint $table) {
            //
        });
    }
};
