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
            $table->text('deskripsi_syarat')->default('Memenuhi sks Mahasiswa')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_pendaftaran', function (Blueprint $table) {
            $table->text('deskripsi_syarat')->nullable()->change();
        });
    }
};
