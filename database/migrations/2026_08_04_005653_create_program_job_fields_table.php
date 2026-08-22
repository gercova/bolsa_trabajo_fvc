<?php
// database/migrations/2026_08_04_000003_create_program_job_fields_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_job_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_job_fields');
    }
};