<?php
// database/migrations/2026_08_04_000002_create_program_competencies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('icon')->default('fa-graduation-cap');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_competencies');
    }
};