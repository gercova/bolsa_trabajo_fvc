<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_schedule_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_schedule_id')
                  ->constrained('enrollment_schedules')
                  ->cascadeOnDelete();
            $table->foreignId('program_id')
                  ->nullable()
                  ->constrained('study_programs')
                  ->nullOnDelete();
            $table->integer('available_slots')->default(0);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_schedule_details');
    }
};
