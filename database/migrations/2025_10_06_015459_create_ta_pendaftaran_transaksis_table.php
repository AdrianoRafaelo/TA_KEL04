<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaPendaftaranTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ta_pendaftaran_transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ta_pendaftaran_id');
            $table->string('file_portofolio')->nullable();
            $table->integer('ref_status_ta_id');
            $table->string('username');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->integer('active')->default(1);

            $table->foreign('ta_pendaftaran_id')->references('id')->on('ta_pendaftaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_pendaftaran_transaksi');
    }
};
