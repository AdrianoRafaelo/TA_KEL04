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
        Schema::create('ta_bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa_tugas_akhirs');
            $table->date('tanggal'); // Tanggal bimbingan
            $table->text('topik_pembahasan'); // Topik yang dibahas
            $table->text('tugas_selanjutnya')->nullable(); // Tugas untuk selanjutnya
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status bimbingan
            $table->text('catatan')->nullable(); // Catatan dari dosen
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_bimbingans', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
        });
        Schema::dropIfExists('ta_bimbingans');
    }
};
