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
        Schema::create('seminar_mbkm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->boolean('is_magang')->default(false);
            $table->text('cpmk_ekotek')->nullable();
            $table->text('cpmk_pmb')->nullable();
            $table->string('laporan_ekotek_file')->nullable();
            $table->string('laporan_pmb_file')->nullable();
            $table->string('jadwal_seminar_file')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('fti_datas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar_mbkm');
    }
};
