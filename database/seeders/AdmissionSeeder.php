<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\AdmissionDetail;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class AdmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener todos los programas de estudio existentes
        $programs = StudyProgram::all();

        if ($programs->isEmpty()) {
            $this->command->warn('No hay programas de estudio. Ejecuta primero StudyProgramSeeder.');
            return;
        }

        // ------------------------------------------------------------
        // 1. ADMISIÓN HISTÓRICA / RESULTADOS ANTERIORES (2026-I)
        // ------------------------------------------------------------
        $historico = Admission::create([
            'activity'               => 'Examen de Admisión Ordinario',
            'period'                 => '2026-I',
            'total_vacancies'        => $programs->count() * 25,
            'exam_date'              => '2026-04-05',
            'inscription_start_date' => '2026-01-12',
            'inscription_end_date'   => '2026-04-03',
            'url_pdf'                => null,
            'results_url_pdf'        => 'admissions/results/resultados_admision_2026_1.pdf',
            'price'                  => 150.00,
            'tuition_fee'            => 0,
            'monthly_fee'            => 0,
            'duration'               => null,
            'indications'            => 'Cuadro de méritos oficial del proceso de admisión general 2026-I.',
            'type'                   => 'ordinario',
            'process'                => 'admisión',
            'area_id'                => null,
            'is_active'              => false,
        ]);

        foreach ($programs as $program) {
            AdmissionDetail::create([
                'admission_id' => $historico->id,
                'program_id'   => $program->id,
                'vacancies'    => 25,
            ]);
        }

        // ------------------------------------------------------------
        // 2. ADMISIÓN EXTRAORDINARIA PROYECTADA (2027-I)
        // ------------------------------------------------------------
        $extraordinario = Admission::create([
            'activity'               => 'Admisión Extraordinaria (Primeros Puestos, Deportistas y Casos Especiales)',
            'period'                 => '2027-I',
            'total_vacancies'        => $programs->count() * 2,
            'exam_date'              => '2027-03-21',
            'inscription_start_date' => '2027-01-15',
            'inscription_end_date'   => '2027-03-15',
            'url_pdf'                => null,
            'results_url_pdf'        => null,
            'price'                  => 120.00,
            'tuition_fee'            => 0,
            'monthly_fee'            => 0,
            'duration'               => null,
            'indications'            => "1. Modalidad dirigida a los primeros puestos de secundaria, deportistas calificados, personas con discapacidad y beneficiarios de programas sociales.\n2. Presentar constancia de acreditación o mérito original debidamente visada.",
            'type'                   => 'extraordinario',
            'process'                => 'admisión',
            'area_id'                => null,
            'is_active'              => true,
        ]);

        foreach ($programs as $program) {
            AdmissionDetail::create([
                'admission_id' => $extraordinario->id,
                'program_id'   => $program->id,
                'vacancies'    => 2,
            ]);
        }

        // ------------------------------------------------------------
        // 3. ADMISIÓN ORDINARIA PROYECTADA (2027-I)
        // ------------------------------------------------------------
        $ordinario = Admission::create([
            'activity'               => 'Examen de Admisión Ordinario',
            'period'                 => '2027-I',
            'total_vacancies'        => $programs->count() * 30,
            'exam_date'              => '2027-04-04',
            'inscription_start_date' => '2027-01-20',
            'inscription_end_date'   => '2027-03-31',
            'url_pdf'                => null,
            'results_url_pdf'        => null,
            'price'                  => 180.00,
            'tuition_fee'            => 0,
            'monthly_fee'            => 0,
            'duration'               => null,
            'indications'            => "1. Para rendir la prueba general, es obligatorio portar DNI original, carnet de postulante, lápiz 2B, borrador y tajador.\n2. El ingreso al campus institucional es de 07:00 a.m. a 07:45 a.m. No se permitirá el ingreso con celulares ni mochilas.",
            'type'                   => 'ordinario',
            'process'                => 'admisión',
            'area_id'                => null,
            'is_active'              => true,
        ]);

        foreach ($programs as $program) {
            AdmissionDetail::create([
                'admission_id' => $ordinario->id,
                'program_id'   => $program->id,
                'vacancies'    => 30,
            ]);
        }

        // ------------------------------------------------------------
        // 4. PROCESOS DE MATRÍCULA (sin detalles de vacantes)
        // ------------------------------------------------------------
        $matriculaItems = [
            [
                'activity' => 'Matrícula regular o ratificación de matrícula (III y V)',
                'start'    => '2026-02-09',
                'end'      => '2026-03-27',
            ],
            [
                'activity' => 'Matrícula exonerados (III y V)',
                'start'    => '2026-03-02',
                'end'      => '2026-04-03',
            ],
            [
                'activity' => 'Matrícula exonerados Admisión 2025',
                'start'    => '2026-03-24',
                'end'      => '2026-04-10',
            ],
            [
                'activity' => 'Matrícula de ingresantes',
                'start'    => '2026-04-06',
                'end'      => '2026-04-14',
            ],
            [
                'activity' => 'Matrícula extemporánea ingresantes (+30%) (I)',
                'start'    => '2026-04-15',
                'end'      => '2026-04-17',
            ],
            [
                'activity' => 'Matrícula extemporánea (+30%) (III y V)',
                'start'    => '2026-03-30',
                'end'      => '2026-04-10',
            ],
        ];

        foreach ($matriculaItems as $item) {
            Admission::create([
                'activity'               => $item['activity'],
                'period'                 => '2026-I',
                'total_vacancies'        => 0,
                'exam_date'              => null,
                'inscription_start_date' => $item['start'],
                'inscription_end_date'   => $item['end'],
                'url_pdf'                => null,
                'results_url_pdf'        => null,
                'price'                  => 0,
                'tuition_fee'            => 0,
                'monthly_fee'            => 0,
                'duration'               => null,
                'indications'            => null,
                'type'                   => 'ordinario',
                'process'                => 'matrícula',
                'area_id'                => null,
                'is_active'              => true,
            ]);
        }

        // ------------------------------------------------------------
        // 5. CICLO PREPARATORIO CEPRE (Proyectado 2027-I)
        // ------------------------------------------------------------
        $cepre = Admission::create([
            'activity'               => 'Ciclo Preparatorio CEPRE-FVC',
            'period'                 => '2027-I',
            'total_vacancies'        => $programs->count() * 5,
            'exam_date'              => '2027-03-28',
            'inscription_start_date' => '2026-12-01',
            'inscription_end_date'   => '2027-01-15',
            'url_pdf'                => null,
            'results_url_pdf'        => null,
            'price'                  => 150.00,
            'tuition_fee'            => 80.00,
            'monthly_fee'            => 120.00,
            'duration'               => '3 Meses (12 Semanas)',
            'indications'            => "1. Asistir puntualmente a las sesiones académicas portando carnet de estudiante CEPRE.\n2. Para la evaluación final, es obligatorio portar DNI original en físico, lápiz 2B, borrador y tajador.\n3. El ingreso al local institucional el día de la prueba final se cerrará estrictamente a las 07:45 a.m.",
            'type'                   => 'ordinario',
            'process'                => 'cepre',
            'area_id'                => null,
            'is_active'              => true,
        ]);

        foreach ($programs as $program) {
            AdmissionDetail::create([
                'admission_id' => $cepre->id,
                'program_id'   => $program->id,
                'vacancies'    => 5,
            ]);
        }

        // ------------------------------------------------------------
        // 6. PROCESOS EXTRAORDINARIOS CON 1 VACANTE POR PROGRAMA (2027-I)
        // ------------------------------------------------------------
        $especiales = [
            'Reparaciones colectivas'    => 'admisión',
            'Discapacidad'               => 'admisión',
            'Título o grado académico'   => 'admisión',
            'Deportista calificado'      => 'admisión',
        ];

        foreach ($especiales as $nombre => $process) {
            $admission = Admission::create([
                'activity'               => 'Admisión por ' . $nombre,
                'period'                 => '2027-I',
                'total_vacancies'        => $programs->count() * 1,
                'exam_date'              => null,
                'inscription_start_date' => null,
                'inscription_end_date'   => null,
                'url_pdf'                => null,
                'results_url_pdf'        => null,
                'price'                  => 0,
                'tuition_fee'            => 0,
                'monthly_fee'            => 0,
                'duration'               => null,
                'indications'            => null,
                'type'                   => 'extraordinario',
                'process'                => $process,
                'area_id'                => null,
                'is_active'              => true,
            ]);

            foreach ($programs as $program) {
                AdmissionDetail::create([
                    'admission_id' => $admission->id,
                    'program_id'   => $program->id,
                    'vacancies'    => 1,
                ]);
            }
        }
    }
}