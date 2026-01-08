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
        Schema::create('pendaftaran_mbkm_nonmitra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('fti_datas');
            $table->unsignedBigInteger('nonmitra_id');
            $table->foreign('nonmitra_id')->references('id')->on('mbkm_non_mitra_programs');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('nama_perusahaan');
            $table->string('posisi_mbkm');
            $table->string('file_loa');
            $table->string('file_proposal');
            $table->string('masa_mbkm');
            $table->enum('matakuliah_ekuivalensi', ['ya', 'tidak']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->enum('active', ['0', '1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_mbkm_nonmitra', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('pendaftaran_mbkm_nonmitra');
    }
};
