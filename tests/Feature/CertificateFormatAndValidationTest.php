<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\User;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CertificateFormatAndValidationTest extends TestCase
{
    protected Certificate $certificate;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('document_type')->insertOrIgnore([
            'id' => 1,
            'name' => 'Documento Nacional de Identidad',
            'abreviation' => 'DNI',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $program = StudyProgram::where('name', 'Administración de Redes y Comunicaciones')->first()
            ?? StudyProgram::create([
                'name' => 'Administración de Redes y Comunicaciones',
                'slug' => 'administracion-de-redes-y-comunicaciones',
                'is_active' => true,
            ]);

        $course = Course::where('name', 'SEMANA TÉCNICA: ADMINISTRACIÓN DE REDES Y COMUNICACIONES')->first()
            ?? Course::create([
                'name' => 'SEMANA TÉCNICA: ADMINISTRACIÓN DE REDES Y COMUNICACIONES',
                'certificate_type' => 'capacitacion',
                'study_program_id' => $program->id,
                'event_name' => 'Semana Técnica 2026',
                'is_active' => true,
            ]);

        $topics = [
            'Análisis y Visualización de Datos con Power BI.',
            'IoT para la Transformación Digital de las Instituciones Públicas.',
            'MikroTik, Administración y Seguridad de Redes Institucionales.',
            'Sistemas ERP para la Gestión Empresarial.',
            'El Rol del Ingeniero en la Era de la IA: Requisitos, Patrones de Software y Producción Real.',
            'El Impacto de la IA Agéntica en tu Futuro Profesional.',
        ];

        foreach ($topics as $topic) {
            $course->modules()->firstOrCreate(
                ['name' => $topic, 'course_id' => $course->id],
                ['credits' => null, 'is_active' => true]
            );
        }

        $student = User::firstOrCreate(
            ['dni' => '71234567'],
            [
                'document_type_id' => 1,
                'names' => 'JOSE DANIEL CHAVEZ HERRERA',
                'email' => 'jchavez@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Postulante',
                'is_active' => true,
            ]
        );

        $this->adminUser = User::firstOrCreate(
            ['dni' => '12345678'],
            [
                'document_type_id' => 1,
                'names' => 'Administrador del Sistema',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Admin',
                'is_active' => true,
            ]
        );

        $this->certificate = Certificate::firstOrCreate(
            ['certificate_code' => '005-2026-ST-FVC'],
            [
                'user_id' => $student->id,
                'course_id' => $course->id,
                'certificate_type' => 'capacitacion',
                'participation_type' => 'ASISTENTE',
                'event_name' => 'Semana Técnica 2026',
                'study_program_id' => $program->id,
                'institution_name' => 'Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero” de Uchiza',
                'city' => 'Uchiza',
                'description' => 'Tecnologías de Información y Comunicación',
                'start_date' => '2026-09-21',
                'end_date' => '2026-09-24',
                'duration' => '90 horas pedagógicas',
                'modality' => 'Presencial',
                'issue_date' => '2026-09-30',
                'is_active' => true,
            ]
        );
    }

    public function test_qr_code_service_generates_valid_vector_svg_and_data_uri(): void
    {
        $url = 'https://example.com/validar-certificado/005-2026-ST-FVC';
        $svg = QrCodeService::svg($url, 120);

        $this->assertNotEmpty($svg);
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);
        $this->assertStringContainsString('width="120"', $svg);

        $dataUri = QrCodeService::dataUri($url, 120);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $dataUri);
    }

    public function test_certificate_model_resolves_topics_and_training_attributes(): void
    {
        $this->assertTrue($this->certificate->isTraining());
        $this->assertNotEmpty($this->certificate->validation_url);
        $this->assertStringContainsString('005-2026-ST-FVC', $this->certificate->validation_url);
        $this->assertStringContainsString('del 21 al 24 de setiembre', $this->certificate->formatted_date_range);
        $this->assertStringContainsString('Uchiza, 30 de setiembre', $this->certificate->formatted_issue_date);

        $topics = $this->certificate->topics_list;
        $this->assertIsArray($topics);
        $this->assertNotEmpty($topics);
    }

    public function test_public_user_can_access_validation_page_with_qr_code(): void
    {
        $response = $this->get('/validar-certificado/'.$this->certificate->certificate_code);

        $response->assertStatus(200);
        $response->assertSee('JOSE DANIEL CHAVEZ HERRERA');
        $response->assertSee('005-2026-ST-FVC');
        $response->assertSee('Temario y Contenidos Desarrollados');
        $response->assertSee('Ver Certificado Oficial (Formato Original)');
    }

    public function test_public_user_can_view_official_printable_certificate_document(): void
    {
        $response = $this->get('/validar-certificado/'.$this->certificate->certificate_code.'/imprimir');

        $response->assertStatus(200);
        $response->assertSee('CERTIFICADO');
        $response->assertSee('FRANCISCO VIGO CABALLERO');
        $response->assertSee('JOSE DANIEL CHAVEZ HERRERA');
        $response->assertSee('TEMARIO');
        $response->assertSee('005-2026-ST-FVC');
    }

    public function test_admin_user_can_view_printable_certificate_route(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin-certificados/'.$this->certificate->id.'/imprimir');

        $response->assertStatus(200);
        $response->assertSee('CERTIFICADO');
        $response->assertSee('JOSE DANIEL CHAVEZ HERRERA');
    }

    public function test_printable_certificate_embeds_logo_as_base64_data_uri(): void
    {
        $response = $this->get('/validar-certificado/'.$this->certificate->certificate_code.'/imprimir');

        $response->assertStatus(200);
        $response->assertSee('data:image/', false);
    }

    public function test_storage_fallback_route_serves_public_disk_files(): void
    {
        $response = $this->get('/storage/enterprise/favicons/logo-iestpfvc.png');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_storage_fallback_route_prevents_directory_traversal(): void
    {
        $response = $this->get('/storage/../../.env');

        $response->assertStatus(404);
    }

    public function test_basic_english_certificate_identifies_properly_and_resolves_modules_data(): void
    {
        $englishCourse = Course::firstOrCreate(
            ['name' => 'INGLÉS A NIVEL BÁSICO'],
            [
                'certificate_type' => 'ingles',
                'event_name' => 'Programa de Idiomas 2026',
                'is_active' => true,
            ]
        );

        $englishCert = Certificate::updateOrCreate(
            ['certificate_code' => '009-2026-ING-FVC'],
            [
                'user_id' => $this->certificate->user_id,
                'course_id' => $englishCourse->id,
                'certificate_type' => 'ingles',
                'city' => 'Uchiza',
                'start_date' => '2026-05-14',
                'end_date' => '2026-07-16',
                'duration' => '128 horas pedagógicas',
                'issue_date' => '2025-12-29',
                'is_active' => true,
            ]
        );

        $this->assertTrue($englishCert->isBasicEnglish());
        $this->assertFalse($englishCert->isTraining());

        $modulesData = $englishCert->english_modules_data;
        $this->assertCount(2, $modulesData);
        $this->assertMatchesRegularExpression('/M[OÓ]DULO:\s*I\b/iu', $modulesData[0]['name']);
        $this->assertEquals(4, $modulesData[0]['credits']);
        $this->assertEquals(14, $modulesData[0]['score_num']);
        $this->assertEquals('Catorce', $modulesData[0]['score_text']);
        $this->assertContains('Greatings and farewells', $modulesData[0]['contents']);

        $this->assertMatchesRegularExpression('/M[OÓ]DULO:\s*II\b/iu', $modulesData[1]['name']);
        $this->assertEquals(3, $modulesData[1]['credits']);
        $this->assertEquals(14, $modulesData[1]['score_num']);
        $this->assertEquals('Catorce', $modulesData[1]['score_text']);
        $this->assertContains('Demostrative Pronuons A.N.I form', $modulesData[1]['contents']);
    }

    public function test_basic_english_printable_certificate_renders_both_anverso_and_reverso(): void
    {
        $englishCourse = Course::firstOrCreate(
            ['name' => 'INGLÉS A NIVEL BÁSICO'],
            ['certificate_type' => 'ingles', 'is_active' => true]
        );

        $englishCert = Certificate::updateOrCreate(
            ['certificate_code' => '010-2026-ING-FVC'],
            [
                'user_id' => $this->certificate->user_id,
                'course_id' => $englishCourse->id,
                'certificate_type' => 'ingles',
                'city' => 'Uchiza',
                'start_date' => '2026-05-14',
                'end_date' => '2026-07-16',
                'duration' => '128 horas pedagógicas',
                'issue_date' => '2025-12-29',
                'is_active' => true,
            ]
        );

        $response = $this->get('/validar-certificado/'.$englishCert->certificate_code.'/imprimir');

        $response->assertStatus(200);
        $response->assertSee('CERTIFICADO');
        $response->assertSee('OTORGADO A:');
        $response->assertSee('INGLÉS A NIVEL BÁSICO');
        $response->assertSee('sheet-anverso');
        $response->assertSee('sheet-reverso');
        $response->assertSee('MODULOS Y CONTENIDOS');
        $response->assertSee('Greatings and farewells');
        $response->assertSee('Ambas Caras');
        $response->assertSee('Frente');
        $response->assertSee('Reverso');
        $response->assertSee('english-front-watermark');
        $response->assertSee('english-table-watermark');
        $response->assertDontSee('english-laurel-watermark');
    }

    public function test_basic_english_public_validation_shows_academic_record_table(): void
    {
        $englishCourse = Course::firstOrCreate(
            ['name' => 'INGLÉS A NIVEL BÁSICO'],
            ['certificate_type' => 'ingles', 'is_active' => true]
        );

        $englishCert = Certificate::updateOrCreate(
            ['certificate_code' => '011-2026-ING-FVC'],
            [
                'user_id' => $this->certificate->user_id,
                'course_id' => $englishCourse->id,
                'certificate_type' => 'ingles',
                'city' => 'Uchiza',
                'start_date' => '2026-05-14',
                'end_date' => '2026-07-16',
                'duration' => '128 horas pedagógicas',
                'issue_date' => '2025-12-29',
                'is_active' => true,
            ]
        );

        $response = $this->get('/validar-certificado/'.$englishCert->certificate_code);

        $response->assertStatus(200);
        $response->assertSee('Registro Académico y Calificaciones — Inglés a Nivel Básico');
        $response->assertSee('Greatings and farewells');
        $response->assertSee('Demostrative Pronuons A.N.I form');
        $response->assertSee('Catorce');
        $response->assertSee('Ver Certificado Oficial (Formato Original)');
    }
}
