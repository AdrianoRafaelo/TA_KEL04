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
        Schema::create('ta_sidang_akhirs', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa'); // NIM atau username mahasiswa
            $table->foreignId('ta_pendaftaran_id')->unique()->constrained('ta_pendaftaran');
            $table->foreignId('ta_seminar_hasils_id')->unique()->constrained('ta_seminar_hasils')->onDelete('restrict');
            $table->string('pembimbing')->nullable(); // Pembimbing
            $table->string('pengulas_1')->nullable(); // Pengulas I
            $table->string('pengulas_2')->nullable(); // Pengulas II
            $table->string('file_skripsi')->nullable(); // File skripsi
            $table->string('jadwal_sidang_file')->nullable(); // File jadwal sidang
            $table->string('revisi_dokumen')->nullable(); // Dokumen revisi
            $table->string('form_revisi')->nullable(); // Form revisi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status sidang
            $table->text('catatan')->nullable(); // Catatan dari koordinator
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_sidang_akhirs', function (Blueprint $table) {
            $table->dropForeign(['ta_pendaftaran_id']);
            $table->dropForeign(['ta_seminar_hasils_id']);
        });
        Schema::dropIfExists('ta_sidang_akhirs');
    }
};
