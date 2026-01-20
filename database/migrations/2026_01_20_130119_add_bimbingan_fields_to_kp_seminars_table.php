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
            $table->integer('bimbingan_sebelum_kp')->default(0)->after('active');
            $table->integer('bimbingan_sewaktu_kp')->default(0)->after('bimbingan_sebelum_kp');
            $table->integer('bimbingan_sesudah_kp')->default(0)->after('bimbingan_sewaktu_kp');
            $table->integer('total_bimbingan')->default(0)->after('bimbingan_sesudah_kp');
            $table->timestamp('rekap_submitted_at')->nullable()->after('total_bimbingan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kp_seminars', function (Blueprint $table) {
            $table->dropColumn(['bimbingan_sebelum_kp', 'bimbingan_sewaktu_kp', 'bimbingan_sesudah_kp', 'total_bimbingan', 'rekap_submitted_at']);
        });
    }
};
