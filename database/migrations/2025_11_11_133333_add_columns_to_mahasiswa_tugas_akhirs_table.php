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
        Schema::table('mahasiswa_tugas_akhirs', function (Blueprint $table) {
            $table->dropForeign(['ta_pendaftaran_id']);
            $table->dropColumn(['ta_pendaftaran_id', 'peran', 'nama_dosen']);
            $table->string('mahasiswa');
            $table->string('judul');
            $table->string('pembimbing')->nullable();
            $table->string('pengulas_1')->nullable();
            $table->string('pengulas_2')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa_tugas_akhirs', function (Blueprint $table) {
            $table->dropColumn(['mahasiswa', 'judul', 'pembimbing', 'pengulas_1', 'pengulas_2', 'created_by', 'updated_by', 'active']);
            $table->unsignedBigInteger('ta_pendaftaran_id');
            $table->enum('peran', ['pembimbing', 'pengulas1', 'pengulas2']);
            $table->string('nama_dosen');
            $table->foreign('ta_pendaftaran_id')->references('id')->on('ta_pendaftaran')->onDelete('cascade');
        });
    }
};
