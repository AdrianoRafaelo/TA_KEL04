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
        // Clean invalid foreign keys
        DB::statement('UPDATE mbkm_pelaksanaans SET pendaftaran_mbkm_id = NULL WHERE pendaftaran_mbkm_id IS NOT NULL AND pendaftaran_mbkm_id NOT IN (SELECT id FROM pendaftaran_mbkm)');
        DB::statement('UPDATE mbkm_pelaksanaans SET pendaftaran_mbkm_nonmitra_id = NULL WHERE pendaftaran_mbkm_nonmitra_id IS NOT NULL AND pendaftaran_mbkm_nonmitra_id NOT IN (SELECT id FROM pendaftaran_mbkm_nonmitra)');

        Schema::table('mbkm_pelaksanaans', function (Blueprint $table) {
            $table->foreign('pendaftaran_mbkm_id')->references('id')->on('pendaftaran_mbkm')->onDelete('cascade');
            $table->foreign('pendaftaran_mbkm_nonmitra_id')->references('id')->on('pendaftaran_mbkm_nonmitra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mbkm_pelaksanaans', function (Blueprint $table) {
            $table->dropForeign(['pendaftaran_mbkm_id']);
            $table->dropForeign(['pendaftaran_mbkm_nonmitra_id']);
        });
    }
};
