<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (! Schema::hasColumn('certificates', 'code')) {
                $table->string('code')->nullable()->unique()->after('id')->comment('Identificador / código del certificado');
            }
        });

        // Copiar certificate_code a code para registros existentes
        DB::table('certificates')->whereNull('code')->update([
            'code' => DB::raw('certificate_code'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
