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
            $table->string('pembimbing')->nullable();
            $table->string('pengulas1')->nullable();
            $table->string('pengulas2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['pembimbing', 'pengulas1', 'pengulas2']);
        });
    }
};
