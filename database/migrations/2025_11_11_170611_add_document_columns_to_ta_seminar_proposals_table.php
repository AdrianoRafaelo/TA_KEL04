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
        Schema::table('ta_seminar_proposals', function (Blueprint $table) {
            $table->string('pengulas_1')->nullable();
            $table->string('pengulas_2')->nullable();
            $table->string('form_persetujuan')->nullable();
            $table->string('proposal_penelitian')->nullable();
            $table->string('berita_acara_pembimbing')->nullable();
            $table->string('penilaian_pembimbing')->nullable();
            $table->string('berita_acara_pengulas1')->nullable();
            $table->string('penilaian_pengulas1')->nullable();
            $table->string('berita_acara_pengulas2')->nullable();
            $table->string('penilaian_pengulas2')->nullable();
            $table->string('revisi_dokumen')->nullable();
            $table->string('form_revisi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_seminar_proposals', function (Blueprint $table) {
            $table->dropColumn([
                'pengulas_1',
                'pengulas_2',
                'form_persetujuan',
                'proposal_penelitian',
                'berita_acara_pembimbing',
                'penilaian_pembimbing',
                'berita_acara_pengulas1',
                'penilaian_pengulas1',
                'berita_acara_pengulas2',
                'penilaian_pengulas2',
                'revisi_dokumen',
                'form_revisi'
            ]);
        });
    }
};
