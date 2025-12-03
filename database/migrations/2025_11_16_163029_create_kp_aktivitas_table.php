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
        Schema::create('kp_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('nim')->on('users');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('original_name')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kp_aktivitas', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
        });
        Schema::dropIfExists('kp_aktivitas');
    }
};
