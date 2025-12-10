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
        Schema::create('mahasiswa_tugas_akhirs', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa');
            $table->foreignId('ta_pendaftaran_id')->unique()->constrained('ta_pendaftaran');
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
        Schema::dropIfExists('mahasiswa_tugas_akhirs');
    }
};
