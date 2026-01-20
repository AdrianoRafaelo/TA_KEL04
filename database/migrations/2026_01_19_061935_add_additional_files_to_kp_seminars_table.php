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
        Schema::table('kp_seminars', function (Blueprint $table) {
            $table->string('file_krs_anggota')->nullable();
            $table->string('file_surat_persetujuan')->nullable();
            $table->string('file_lembar_konfirmasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kp_seminars', function (Blueprint $table) {
            $table->dropColumn(['file_krs_anggota', 'file_surat_persetujuan', 'file_lembar_konfirmasi']);
        });
    }
};
