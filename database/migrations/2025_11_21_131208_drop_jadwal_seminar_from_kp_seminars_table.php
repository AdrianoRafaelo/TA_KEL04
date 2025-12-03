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
        Schema::table('kp_seminars', function (Blueprint $table) {
            $table->dropColumn('jadwal_seminar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kp_seminars', function (Blueprint $table) {
            $table->datetime('jadwal_seminar')->nullable();
        });
    }
};
