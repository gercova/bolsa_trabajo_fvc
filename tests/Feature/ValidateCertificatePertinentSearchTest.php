<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\DocumentType;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ValidateCertificatePertinentSearchTest extends TestCase
{
    protected DocumentType $docType;

    protected User $student;

    protected Course $modularCourse;

    protected Course $englishCourse;

    protected Certificate $modularCert;

    protected Certificate $englishCert;

    protected function setUp(): void
    {
        parent::setUp();

        $this->docType = DocumentType::firstOrCreate(
            ['abreviation' => 'DNI'],
            ['name' => 'Documento Nacional de Identidad', 'is_active' => true]
        );

        $this->student = User::firstOrCreate(
            ['dni' => '45576851'],
            [
                'document_type_id' => $this->docType->id,
                'names' => 'SALAZAR SINARAHUA, PILAR',
                'email' => 'pilar.salazar@example.com',
                'password' => Hash::make('secret123'),
                'role' => 'Postulante',
                'is_active' => true,
            ]
        );

        $program = StudyProgram::firstOrCreate(
            ['name' => 'Enfermería Técnica'],
            ['slug' => 'enfermeria-tecnica', 'is_active' => true]
        );

        $this->modularCourse = Course::firstOrCreate(
            ['name' => 'CUIDADOS BÁSICOS EN SALUD COMUNITARIA'],
            [
                'certificate_type' => 'modular',
                'study_program_id' => $program->id,
                'is_active' => true,
            ]
        );

        $this->englishCourse = Course::firstOrCreate(
            ['name' => 'INGLÉS A NIVEL BÁSICO'],
            [
                'certificate_type' => 'english',
                'study_program_id' => $program->id,
                'is_active' => true,
            ]
        );

        $this->modularCert = Certificate::updateOrCreate(
            ['certificate_code' => 'CERT-45576851-1'],
            [
                'code' => 'CERT-45576851-1',
                'user_id' => $this->student->id,
                'course_id' => $this->modularCourse->id,
                'certificate_type' => 'modular',
                'duration' => '128 Horas',
                'start_date' => '2026-05-14',
                'end_date' => '2026-06-16',
                'issue_date' => '2026-08-23',
                'city' => 'Uchiza',
                'is_active' => true,
            ]
        );

        $this->englishCert = Certificate::updateOrCreate(
            ['certificate_code' => 'CERT-45576851-2'],
            [
                'code' => 'CERT-45576851-2',
                'user_id' => $this->student->id,
                'course_id' => $this->englishCourse->id,
                'certificate_type' => 'english',
                'duration' => '128 horas pedagógicas',
                'start_date' => '2026-05-14',
                'end_date' => '2026-06-16',
                'issue_date' => '2026-08-23',
                'city' => 'Uchiza',
                'is_active' => true,
            ]
        );
    }

    public function test_non_english_certificate_has_empty_english_modules_data(): void
    {
        $this->assertFalse($this->modularCert->isBasicEnglish());
        $this->assertEmpty($this->modularCert->english_modules_data);
    }

    public function test_genuine_english_certificate_has_english_modules_data(): void
    {
        $this->assertTrue($this->englishCert->isBasicEnglish());
        $this->assertNotEmpty($this->englishCert->english_modules_data);
        $this->assertCount(2, $this->englishCert->english_modules_data);
    }

    public function test_search_by_code_returns_only_pertinent_certificate(): void
    {
        $response = $this->get('/validar-certificado?code=CERT-45576851-1');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Services/ValidateCertificate')
            ->where('certificate.certificate_code', 'CERT-45576851-1')
            ->where('certificate.course.name', 'CUIDADOS BÁSICOS EN SALUD COMUNITARIA')
            ->where('certificate.english_modules_data', [])
            ->has('certificates', 1)
            ->where('certificates.0.certificate_code', 'CERT-45576851-1')
        );
    }

    public function test_search_by_dni_returns_student_certificates_with_correct_pertinent_data(): void
    {
        $response = $this->get('/validar-certificado?code=45576851');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Services/ValidateCertificate')
            ->where('searchedUser.dni', '45576851')
            ->where('searchedUser.names', 'SALAZAR SINARAHUA, PILAR')
            ->has('certificates', 2)
            ->where('certificates', function ($certs) {
                $certCodes = collect($certs)->pluck('certificate_code')->all();

                return in_array('CERT-45576851-1', $certCodes) && in_array('CERT-45576851-2', $certCodes);
            })
        );
    }
}
