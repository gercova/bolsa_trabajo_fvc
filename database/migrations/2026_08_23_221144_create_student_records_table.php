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
        Schema::create('student_records', function (Blueprint $table) {
            $table->id();
            // Datos personales del estudiante
            $table->foreignId('document_type_id')->nullable()->default(1)->constrained('document_type')->nullOnDelete();
            $table->string('document', 20)->nullable();
            $table->string('last_name_father', 100)->nullable();
            $table->string('last_name_mother', 100)->nullable();
            $table->string('names', 150)->nullable();
            $table->enum('gender', ['MASCULINO', 'FEMENINO'])->nullable();
            $table->date('birthdate')->nullable();
            $table->string('mother_tongue', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('pais_procedencia', 50)->nullable();

            // Datos de la institución educativa de procedencia (IE)
            $table->string('ubigeo_ie', 20)->nullable();
            $table->string('region_ie', 100)->nullable();   // Puede ser región o departamento
            $table->string('province_ie', 100)->nullable();
            $table->string('district_ie', 100)->nullable();
            $table->string('institution_type_ie', 50)->nullable();
            $table->string('modular_code_ie', 20)->nullable();
            $table->string('institution_name_ie', 200)->nullable();
            $table->string('management_type_ie', 50)->nullable();
            $table->integer('year_graduation')->nullable();

            // Datos de la institución donde se postula / matricula
            $table->string('region', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('codigo_modular', 20)->nullable();
            $table->string('nombre_institucion', 200)->nullable();
            $table->string('tipo_gestion', 50)->nullable();

            // Datos específicos del período y programa
            $table->string('academic_period', 20)->nullable();
            $table->string('study_program', 150)->nullable();
            $table->string('modality', 50)->nullable();       // Solo admisión
            $table->string('modality_type', 50)->nullable();  // Solo admisión
            $table->string('headquarters', 150)->nullable();            // Solo admisión
            $table->string('route_type', 50)->nullable();  // Solo admisión
            $table->string('shift', 20)->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('situation', 100)->nullable();
            $table->string('cycle', 20)->nullable();            // Solo matrícula
            $table->string('enrollment_status', 50)->nullable();
            $table->string('period_status', 50)->nullable();

            // Tipo de registro para distinguir origen
            $table->enum('record_type', ['ADMISION', 'MATRICULA'])->default('ADMISION');

            $table->timestamp('registration_date')->nullable();
            $table->timestamps();

            // Índices para búsquedas comunes
            $table->index('document');
            $table->index('academic_period');
            $table->index('study_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_records');
    }
};
