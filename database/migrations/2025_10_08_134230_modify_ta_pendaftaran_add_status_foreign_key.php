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
            $table->unsignedBigInteger('status_id')->nullable()->after('active'); // Tambahkan kolom
            $table->foreign('status_id', 'ta_pendaftaran_status_id_foreign') // Beri nama eksplisit
                  ->references('id')->on('ref_status_ta') // Rujuk ke ref_status_ta, bukan statuses
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_pendaftaran', function (Blueprint $table) {
            $table->dropForeign('ta_pendaftaran_status_id_foreign'); // Drop foreign key dengan nama eksplisit
            $table->dropColumn('status_id');
        });
    }
};