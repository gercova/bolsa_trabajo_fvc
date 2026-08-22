<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\StudyProgram;
use App\Models\ModularCertification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyProgramPublicTest extends TestCase
{
    use RefreshDatabase;

    private function createEnterprise(): void
    {
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
    }

    public function test_public_user_can_access_study_programs_page(): void
    {
        $this->createEnterprise();

        // Create an active study program with modules
        $activeProgram = StudyProgram::create([
            'name' => 'Producción Agropecuaria',
            'slug' => 'produccion-agropecuaria',
            'description' => 'Especialidad agrícola importante',
            'details' => "Duración: 3 años\nTítulo: Profesional Técnico",
            'is_active' => true,
        ]);

        ModularCertification::create([
            'module' => 'Módulo Agropecuaria I',
            'model_type' => StudyProgram::class,
            'program_id' => $activeProgram->id,
            'is_active' => true,
        ]);

        // Create an inactive study program
        $inactiveProgram = StudyProgram::create([
            'name' => 'Enfermería Técnica Oculta',
            'slug' => 'enfermeria-tecnica-oculta',
            'description' => 'Especialidad oculta',
            'details' => "Duración: 3 años",
            'is_active' => false,
        ]);

        $response = $this->get('/programas-de-estudios');

        $response->assertOk();
        $response->assertSee('Producción Agropecuaria');
        $response->assertSee('Especialidad agrícola importante');
        $response->assertSee('Módulo Agropecuaria I');
        $response->assertDontSee('Enfermería Técnica Oculta');
    }

    public function test_study_programs_alias_redirects_correctly(): void
    {
        $response = $this->get('/study-programs');

        $response->assertRedirect('/programas-de-estudios');
    }

    public function test_public_user_can_access_study_program_detail_page(): void
    {
        $this->createEnterprise();

        // Create an active study program with modules
        $program = StudyProgram::create([
            'name' => 'Producción Agropecuaria',
            'slug' => 'produccion-agropecuaria',
            'description' => 'Especialidad agrícola importante',
            'details' => "Duración: 3 años\nTítulo: Profesional Técnico\nInversión: Gratuito",
            'is_active' => true,
        ]);

        ModularCertification::create([
            'module' => 'Módulo Agropecuaria I',
            'model_type' => StudyProgram::class,
            'program_id' => $program->id,
            'is_active' => true,
        ]);

        $response = $this->get('/programas-de-estudios/produccion-agropecuaria');

        $response->assertOk();
        $response->assertSee('Producción Agropecuaria');
        $response->assertSee('Especialidad agrícola importante');
        $response->assertSee('Módulo Agropecuaria I');
        
        // Assert SEO tags are present
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<script type="application/ld+json">', false);
    }
}
