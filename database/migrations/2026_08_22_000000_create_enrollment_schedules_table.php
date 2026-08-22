<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('academic_period', 30);                              // e.g. "2026-II"
            $table->enum('enrollment_type', ['ordinaria', 'extraordinaria'])
                  ->default('ordinaria');
            $table->decimal('enrollment_fee', 8, 2)->default(0.00);            // costo derecho de matrícula
            $table->date('start_date');
            $table->date('end_date');
            $table->text('observations')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_schedules');
    }
};
