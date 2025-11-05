<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeskripsiSyaratToTaPendaftaranTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ta_pendaftaran_transaksi', function (Blueprint $table) {
            $table->text('deskripsi_syarat')->nullable()->after('file_portofolio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_pendaftaran_transaksi', function (Blueprint $table) {
            $table->dropColumn('deskripsi_syarat');
        });
    }
}
