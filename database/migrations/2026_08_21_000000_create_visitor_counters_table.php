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
        if (!Schema::hasTable('visitor_counters')) {
            Schema::create('visitor_counters', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->unsignedBigInteger('views_count')->default(0);
                $table->timestamp('last_visit_at')->nullable();
                $table->timestamps();
            });
        }

        // Ensure default row exists
        if (!DB::table('visitor_counters')->where('key', 'total_website_visits')->exists()) {
            DB::table('visitor_counters')->insert([
                'key' => 'total_website_visits',
                'views_count' => 1,
                'last_visit_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_counters');
    }
};
