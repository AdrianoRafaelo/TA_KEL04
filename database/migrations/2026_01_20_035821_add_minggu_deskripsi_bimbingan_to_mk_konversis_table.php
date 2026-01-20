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
        Schema::table('mk_konversis', function (Blueprint $table) {
            $table->integer('minggu')->nullable()->after('kurikulum_id');
            $table->string('matkul')->nullable()->after('minggu');
            $table->text('bimbingan')->nullable()->after('deskripsi_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mk_konversis', function (Blueprint $table) {
            $table->dropColumn(['minggu', 'matkul', 'bimbingan']);
        });
    }
};
