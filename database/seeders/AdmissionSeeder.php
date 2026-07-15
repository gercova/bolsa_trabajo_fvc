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
        // 1. ADMISIÓN ORDINARIA (examen con 27 vacantes por programa)
        // ------------------------------------------------------------
        $ordinario = Admission::create([
            'activity'               => 'Examen de admisión ordinario',
            'period'                 => '2026-I',
            'total_vacancies'        => $programs->count() * 27,
            'exam_date'              => '2026-04-05',
            'inscription_start_date' => '2026-01-12',
            'inscription_end_date'   => '2026-04-03',
            'url_pdf'                => null,
            'price'                  => 0,
            'type'                   => 'ordinario',
            'process'                => 'admisión',
            'area_id'                => null,
            'is_active'              => true,
        ]);

        foreach ($programs as $program) {
            AdmissionDetail::create([
                'admission_id' => $ordinario->id,
                'program_id'   => $program->id,
                'vacancies'    => 27,
            ]);
        }

        // ------------------------------------------------------------
        // 2. ADMISIÓN EXONERADOS (sin vacantes específicas en detalles)
        // ------------------------------------------------------------
        Admission::create([
            'activity'               => 'Inscripción y evaluación de exonerados',
            'period'                 => '2026-I',
            'total_vacancies'        => 0,
            'exam_date'              => null,
            'inscription_start_date' => '2026-01-12',
            'inscription_end_date'   => '2026-03-20',
            'url_pdf'                => null,
            'price'                  => 0,
            'type'                   => 'extraordinario',
            'process'                => 'admisión',
            'area_id'                => null,
            'is_active'              => true,
        ]);

        // ------------------------------------------------------------
        // 3. PROCESOS DE MATRÍCULA (sin detalles de vacantes)
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
                'price'                  => 0,
                'type'                   => 'ordinario',
                'process'                => 'matrícula',
                'area_id'                => null,
                'is_active'              => true,
            ]);
        }

        // ------------------------------------------------------------
        // 4. PROCESOS ESPECIALES CON 1 VACANTE POR PROGRAMA
        // ------------------------------------------------------------
        $especiales = [
            'CEPRE'                      => 'cepre',       // process = 'cepre'
            'Reparaciones colectivas'    => 'admisión',
            'Discapacidad'               => 'admisión',
            'Título o grado académico'   => 'admisión',
            'Deportista calificado'      => 'admisión',
        ];

        foreach ($especiales as $nombre => $process) {
            // Para CEPRE usamos type 'ordinario', para los demás 'extraordinario'
            $type = ($process === 'cepre') ? 'ordinario' : 'extraordinario';

            $admission = Admission::create([
                'activity'               => 'Admisión por ' . $nombre,
                'period'                 => '2026-I',
                'total_vacancies'        => $programs->count() * 1,
                'exam_date'              => null,
                'inscription_start_date' => null,
                'inscription_end_date'   => null,
                'url_pdf'                => null,
                'price'                  => 0,
                'type'                   => $type,
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