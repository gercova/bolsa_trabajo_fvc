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
        Schema::create('scholarship_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('academic_period', 50)->index(); // e.g. 2026-I, 2026-II
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('resolution_number', 100)->nullable();
            $table->string('file_path', 255);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->date('publication_date')->nullable();
            $table->foreignId('scholarship_id')->nullable()->constrained('scholarships')->nullOnDelete();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_beneficiaries');
    }
};
