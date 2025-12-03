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
            $table->unsignedBigInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('fti_datas');
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->foreign('dosen_id')->references('id')->on('fti_datas');
            $table->date('tanggal'); // Tanggal bimbingan
            $table->text('topik_pembahasan'); // Topik yang dibahas
            $table->text('tugas_selanjutnya')->nullable(); // Tugas untuk selanjutnya
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status bimbingan
            $table->text('catatan')->nullable(); // Catatan dari dosen
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
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
            $table->dropForeign(['dosen_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::dropIfExists('ta_bimbingans');
    }
};
