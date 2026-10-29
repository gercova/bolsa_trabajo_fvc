<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_programs', function (Blueprint $table) {
            $table->text('carousel_alt_text')->nullable()->after('description');
            $table->boolean('enable_carousel')->default(false)->after('carousel_alt_text');
        });
    }

    public function down(): void
    {
        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropColumn(['carousel_alt_text', 'enable_carousel']);
        });
    }
};
