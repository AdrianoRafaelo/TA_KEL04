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
        Schema::create('repository_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['ta', 'kp', 'mbkm', 'manual']);
            $table->string('title');
            $table->string('author')->nullable();
            $table->year('year')->nullable();
            $table->string('file_path');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // e.g., skripsi, proposal, laporan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repository_items');
    }
};
