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
        Schema::create('ta_seminar_proposals', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa');
            $table->foreignId('ta_pendaftaran_id')->unique()->constrained('ta_pendaftaran');
            $table->string('judul'); // Judul TA
            $table->string('pembimbing'); // Pembimbing
            $table->string('file_proposal')->nullable(); // File proposal yang diunggah
            $table->string('file_persetujuan')->nullable(); // File form persetujuan
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status seminar
            $table->text('catatan')->nullable(); // Catatan dari koordinator
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_seminar_proposals', function (Blueprint $table) {
            $table->dropForeign(['ta_pendaftaran_id']);
        });
        Schema::dropIfExists('ta_seminar_proposals');
    }
};
