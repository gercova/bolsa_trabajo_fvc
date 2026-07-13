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
            'period' => '2026-I-CEPRE',
            'total_vacancies' => 50,
            'observation' => 'Examen CEPRE Activo',
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
            'period' => '2025-II-INACT',
            'total_vacancies' => 30,
            'observation' => 'Examen CEPRE Inactivo',
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
            'period' => '2026-I-ADMIS',
            'total_vacancies' => 150,
            'observation' => 'Examen de Admisión Ordinario',
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
        $response->assertSee('Examen CEPRE Activo');
        $response->assertSee('Copia de DNI legalizada');

        // Should NOT see inactive or non-cepre exams/requirements
        $response->assertDontSee('2025-II-INACT');
        $response->assertDontSee('2026-I-ADMIS');
        $response->assertDontSee('Requisito inactivo de prueba');
    }
}
