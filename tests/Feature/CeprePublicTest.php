<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\AdmissionRequirement;
use App\Models\Enterprise;
use App\Models\StudyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CeprePublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_access_cepre_page(): void
    {
        // 0. Seed enterprise settings for footer/layout
        Enterprise::create([
            'ruc' => '20123456789',
            'company_name' => 'Test Enterprise',
            'trade_name' => 'Test Trade',
            'legal_representative_dni' => '12345678',
            'legal_representative' => 'John Doe',
            'email' => 'test@example.com',
            'phone_number_1' => '+51 987654321',
            'whatsapp_link' => 'https://wa.me/51987654321',
            'address' => 'Av. de prueba 123',
            'city' => 'Trujillo',
        ]);

        // 1. Create study program
        $program = StudyProgram::create([
            'name' => 'Computación e Informática',
            'slug' => 'computacion-e-informatica',
            'description' => 'Descripción',
            'details' => 'Detalles',
            'is_active' => true,
        ]);

        // 2. Create active CEPRE exam
        $activeCepre = Admission::create([
            'activity' => 'Ciclo de Preparación Cepre',
            'period' => '2026-I-CEPRE',
            'total_vacancies' => 50,
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 100.00,
            'type' => 'ordinario',
            'process' => 'cepre',
            'is_active' => true,
        ]);

        $activeCepre->admissionDetail()->create([
            'program_id' => $program->id,
            'vacancies' => 20,
        ]);

        // 3. Create inactive CEPRE exam
        $inactiveCepre = Admission::create([
            'activity' => 'Ciclo Cepre Inactivo',
            'period' => '2025-II-INACT',
            'total_vacancies' => 30,
            'exam_date' => '2025-08-15',
            'inscription_start_date' => '2025-06-15',
            'inscription_end_date' => '2025-08-10',
            'price' => 90.00,
            'type' => 'ordinario',
            'process' => 'cepre',
            'is_active' => false,
        ]);

        // 4. Create active Admission exam (process = admisión)
        $activeAdmission = Admission::create([
            'activity' => 'Examen de Admisión',
            'period' => '2026-I-ADMIS',
            'total_vacancies' => 150,
            'exam_date' => '2026-04-15',
            'inscription_start_date' => '2026-02-15',
            'inscription_end_date' => '2026-04-10',
            'price' => 120.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        // 5. Create active requirement
        $requirement = AdmissionRequirement::create([
            'requirement' => 'Copia de DNI legalizada',
            'is_active' => true,
        ]);

        // 6. Create inactive requirement
        $inactiveRequirement = AdmissionRequirement::create([
            'requirement' => 'Requisito inactivo de prueba',
            'is_active' => false,
        ]);

        // 7. Request public CEPRE page
        $response = $this->get('/admision-y-matricula/cepre-fvc');

        // 8. Assertions
        $response->assertStatus(200);
        $response->assertSee('2026-I-CEPRE');
        $response->assertSee('Ciclo de Preparación Cepre');
        $response->assertSee('Copia de DNI legalizada');

        // Should NOT see inactive or non-cepre exams/requirements
        $response->assertDontSee('2025-II-INACT');
        $response->assertDontSee('2026-I-ADMIS');
        $response->assertDontSee('Requisito inactivo de prueba');
    }

    public function test_public_can_access_admission_exam_page(): void
    {
        // 0. Seed enterprise settings for footer/layout
        Enterprise::create([
            'ruc' => '20123456789',
            'company_name' => 'Test Enterprise',
            'trade_name' => 'Test Trade',
            'legal_representative_dni' => '12345678',
            'legal_representative' => 'John Doe',
            'email' => 'test@example.com',
            'phone_number_1' => '+51 987654321',
            'whatsapp_link' => 'https://wa.me/51987654321',
            'address' => 'Av. de prueba 123',
            'city' => 'Trujillo',
        ]);

        // 1. Create study program
        $program = StudyProgram::create([
            'name' => 'Contabilidad',
            'slug' => 'contabilidad',
            'description' => 'Descripción',
            'details' => 'Detalles',
            'is_active' => true,
        ]);

        // 2. Create active Admission exam
        $activeAdmission = Admission::create([
            'activity' => 'Examen Ordinario',
            'period' => '2026-I-ADMIS',
            'total_vacancies' => 150,
            'exam_date' => '2026-04-15',
            'inscription_start_date' => '2026-02-15',
            'inscription_end_date' => '2026-04-10',
            'price' => 120.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        $activeAdmission->admissionDetail()->create([
            'program_id' => $program->id,
            'vacancies' => 30,
        ]);

        // 3. Create active CEPRE exam
        $activeCepre = Admission::create([
            'activity' => 'Ciclo Cepre',
            'period' => '2026-I-CEPRE',
            'total_vacancies' => 50,
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 100.00,
            'type' => 'ordinario',
            'process' => 'cepre',
            'is_active' => true,
        ]);

        // 4. Create active requirement
        $requirement = AdmissionRequirement::create([
            'requirement' => 'Certificado de estudios secundarios',
            'is_active' => true,
        ]);

        // 5. Request public Admission Exam page
        $response = $this->get('/admision-y-matricula/examen-de-admision');

        // 6. Assertions
        $response->assertStatus(200);
        $response->assertSee('2026-I-ADMIS');
        $response->assertSee('Examen Ordinario');
        $response->assertSee('Certificado de estudios secundarios');

        // Should NOT see CEPRE exams
        $response->assertDontSee('2026-I-CEPRE');
        $response->assertDontSee('Ciclo Cepre');
    }

    public function test_public_can_access_enrollments_page(): void
    {
        // 0. Seed enterprise settings for footer/layout
        Enterprise::create([
            'ruc' => '20123456789',
            'company_name' => 'Test Enterprise',
            'trade_name' => 'Test Trade',
            'legal_representative_dni' => '12345678',
            'legal_representative' => 'John Doe',
            'email' => 'test@example.com',
            'phone_number_1' => '+51 987654321',
            'whatsapp_link' => 'https://wa.me/51987654321',
            'address' => 'Av. de prueba 123',
            'city' => 'Trujillo',
        ]);

        // 1. Create area
        $area = \App\Models\Area::create([
            'name' => 'Secretaría Académica',
        ]);

        // 2. Create active Enrollment schedule (process = matrícula)
        $enrollment = Admission::create([
            'activity' => 'Matrícula Regular Alumnos Nuevos',
            'period' => '2026-I',
            'total_vacancies' => 100,
            'exam_date' => '2026-03-01',
            'inscription_start_date' => '2026-02-01',
            'inscription_end_date' => '2026-02-28',
            'price' => 80.00,
            'type' => 'ordinario',
            'process' => 'matrícula',
            'area_id' => $area->id,
            'is_active' => true,
        ]);

        // 3. Create active CEPRE exam (process = cepre)
        $activeCepre = Admission::create([
            'activity' => 'Ciclo Cepre',
            'period' => '2026-I-CEPRE',
            'total_vacancies' => 50,
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 100.00,
            'type' => 'ordinario',
            'process' => 'cepre',
            'is_active' => true,
        ]);

        // 4. Create active requirement
        $requirement = AdmissionRequirement::create([
            'requirement' => 'Ficha de matrícula debidamente llenada',
            'is_active' => true,
        ]);

        // 5. Request public Enrollments page
        $response = $this->get('/admision-y-matricula/matriculas');

        // 6. Assertions
        $response->assertStatus(200);
        $response->assertSee('Matrícula Regular Alumnos Nuevos');
        $response->assertSee('Secretaría Académica');
        $response->assertSee('Ficha de matrícula debidamente llenada');

        // Should NOT see CEPRE exams
        $response->assertDontSee('2026-I-CEPRE');
        $response->assertDontSee('Ciclo Cepre');
    }
}
