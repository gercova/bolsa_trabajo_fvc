<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\ModularCertification;
use App\Models\StudyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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
            'details' => 'Duración: 3 años',
            'is_active' => false,
        ]);

        $response = $this->get('/programas-de-estudios');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('StudyPrograms/Index')
            ->has('programs', 1)
            ->where('programs.0.name', 'Producción Agropecuaria')
            ->where('programs.0.slug', 'produccion-agropecuaria')
            ->where('programs.0.description', 'Especialidad agrícola importante')
            ->has('programs.0.modules', 1)
            ->where('programs.0.modules.0.module', 'Módulo Agropecuaria I')
        );
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
        $response->assertInertia(fn (Assert $page) => $page
            ->component('StudyPrograms/Show')
            ->has('program')
            ->where('program.name', 'Producción Agropecuaria')
            ->where('program.slug', 'produccion-agropecuaria')
            ->where('program.description', 'Especialidad agrícola importante')
            ->has('program.modules', 1)
            ->where('program.modules.0.module', 'Módulo Agropecuaria I')
        );
    }
}
