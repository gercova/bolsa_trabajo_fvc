<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            // Elimina la restricción única pasando un arreglo con el nombre de la columna
            $table->dropUnique(['url']);
        });
    }

    public function down(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            // Si hacemos un rollback, vuelve a ser unique
            $table->unique('url');
        });
    }
};