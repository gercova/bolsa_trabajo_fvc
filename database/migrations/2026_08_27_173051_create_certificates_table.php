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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('certificate_code')->unique();
            $table->string('description')->nullable()->comment('Descripción breve del certificado');
            $table->date('start_date')->nullable()->comment('Fecha de inicio del certificado');
            $table->date('end_date')->nullable()->comment('Fecha de fin del certificado');
            $table->string('duration')->nullable()->comment('Duración del certificado');
            $table->string('modality')->default('Presencial');
            $table->date('issue_date');
            $table->boolean('is_active')->default(true);
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
