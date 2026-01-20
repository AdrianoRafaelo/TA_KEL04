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
        Schema::create('mbkm_pelaksanaan_non_mitra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('pendaftaran_mbkm_nonmitra_id');
            $table->integer('minggu');
            $table->string('matkul');
            $table->text('deskripsi_kegiatan');
            $table->text('bimbingan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('fti_datas')->onDelete('cascade');
            $table->foreign('pendaftaran_mbkm_nonmitra_id')->references('id')->on('pendaftaran_mbkm_nonmitra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbkm_pelaksanaan_non_mitra');
    }
};
