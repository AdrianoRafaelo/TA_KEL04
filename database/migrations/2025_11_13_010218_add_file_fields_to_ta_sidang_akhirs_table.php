<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ta_sidang_akhirs', function (Blueprint $table) {
            // Rename file_skripsi to file_dokumen_ta
            $table->renameColumn('file_skripsi', 'file_dokumen_ta');

            // Add new columns
            $table->string('file_log_activity')->nullable();
            $table->string('file_persetujuan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_sidang_akhirs', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['file_log_activity', 'file_persetujuan']);

            // Rename back to file_skripsi
            $table->renameColumn('file_dokumen_ta', 'file_skripsi');
        });
    }
};
