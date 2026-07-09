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
        Schema::create('ubigeo_district', function (Blueprint $table) {
            $table->string('id', 6)->primary()->collation('utf8mb4_general_ci');
            $table->string('name', 50);
            $table->string('province_id', 4)->collation('utf8mb4_general_ci');
            $table->foreign('province_id')->references('id')->on('ubigeo_province');
            $table->string('region_id', 2)->collation('utf8mb4_general_ci');
            $table->foreign('region_id')->references('id')->on('ubigeo_region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubigeo_district');
    }
};
