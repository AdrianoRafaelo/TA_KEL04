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
        Schema::create('mbkm_pelaksanaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->integer('minggu');
            $table->string('matkul');
            $table->text('deskripsi_kegiatan');
            $table->text('bimbingan')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('fti_datas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbkm_pelaksanaans');
    }
};
