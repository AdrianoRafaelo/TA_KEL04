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
        Schema::table('mbkm_mitras', function (Blueprint $table) {
            $table->dropColumn(['nama_mitra', 'alamat', 'kontak']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mbkm_mitras', function (Blueprint $table) {
            $table->string('nama_mitra');
            $table->text('alamat')->nullable();
            $table->string('kontak')->nullable();
        });
    }
};
