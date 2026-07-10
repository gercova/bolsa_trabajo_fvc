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
        Schema::create('admission', function (Blueprint $table) {
            $table->id();
            $table->string('period', 20)->nullable();
            $table->integer('total_vacancies')->default(0);
            $table->text('observation')->nullable();
            $table->date('exam_date')->nullable();
            $table->date('inscription_start_date')->nullable();
            $table->date('inscription_end_date')->nullable();
            $table->string('url_pdf')->nullable();
            $table->float('price', 2)->default(0);
            $table->enum('type', ['ordinario', 'extraordinario'])->default('ordinario');
            $table->enum('process', ['admisión', 'cepre'])->default('cepre');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cepre');
    }
};
