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
        Schema::create('institutional_carousels', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable(); // Ej: "Admisión 2026-I • Modalidades Abiertas"
            $table->string('tag_icon')->nullable()->default('bi-mortarboard-fill');
            $table->string('tag_color')->nullable()->default('amber'); // amber, sky, rose, emerald, indigo, purple
            $table->string('title'); // Ej: "Tu futuro profesional empieza aquí, en el"
            $table->string('highlight_text')->nullable(); // Ej: "IESTP Francisco Vigo Caballero"
            $table->text('description')->nullable();
            $table->string('primary_button_text')->nullable(); // Ej: "Examen de Admisión"
            $table->string('primary_button_url')->nullable();
            $table->string('primary_button_icon')->nullable()->default('bi-pencil-square');
            $table->string('secondary_button_text')->nullable(); // Ej: "Ver 5 Carreras"
            $table->string('secondary_button_url')->nullable();
            $table->string('secondary_button_icon')->nullable()->default('bi-grid-3x3-gap-fill');
            $table->string('indicator_label')->nullable(); // Ej: "Admisión 2026"
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutional_carousels');
    }
};
