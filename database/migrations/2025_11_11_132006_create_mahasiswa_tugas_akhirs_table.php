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
        Schema::create('mahasiswa_tugas_akhirs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ta_pendaftaran_id');
            $table->enum('peran', ['pembimbing', 'pengulas1', 'pengulas2']);
            $table->string('nama_dosen');
            $table->timestamps();

            $table->foreign('ta_pendaftaran_id')->references('id')->on('ta_pendaftaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_tugas_akhirs');
    }
};
