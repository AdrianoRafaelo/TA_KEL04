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
        Schema::table('mbkm_pelaksanaans', function (Blueprint $table) {
            $table->unsignedBigInteger('pendaftaran_mbkm_nonmitra_id')->nullable()->after('pendaftaran_mbkm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mbkm_pelaksanaans', function (Blueprint $table) {
            $table->dropColumn('pendaftaran_mbkm_nonmitra_id');
        });
    }
};
