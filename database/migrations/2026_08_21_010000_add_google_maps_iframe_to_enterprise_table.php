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
        if (Schema::hasTable('enterprise') && !Schema::hasColumn('enterprise', 'google_maps_iframe')) {
            Schema::table('enterprise', function (Blueprint $table) {
                $table->text('google_maps_iframe')->nullable()->after('address');
            });

            // Set default iframe for Francisco Vigo Caballero in Uchiza
            DB::table('enterprise')->update([
                'google_maps_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.886026543033!2d-76.46860822416807!3d-8.449056191591522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91af63dcf2a7d793%3A0x39afb5dd2aae7783!2sFRANCISCO%20VIGO%20CABALLERO!5e0!3m2!1ses!2spe!4v1740000000000!5m2!1ses!2spe" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('enterprise') && Schema::hasColumn('enterprise', 'google_maps_iframe')) {
            Schema::table('enterprise', function (Blueprint $table) {
                $table->dropColumn('google_maps_iframe');
            });
        }
    }
};
