<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('ta_seminar_proposals', function (Blueprint $table) {
            $table->string('rubrik_penilaian')->nullable();
            $table->string('form_review')->nullable();
        });
    }

    public function down() {
        Schema::table('ta_seminar_proposals', function (Blueprint $table) {
            $table->dropColumn(['rubrik_penilaian', 'form_review']);
        });
    }
};
