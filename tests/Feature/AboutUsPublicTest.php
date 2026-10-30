<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\StudentCouncil;
use App\Models\StudyProgram;
use App\Models\User;
use App\Models\UserRoleDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AboutUsPublicTest extends TestCase
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
            'phrase' => 'Formando líderes',
            'description' => 'Descripción del instituto de prueba.',
            'mission' => 'Misión institucional de prueba.',
            'vision' => 'Visión institucional de prueba.',
            'principles' => 'Calidad, Transparencia',
            'values' => 'Responsabilidad, Orden',
        ]);
    }

    private function getDocumentTypeId(): int
    {
        $id = DB::table('document_type')->value('id');
        if (! $id) {
            $id = DB::table('document_type')->insertGetId([
                'name' => 'DNI',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $id;
    }

    public function test_public_user_can_access_who_we_are_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/nosotros/quienes-somos');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('AboutUs/WhoWeAre')
            ->has('enterprise')
            ->where('enterprise.company_name', 'Test Enterprise')
            ->where('enterprise.mission', 'Misión institucional de prueba.')
        );
    }

    public function test_public_user_can_access_organization_chart_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/nosotros/organigrama-institucional');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('AboutUs/OrganizationChart')
            ->has('enterprise')
        );
    }

    public function test_public_user_can_access_hierarchical_staff_page(): void
    {
        $this->createEnterprise();
        $docTypeId = $this->getDocumentTypeId();

        $director = User::factory()->create([
            'names' => 'Carlos Director',
            'job_position' => 'Director General',
            'role' => 'Admin',
            'document_type_id' => $docTypeId,
            'is_active' => true,
        ]);

        $boss = User::factory()->create([
            'names' => 'María Jefa',
            'job_position' => 'Jefe de Unidad Académica',
            'role' => 'Administrativo',
            'document_type_id' => $docTypeId,
            'is_active' => true,
        ]);

        $response = $this->get('/nosotros/plana-jerarquica');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('AboutUs/HierarchicalStaff')
            ->has('enterprise')
            ->has('director')
            ->has('managementStaff')
            ->has('coordinators')
            ->has('allHierarchical')
            ->where('director.names', 'Carlos Director')
        );
    }

    public function test_public_user_can_access_teachers_staff_page(): void
    {
        $this->createEnterprise();
        $docTypeId = $this->getDocumentTypeId();

        $program = StudyProgram::create([
            'name' => 'Computación e Informática',
            'slug' => 'computacion-e-informatica',
            'description' => 'Programa de tecnología',
            'details' => '3 años',
            'is_active' => true,
        ]);

        $teacherUser = User::factory()->create([
            'names' => 'Docente Especialista',
            'role' => 'Docente',
            'document_type_id' => $docTypeId,
            'is_active' => true,
        ]);

        UserRoleDetail::create([
            'user_id' => $teacherUser->id,
            'program_id' => $program->id,
            'is_coordinator' => true,
            'is_active' => true,
        ]);

        $generalTeacher = User::factory()->create([
            'names' => 'Docente General',
            'role' => 'Docente',
            'document_type_id' => $docTypeId,
            'is_active' => true,
        ]);

        $response = $this->get('/nosotros/plana-de-docentes');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('AboutUs/TeachersStaff')
            ->has('enterprise')
            ->has('teacherDetails')
            ->has('programs')
            ->has('transversalTeachers')
            ->has('unassignedTeachers')
        );
    }

    public function test_public_user_can_access_administrative_staff_page(): void
    {
        $this->createEnterprise();
        $docTypeId = $this->getDocumentTypeId();

        User::factory()->create([
            'names' => 'Secretaria Académica',
            'job_position' => 'Secretaria General',
            'role' => 'Administrativo',
            'document_type_id' => $docTypeId,
            'is_active' => true,
        ]);

        $response = $this->get('/nosotros/plana-administrativa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('AboutUs/AdministrativeStaff')
            ->has('enterprise')
            ->has('staffs', 1)
            ->where('staffs.0.names', 'Secretaria Académica')
        );
    }

    public function test_public_user_can_access_student_council_page(): void
    {
        $this->createEnterprise();
        $docTypeId = $this->getDocumentTypeId();

        $program = StudyProgram::create([
            'name' => 'Contabilidad',
            'slug' => 'contabilidad',
            'description' => 'Programa de finanzas',
            'details' => '3 años',
            'is_active' => true,
        ]);

        $student = User::factory()->create([
            'names' => 'Líder Estudiantil',
            'role' => 'estudiante',
            'document_type_id' => $docTypeId,
            'is_active' => true,
        ]);

        StudentCouncil::create([
            'user_id' => $student->id,
            'study_program_id' => $program->id,
            'name' => 'Líder Estudiantil',
            'position' => 'Presidente del Consejo de Estudiantes',
            'academic_period' => '2026-2027',
            'is_active' => true,
        ]);

        $response = $this->get('/nosotros/consejo-de-estudiantes');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('AboutUs/StudentCouncil')
            ->has('enterprise')
            ->has('periods')
            ->has('selectedPeriod')
            ->has('members', 1)
            ->has('board', 1)
            ->has('secretaries', 0)
            ->where('board.0.name', 'Líder Estudiantil')
        );
    }

    public function test_public_user_can_access_locales_page(): void
    {
        $this->createEnterprise();

        StudyProgram::create([
            'name' => 'Enfermería Técnica',
            'slug' => 'enfermeria-tecnica',
            'description' => 'Programa de salud',
            'details' => '3 años',
            'is_active' => true,
        ]);

        $response = $this->get('/nosotros/locales');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('AboutUs/Locals')
            ->has('enterprise')
            ->has('programs', 1)
        );
    }
}
