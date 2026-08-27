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
        Schema::create('account_balances', function (Blueprint $table) {
            $table->id();
            $table->string('month')->nullable();                 // MES
            $table->date('date')->nullable();                    // FECHA
            $table->string('receipt_number')->nullable();        // N° B/V
            $table->string('client')->nullable();                // CLIENTE
            $table->text('description')->nullable();             // DESCRIPCIÓN
            $table->string('category')->nullable();              // CATEGORÍA
            $table->string('program_code')->nullable();          // PROGRAMA (COD.)
            $table->string('program_name')->nullable();          // PROGRAMA (NOMBRE)
            $table->decimal('amount', 10, 2)->nullable();        // MONTO (S/)
            $table->string('reason')->nullable();                // MOTIVO
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_balances');
    }
};
