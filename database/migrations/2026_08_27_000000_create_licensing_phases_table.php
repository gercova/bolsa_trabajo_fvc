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
        Schema::create('licensing_phases', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('phase_number');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('code', 50)->nullable();
            $table->string('stage_tag', 20)->default('P'); // P (En Proceso), C (Culminado), PTE (Pendiente), OBS (Observado)
            $table->enum('status', ['pending', 'in_progress', 'completed', 'observed'])->default('pending');
            $table->boolean('is_current')->default(false); // Etapa Actual (P)
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->text('description')->nullable();
            $table->json('milestones')->nullable(); // Detalle de CBC o metas secundarias
            $table->string('resolution_number')->nullable();
            $table->string('legal_basis')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('estimated_date')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_link')->nullable();
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
        Schema::dropIfExists('licensing_phases');
    }
};
