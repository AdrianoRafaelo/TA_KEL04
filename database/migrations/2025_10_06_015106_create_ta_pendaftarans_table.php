<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaPendaftaransTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ta_pendaftaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('file')->nullable();
            $table->text('deskripsi_syarat')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->integer('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_seminar_proposals', function (Blueprint $table) {
            $table->dropForeign(['ta_pendaftaran_id']);
        });
        Schema::dropIfExists('ta_pendaftaran');
    }
};
