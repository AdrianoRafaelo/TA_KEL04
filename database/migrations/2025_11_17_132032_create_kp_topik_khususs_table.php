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
        Schema::create('kp_topik_khususs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kp_request_id');
            $table->text('topik');
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('active', 10)->default('active');
            $table->timestamps();

            $table->foreign('kp_request_id')->references('id')->on('kp_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kp_topik_khususs');
    }
};
