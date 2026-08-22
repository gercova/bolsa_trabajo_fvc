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
        Schema::create('tupa_procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tupa_id')->constrained('tupa')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('tupa_categories')->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->text('description');
            $table->json('requirements');
            $table->string('cost');
            $table->string('uit_percent');
            $table->string('qualification');
            $table->string('duration');
            $table->string('office');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tupa_procedures');
    }
};
