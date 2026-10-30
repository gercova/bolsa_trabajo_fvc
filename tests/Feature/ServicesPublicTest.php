<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\DocumentType;
use App\Models\Enterprise;
use App\Models\StudyProgram;
use App\Models\User;
use Database\Seeders\EnterpriseSeeder;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ServicesPublicTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Enterprise::exists()) {
            (new EnterpriseSeeder)->run();
        }
    }

    public function test_job_board_page_returns_inertia_component(): void
    {
        $response = $this->get(route('bolsa-de-trabajo'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Services/JobBoard')
            ->has('enterprise')
            ->has('jobs')
            ->has('locations')
            ->has('sources')
            ->has('graduates')
            ->has('studyPrograms')
        );
    }

    public function test_institutional_links_page_returns_inertia_component(): void
    {
        $response = $this->get(route('enlaces-institucionales'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Services/InstitutionalLinks')
            ->has('enterprise')
            ->has('links')
        );
    }

    public function test_validate_certificate_page_returns_inertia_component(): void
    {
        $response = $this->get(route('validar-certificado'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Services/ValidateCertificate')
            ->has('enterprise')
            ->has('yearsSummary')
            ->has('periodsSummary')
            ->has('coursesSummary')
            ->has('studyPrograms')
            ->has('totalCertificates')
            ->has('validCertificates')
            ->has('totalStudents')
            ->has('totalCourses')
        );
    }

    public function test_verificar_certificado_redirects_to_validar_certificado(): void
    {
        $response = $this->get('/verificar-certificado/CERT-TEST-001');

        $response->assertRedirect('/validar-certificado?code=CERT-TEST-001');
    }

    public function test_print_certificate_with_inertia_header_returns_inertia_component(): void
    {
        $docType = DocumentType::first() ?? DocumentType::create([
            'name' => 'DNI',
            'abreviation' => 'DNI',
            'is_active' => true,
        ]);

        $user = User::firstOrCreate(
            ['dni' => '71239999'],
            [
                'document_type_id' => $docType->id,
                'names' => 'TEST STUDENT USER',
                'email' => 'test.student.user@example.com',
                'password' => Hash::make('secret'),
                'is_active' => true,
            ]
        );

        $program = StudyProgram::first() ?? StudyProgram::create([
            'name' => 'Administración de Redes y Comunicaciones',
            'slug' => 'administracion-de-redes-y-comunicaciones',
            'is_active' => true,
        ]);

        $course = Course::firstOrCreate(
            ['name' => 'Curso de Prueba SPA'],
            [
                'study_program_id' => $program->id,
                'is_active' => true,
            ]
        );

        $cert = Certificate::firstOrCreate(
            ['certificate_code' => 'CERT-SPA-TEST-01'],
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'study_program_id' => $program->id,
                'duration' => '90 horas',
                'modality' => 'Presencial',
                'issue_date' => now()->toDateString(),
                'is_active' => true,
            ]
        );

        $response = $this->get(route('validar-certificado.print', ['certificate_code' => $cert->certificate_code, 'spa' => 1]));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Certificates/PrintCertificate')
            ->where('certificate.certificate_code', 'CERT-SPA-TEST-01')
            ->has('enterprise')
        );
    }

    public function test_login_and_register_pages_return_inertia_components(): void
    {
        $loginRes = $this->get(route('login'));
        $loginRes->assertOk();
        $loginRes->assertInertia(fn (Assert $page) => $page->component('Auth/Login'));

        $registerRes = $this->get(route('register'));
        $registerRes->assertOk();
        $registerRes->assertInertia(fn (Assert $page) => $page->component('Auth/Register'));
    }
}
