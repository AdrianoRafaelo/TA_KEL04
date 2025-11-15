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
        Schema::table('kp_requests', function (Blueprint $table) {
            $table->string('mahasiswa_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kp_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->change();
        });
    }
};
