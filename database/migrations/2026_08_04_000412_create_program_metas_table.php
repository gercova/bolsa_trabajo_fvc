<?php
// database/migrations/2026_08_04_000001_create_program_metas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->onDelete('cascade');
            $table->string('icon')->default('bi-mortarboard-fill');
            $table->string('accent')->default('blue');
            $table->string('bg_badge')->nullable();
            $table->string('tag')->nullable();
            $table->string('color_bar')->nullable();
            $table->string('glow_class')->nullable();
            $table->string('badge_class')->nullable();
            $table->string('accent_text')->nullable();
            $table->string('bullet_class')->nullable();
            $table->string('icon_bg_class')->nullable();
            $table->string('border_hover_class')->nullable();
            $table->string('badge_module_class')->nullable();
            $table->string('sidebar_icon_class')->nullable();
            $table->string('cta_bg_class')->nullable();
            $table->string('bar_color_class')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_metas');
    }
};