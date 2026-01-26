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
            $table->string('berita_acara_pembimbing')->nullable();
            $table->string('penilaian_pembimbing')->nullable();
            $table->string('berita_acara_pengulas1')->nullable();
            $table->string('penilaian_pengulas1')->nullable();
            $table->string('berita_acara_pengulas2')->nullable();
            $table->string('penilaian_pengulas2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_sidang_akhirs', function (Blueprint $table) {
            $table->dropColumn([
                'berita_acara_pembimbing',
                'penilaian_pembimbing',
                'berita_acara_pengulas1',
                'penilaian_pengulas1',
                'berita_acara_pengulas2',
                'penilaian_pengulas2'
            ]);
        });
    }
};
