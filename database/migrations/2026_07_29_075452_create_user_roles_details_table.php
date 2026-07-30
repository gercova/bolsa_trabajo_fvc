<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Stores extended role detail for institutional staff (especially teachers).
     * - user_id         → the user (teacher / coordinator) this record belongs to
     * - program_id      → the study programme the teacher is associated with (nullable for non-teaching staff)
     * - is_coordinator  → true when this teacher also holds the programme coordinator role
     * - specialty       → optional free-text field for the teacher's area of expertise
     * - is_active       → allows toggling visibility without deleting the record
     */
    public function up(): void
    {
        Schema::create('user_roles_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('program_id')
                  ->nullable()
                  ->constrained('study_programs')
                  ->nullOnDelete();

            $table->boolean('is_coordinator')->default(false)
                  ->comment('True when the teacher is also the programme coordinator');

            $table->string('specialty')->nullable()
                  ->comment('Area of expertise / subject specialty');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Prevent assigning the same user to the same programme twice
            $table->unique(['user_id', 'program_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles_details');
    }
};
