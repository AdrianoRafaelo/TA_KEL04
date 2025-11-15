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
        Schema::create('ta_skripsis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_tugas_akhir_id');
            $table->string('mahasiswa');
            $table->string('file_skripsi_word')->nullable();
            $table->string('file_skripsi_pdf')->nullable();
            $table->string('file_form_bimbingan')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'revision'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_tugas_akhir_id')->references('id')->on('mahasiswa_tugas_akhirs')->onDelete('cascade');
            $table->unique('mahasiswa_tugas_akhir_id'); // Satu skripsi per mahasiswa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_skripsis');
    }
};
