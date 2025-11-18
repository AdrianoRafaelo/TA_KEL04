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
        Schema::create('kp_seminars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kp_request_id');
            $table->string('mahasiswa_id');
            $table->string('status')->default('pending'); // pending, approved, completed
            $table->string('file_laporan_kp')->nullable();
            $table->string('file_penilaian_perusahaan')->nullable();
            $table->string('file_surat_kp')->nullable();
            $table->datetime('jadwal_seminar')->nullable();
            $table->decimal('nilai_kp', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('kp_request_id')->references('id')->on('kp_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kp_seminars');
    }
};
