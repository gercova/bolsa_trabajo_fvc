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
        // 1. Campos adicionales en courses
        Schema::table('courses', function (Blueprint $table) {
            $table->string('certificate_type')->default('capacitacion')->after('description')->comment('Tipo de formato de certificado: capacitacion o modular');
            $table->foreignId('study_program_id')->nullable()->after('certificate_type')->constrained('study_programs')->nullOnDelete();
            $table->string('event_name')->nullable()->after('study_program_id')->comment('Nombre del evento u ocasión, ej: Semana Técnica 2026');
        });

        // 2. Campos adicionales en certificates
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('certificate_type')->default('capacitacion')->after('course_id')->comment('Tipo de formato de certificado: capacitacion o modular');
            $table->string('participation_type')->default('ASISTENTE')->after('certificate_type')->comment('Calidad de participación: ASISTENTE, PONENTE, etc.');
            $table->string('event_name')->nullable()->after('participation_type')->comment('Ocasión o evento, ej: Semana Técnica 2026');
            $table->foreignId('study_program_id')->nullable()->after('event_name')->constrained('study_programs')->nullOnDelete();
            $table->string('institution_name')->nullable()->after('study_program_id')->comment('Nombre de la institución emisora');
            $table->string('city')->nullable()->default('Uchiza')->after('institution_name')->comment('Ciudad de emisión');
        });

        // 3. Ajustes en certificate_details para cursos de capacitación (sin notas)
        Schema::table('certificate_details', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable()->change();
            $table->string('topic_name')->nullable()->after('module_id')->comment('Tema o contenido para temario de capacitación');
            $table->integer('order')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_details', function (Blueprint $table) {
            $table->dropColumn(['topic_name', 'order']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['study_program_id']);
            $table->dropColumn([
                'certificate_type',
                'participation_type',
                'event_name',
                'study_program_id',
                'institution_name',
                'city',
            ]);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['study_program_id']);
            $table->dropColumn([
                'certificate_type',
                'study_program_id',
                'event_name',
            ]);
        });
    }
};
