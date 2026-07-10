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
        Schema::create('enterprise', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11)->unique();
            $table->string('company_name', 150);
            $table->string('trade_name', 150);
            $table->string('legal_representative_dni', 11);
            $table->string('legal_representative', 100);
            $table->string('address', 100)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('business_sector')->nullable();
            $table->string('phrase')->nullable();
            $table->text('description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('phone_number_1', 20)->nullable();
            $table->string('phone_number_2', 20)->nullable();
            $table->string('email', 100)->unique();
            $table->string('facebook_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->text('principles')->nullable();
            $table->text('values')->nullable();
            $table->string('color')->default('bg-amber-500');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise');
    }
};
