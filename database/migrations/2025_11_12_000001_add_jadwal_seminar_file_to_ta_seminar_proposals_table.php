<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('ta_seminar_proposals', function (Blueprint $table) {
            $table->string('jadwal_seminar_file')->nullable();
        });
    }
    public function down() {
        Schema::table('ta_seminar_proposals', function (Blueprint $table) {
            $table->dropColumn('jadwal_seminar_file');
        });
    }
};
