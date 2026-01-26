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
        Schema::table('seminar_mbkm', function (Blueprint $table) {
            $table->unsignedBigInteger('mk_konversi_id')->nullable()->after('mahasiswa_id');
            $table->foreign('mk_konversi_id')->references('id')->on('mk_konversis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar_mbkm', function (Blueprint $table) {
            $table->dropForeign(['mk_konversi_id']);
            $table->dropColumn('mk_konversi_id');
        });
    }
};
