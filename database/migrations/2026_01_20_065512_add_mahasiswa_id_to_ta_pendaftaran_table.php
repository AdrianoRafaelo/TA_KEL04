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
        Schema::table('ta_pendaftaran', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('user_id');
            $table->foreign('mahasiswa_id')->references('id')->on('fti_datas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropColumn('mahasiswa_id');
        });
    }
};
