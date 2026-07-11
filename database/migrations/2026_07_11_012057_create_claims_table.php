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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 11); 
            $table->string('names');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['pendiente', 'leido', 'respondido', 'cerrado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
