<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ta_seminar_hasils', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa');
            $table->string('judul');
            $table->string('pembimbing');
            $table->string('pengulas_1')->nullable();
            $table->string('pengulas_2')->nullable();
            $table->string('file_dokumen_ta')->nullable();
            $table->string('file_log_activity')->nullable();
            $table->string('file_persetujuan')->nullable();
            $table->string('jadwal_seminar_file')->nullable();
            $table->string('rubrik_penilaian')->nullable();
            $table->string('form_review')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('ta_seminar_hasils');
    }
};
