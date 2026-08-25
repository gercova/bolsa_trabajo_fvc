<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('degree_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('record_number')->nullable();          // N° del reporte
            $table->string('modular_code', 20);                            // Código modular
            $table->string('institution_name');                            // Nombre institución
            $table->string('management_type', 50);                         // Tipo de gestión
            $table->string('department', 100);                             // Departamento
            $table->string('study_program');                               // Programa de estudios
            $table->text('mention')->nullable();                           // Mención
            $table->string('formative_level')->nullable();                 // Nivel formativo
            $table->string('productive_family')->nullable();               // Familia productiva
            $table->string('document_type', 20)->nullable();               // Tipo de documento
            $table->string('document_number', 30)->nullable();             // Número de documento
            $table->string('full_names');                                  // Nombres completos
            $table->date('birth_date')->nullable();                        // Fecha de nacimiento
            $table->string('gender', 20)->nullable();                      // Sexo
            $table->date('graduation_date')->nullable();                   // Fecha de egreso
            $table->string('institutional_registration_number')->nullable(); // N° registro institucional
            $table->date('diploma_issue_date')->nullable();                // Fecha emisión diploma
            $table->timestamp('minedu_registration_date')->nullable();     // Fecha registro MINEDU
            $table->string('generated_title_code')->nullable();            // Código de título generado
            $table->string('file_number')->nullable();                     // Número de expediente
            $table->string('registration_type', 50)->nullable();           // Tipo de registro
            $table->string('specialist_user')->nullable();                 // Usuario especialista
            $table->string('diploma_type', 50)->nullable();                // Tipo de diploma
            $table->timestamps();

            // Índices opcionales para búsquedas frecuentes
            $table->index('document_number');
            $table->index('full_names');
            $table->index('modular_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grados_titulos');
    }
};