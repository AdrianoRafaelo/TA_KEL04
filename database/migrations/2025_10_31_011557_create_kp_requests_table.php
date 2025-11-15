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
        Schema::create('kp_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['permohonan', 'pengantar']);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->string('mahasiswa_id');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->foreign('company_id')->references('id')->on('kp_companies');
            $table->foreign('supervisor_id')->references('id')->on('kp_supervisors');
            // $table->foreign('mahasiswa_id')->references('id')->on('users'); // Assuming users table for mahasiswa - commented out for now
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kp_requests');
    }
};
