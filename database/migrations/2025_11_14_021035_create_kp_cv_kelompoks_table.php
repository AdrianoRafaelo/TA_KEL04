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
        Schema::create('kp_cv_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kp_group_id');
            $table->string('user_id'); // username mahasiswa
            $table->string('file_path');
            $table->string('file_name');
            $table->string('original_name');
            $table->timestamps();

            $table->foreign('kp_group_id')->references('id')->on('kp_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kp_cv_kelompoks');
    }
};
