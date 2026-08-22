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
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('logo_path')->nullable();
            $table->string('training_itinerary_path')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('details')->nullable();
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
            $table->unsignedTinyInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};
