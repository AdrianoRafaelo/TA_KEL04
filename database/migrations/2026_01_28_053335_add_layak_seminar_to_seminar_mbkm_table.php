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
            $table->enum('layak_seminar', ['layak', 'tidak_layak'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar_mbkm', function (Blueprint $table) {
            $table->dropColumn('layak_seminar');
        });
    }
};
