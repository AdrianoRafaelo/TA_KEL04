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
        Schema::table('ta_sidang_akhirs', function (Blueprint $table) {
            $table->dropColumn(['berita_acara', 'form_penilaian']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_sidang_akhirs', function (Blueprint $table) {
            $table->string('berita_acara')->nullable();
            $table->string('form_penilaian')->nullable();
        });
    }
};
