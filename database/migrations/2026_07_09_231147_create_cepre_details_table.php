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
        Schema::create('admission_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained('admission')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('study_programs')->onDelete('cascade');
            $table->integer('vacancies')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_details');
    }
};
