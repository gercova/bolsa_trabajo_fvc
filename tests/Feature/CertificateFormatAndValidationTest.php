<?php

namespace Tests\Feature;

use App\Imports\CertificateImport;
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
        $this->assertStringContainsString('validar-certificado?code=005-2026-ST-FVC', $this->certificate->validation_url);
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

    public function test_qr_code_link_points_to_validar_certificado_with_query_code(): void
    {
        $expectedUrl = url('/validar-certificado?code='.$this->certificate->certificate_code);
        $this->assertEquals($expectedUrl, $this->certificate->validation_url);

        // QR Code SVG encodes the validation URL
        $svg = $this->certificate->qr_code_svg;
        $this->assertNotEmpty($svg);
        $this->assertStringContainsString('<svg', $svg);

        // Scanning QR redirects / navigates to the validation view with ?code=
        $response = $this->get('/validar-certificado?code='.$this->certificate->certificate_code);
        $response->assertStatus(200);
        $response->assertSee('JOSE DANIEL CHAVEZ HERRERA');
        $response->assertSee('005-2026-ST-FVC');
        $response->assertSee('Semana Técnica 2026');

        // Works also when certificate has custom code field
        $customCert = Certificate::create([
            'code' => 'CERT-71234567-99',
            'certificate_code' => 'CERT-71234567-99',
            'user_id' => $this->certificate->user_id,
            'course_id' => $this->certificate->course_id,
            'certificate_type' => 'capacitacion',
            'participation_type' => 'PONENTE',
            'event_name' => 'Semana Técnica 2026 Especial',
            'start_date' => '2026-09-21',
            'end_date' => '2026-09-24',
            'duration' => '90 horas',
            'modality' => 'Presencial',
            'issue_date' => '2026-09-30',
            'is_active' => true,
        ]);

        $this->assertEquals(url('/validar-certificado?code=CERT-71234567-99'), $customCert->validation_url);

        $responseCustom = $this->get('/validar-certificado?code=CERT-71234567-99');
        $responseCustom->assertStatus(200);
        $responseCustom->assertSee('CERT-71234567-99');
        $responseCustom->assertSee('Semana Técnica 2026 Especial');

        // verify.certificate alias redirects to /validar-certificado?code=
        $responseAlias = $this->get('/verify-certificate/CERT-71234567-99');
        $responseAlias->assertRedirect('/validar-certificado?code=CERT-71234567-99');

        // verificar.certificado alias redirects to /validar-certificado?code=
        $responseAliasEs = $this->get('/verificar-certificado/CERT-71234567-99');
        $responseAliasEs->assertRedirect('/validar-certificado?code=CERT-71234567-99');

        // route('verificar.certificado') generates valid URL
        $this->assertEquals(
            route('verificar.certificado', 'CERT-71234567-99'),
            url('/verificar-certificado/CERT-71234567-99')
        );
        $this->assertEquals($customCert->validation_url, $customCert->verification_url);

        // Printable certificate renders QR code link pointing to validation_url
        $responsePrint = $this->get('/validar-certificado/'.$this->certificate->certificate_code.'/imprimir');
        $responsePrint->assertStatus(200);
        $responsePrint->assertSee($this->certificate->validation_url);

        $customCert->delete();

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
        $expectedCredits = (int) ($englishCourse->modules->first()?->credits ?? 4);
        $this->assertEquals($expectedCredits, (int) $modulesData[0]['credits']);
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

    public function test_user_can_hold_multiple_certificates_and_relationship_works(): void
    {
        $student = User::where('dni', '71234567')->firstOrFail();

        Certificate::where('issue_date', '2026-10-05')->where('user_id', $student->id)->delete();

        $secondCourse = Course::firstOrCreate(
            ['name' => 'TALLER DE CIBERSEGURIDAD Y REDES'],
            [
                'certificate_type' => 'capacitacion',
                'event_name' => 'Semana Técnica 2026',
                'is_active' => true,
            ]
        );

        $secondCert = Certificate::create([
            'certificate_code' => Certificate::generateUniqueCodeForStudent($student->dni, $secondCourse->id),
            'user_id' => $student->id,
            'course_id' => $secondCourse->id,
            'certificate_type' => 'capacitacion',
            'participation_type' => 'ASISTENTE',
            'event_name' => 'Semana Técnica 2026',
            'issue_date' => '2026-10-05',
            'is_active' => true,
        ]);

        $this->assertGreaterThanOrEqual(2, $student->certificates()->count());
        $this->assertTrue($student->certificates->contains('id', $this->certificate->id));
        $this->assertTrue($student->certificates->contains('id', $secondCert->id));

        $userCerts = Certificate::forUser($student)->get();
        $this->assertGreaterThanOrEqual(2, $userCerts->count());
    }

    public function test_certificate_find_duplicate_identifies_matching_event_course_and_date(): void
    {
        $duplicate = Certificate::findDuplicate(
            userId: $this->certificate->user_id,
            courseId: $this->certificate->course_id,
            eventName: $this->certificate->event_name,
            issueDate: $this->certificate->issue_date,
            startDate: $this->certificate->start_date
        );

        $this->assertNotNull($duplicate);
        $this->assertEquals($this->certificate->id, $duplicate->id);

        $notDuplicateDifferentDate = Certificate::findDuplicate(
            userId: $this->certificate->user_id,
            courseId: $this->certificate->course_id,
            eventName: $this->certificate->event_name,
            issueDate: '2026-12-01',
            startDate: $this->certificate->start_date
        );
        $this->assertNull($notDuplicateDifferentDate);
    }

    public function test_manual_registration_validates_and_rejects_duplicate_certificate(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin-certificados', [
            'user_id' => $this->certificate->user_id,
            'course_id' => $this->certificate->course_id,
            'event_name' => $this->certificate->event_name,
            'issue_date' => $this->certificate->issue_date,
            'certificate_code' => 'CERT-TEST-DUP-01',
            'modality' => 'Presencial',
            'certificate_type' => 'capacitacion',
        ]);

        $response->assertSessionHasErrors(['user_id']);
    }

    public function test_bulk_import_skips_duplicate_certificate_rows_and_creates_valid_ones(): void
    {
        $student = User::where('dni', '71234567')->firstOrFail();
        $course = Course::find($this->certificate->course_id);

        Certificate::where('issue_date', '2026-11-10')->where('user_id', $student->id)->delete();

        $import = new CertificateImport;

        // Row with duplicate data forJose Chavez: same DNI, same course name, same issue date
        $duplicateRow = [
            0 => 1,
            1 => $student->dni,
            2 => $student->names,
            3 => $course->name,
            4 => '2026-09-21',
            5 => '2026-09-24',
            6 => $this->certificate->issue_date,
            7 => '90',
            8 => '16',
            9 => 'Dieciséis',
            10 => '17',
            11 => 'Diecisiete',
            12 => '16.5',
            13 => 'Presencial',
        ];

        // Row with different date for the same student and course -> should succeed with unique code
        $validRow = [
            0 => 2,
            1 => $student->dni,
            2 => $student->names,
            3 => $course->name,
            4 => '2026-11-01',
            5 => '2026-11-05',
            6 => '2026-11-10',
            7 => '40',
            8 => '18',
            9 => 'Dieciocho',
            10 => '19',
            11 => 'Diecinueve',
            12 => '18.5',
            13 => 'Virtual',
        ];

        $rows = collect([$duplicateRow, $validRow]);
        $import->collection($rows);

        $this->assertEquals(1, $import->skippedCount);
        $this->assertNotEmpty($import->errors);
        $this->assertStringContainsString('Certificado duplicado omitido', $import->errors[0]);
        $this->assertEquals(1, $import->importedCount);
    }

    public function test_validar_certificado_by_dni_displays_all_certificates_when_user_has_multiple(): void
    {
        $student = User::where('dni', '71234567')->firstOrFail();

        Certificate::where('certificate_code', 'CERT-IA-2026-TEST')->delete();

        $secondCourse = Course::firstOrCreate(
            ['name' => 'SEMINARIO DE INTELIGENCIA ARTIFICIAL'],
            [
                'certificate_type' => 'capacitacion',
                'event_name' => 'Seminario IA 2026',
                'is_active' => true,
            ]
        );

        $secondCert = Certificate::create([
            'certificate_code' => 'CERT-IA-2026-TEST',
            'user_id' => $student->id,
            'course_id' => $secondCourse->id,
            'certificate_type' => 'capacitacion',
            'participation_type' => 'ASISTENTE',
            'event_name' => 'Seminario IA 2026',
            'issue_date' => '2026-10-06',
            'is_active' => true,
        ]);

        // Search by DNI via route parameter
        $response = $this->get('/validar-certificado/'.$student->dni);

        $response->assertStatus(200);
        $response->assertSee($student->names);
        $response->assertSee($this->certificate->certificate_code);
        $response->assertSee($secondCert->certificate_code);
        $response->assertSee('Certificados Disponibles');
        $response->assertSee('Certificados del estudiante');

        // Search by DNI via query parameter (?code=...)
        $responseQuery = $this->get('/validar-certificado?code='.$student->dni);
        $responseQuery->assertStatus(200);
        $responseQuery->assertSee($student->names);
        $responseQuery->assertSee($this->certificate->certificate_code);
        $responseQuery->assertSee($secondCert->certificate_code);
    }

    public function test_certificate_model_and_migration_support_code_field(): void
    {
        $this->assertNotNull($this->certificate->code);
        $this->assertEquals($this->certificate->certificate_code, $this->certificate->code);

        Certificate::where('code', 'CERT-CODE-TEST-1')->delete();

        $certWithCode = Certificate::create([
            'code' => 'CERT-CODE-TEST-1',
            'user_id' => $this->certificate->user_id,
            'course_id' => $this->certificate->course_id,
            'certificate_type' => 'capacitacion',
            'participation_type' => 'ASISTENTE',
            'event_name' => 'Semana Técnica 2026',
            'issue_date' => '2026-11-20',
            'is_active' => true,
        ]);

        $this->assertEquals('CERT-CODE-TEST-1', $certWithCode->code);
        $this->assertEquals('CERT-CODE-TEST-1', $certWithCode->certificate_code);

        Certificate::where('code', 'CERT-CODE-TEST-1')->delete();
    }

    public function test_generate_unique_code_for_student_uses_sequence_format(): void
    {
        $testDni = '99887766';
        Certificate::where('code', 'LIKE', "CERT-{$testDni}-%")->delete();

        $code1 = Certificate::generateUniqueCodeForStudent($testDni);
        $this->assertEquals("CERT-{$testDni}-1", $code1);

        // If that code is taken, next should be sequence 2
        Certificate::create([
            'code' => $code1,
            'user_id' => $this->certificate->user_id,
            'course_id' => $this->certificate->course_id,
            'certificate_type' => 'capacitacion',
            'issue_date' => '2026-11-21',
            'is_active' => true,
        ]);

        $code2 = Certificate::generateUniqueCodeForStudent($testDni);
        $this->assertEquals("CERT-{$testDni}-2", $code2);

        Certificate::where('code', 'LIKE', "CERT-{$testDni}-%")->delete();
    }

    public function test_certificate_import_uses_provided_code_when_present(): void
    {
        $student = User::where('dni', '71234567')->firstOrFail();
        $course = Course::find($this->certificate->course_id);

        $customCode = 'CERT-CUSTOM-EXCEL-001';
        Certificate::where('code', $customCode)->delete();
        Certificate::where('issue_date', '2026-12-15')->where('user_id', $student->id)->delete();

        $import = new CertificateImport;
        $row = [
            0 => 1,
            1 => $student->dni,
            2 => $student->names,
            3 => $course->name,
            4 => '2026-12-01',
            5 => '2026-12-10',
            6 => '2026-12-15',
            7 => '60',
            8 => '18',
            9 => 'Dieciocho',
            10 => '19',
            11 => 'Diecinueve',
            12 => '18.5',
            13 => 'Presencial',
            14 => $customCode, // Col O: Provided code
        ];

        $import->collection(collect([$row]));

        $this->assertEquals(1, $import->importedCount);
        $createdCert = Certificate::where('code', $customCode)->first();
        $this->assertNotNull($createdCert);
        $this->assertEquals($customCode, $createdCert->code);
        $this->assertEquals($customCode, $createdCert->certificate_code);

        Certificate::where('code', $customCode)->delete();
    }

    public function test_certificate_import_auto_generates_code_when_code_column_empty(): void
    {
        $student = User::where('dni', '71234567')->firstOrFail();
        $course = Course::find($this->certificate->course_id);

        Certificate::where('issue_date', '2026-12-18')->where('user_id', $student->id)->delete();

        $import = new CertificateImport;
        $row = [
            0 => 1,
            1 => $student->dni,
            2 => $student->names,
            3 => $course->name,
            4 => '2026-12-01',
            5 => '2026-12-10',
            6 => '2026-12-18',
            7 => '60',
            8 => '18',
            9 => 'Dieciocho',
            10 => '19',
            11 => 'Diecinueve',
            12 => '18.5',
            13 => 'Presencial',
            14 => '', // Col O: Empty code -> should auto-generate CERT-{dni}-{sequence}
        ];

        $import->collection(collect([$row]));

        $this->assertEquals(1, $import->importedCount);
        $createdCert = Certificate::where('user_id', $student->id)
            ->where('issue_date', '2026-12-18')
            ->first();

        $this->assertNotNull($createdCert);
        $this->assertMatchesRegularExpression('/^CERT-71234567-\d+$/', $createdCert->code);

        Certificate::where('issue_date', '2026-12-18')->where('user_id', $student->id)->delete();
    }

    public function test_download_template_includes_code_and_participation_type_in_csv(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin-certificados/plantilla');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('Código', $content);
        $this->assertStringContainsString('Condición / Participación', $content);
        $this->assertStringContainsString('CERT-{DNI}-{secuencia}', $content);
        $this->assertStringContainsString('ASISTENTE', $content);
        $this->assertStringContainsString('PONENTE', $content);
        $this->assertStringContainsString('ORGANIZADOR', $content);
    }

    public function test_download_template_supports_xlsx_format(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin-certificados/plantilla?format=xlsx');

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('Content-Disposition') ?? '', '.xlsx') ||
            str_contains($response->headers->get('Content-Type') ?? '', 'spreadsheet') ||
            str_contains($response->headers->get('Content-Type') ?? '', 'openxmlformats')
        );
    }

    public function test_certificate_import_reads_and_saves_participation_type(): void
    {
        User::where('dni', '71239999')->delete();
        Certificate::where('code', 'CERT-PONENTE-001')->delete();

        $student = User::factory()->create([
            'dni' => '71239999',
            'names' => 'Ponente Test User',
        ]);

        $import = new CertificateImport;
        $row = [
            0 => '1',
            1 => $student->dni,
            2 => $student->names,
            3 => $this->certificate->course->name,
            4 => '2026-12-01',
            5 => '2026-12-10',
            6 => '2026-12-25',
            7 => '80',
            8 => '19',
            9 => 'Diecinueve',
            10 => '20',
            11 => 'Veinte',
            12 => '19.5',
            13 => 'Virtual',
            14 => 'CERT-PONENTE-001',
            15 => 'PONENTE', // Col P: participation_type
        ];

        $import->collection(collect([$row]));

        $this->assertEquals(1, $import->importedCount);
        $createdCert = Certificate::where('user_id', $student->id)
            ->where('issue_date', '2026-12-25')
            ->first();

        $this->assertNotNull($createdCert);
        $this->assertEquals('PONENTE', $createdCert->participation_type);
        $this->assertEquals('CERT-PONENTE-001', $createdCert->code);

        Certificate::where('issue_date', '2026-12-25')->where('user_id', $student->id)->delete();
        $student->delete();
    }

    public function test_certificate_import_defaults_to_asistente_when_participation_type_empty(): void
    {
        User::where('dni', '71238888')->delete();
        Certificate::where('code', 'LIKE', 'CERT-71238888-%')->delete();

        $student = User::factory()->create([
            'dni' => '71238888',
            'names' => 'Asistente Default User',
        ]);

        $import = new CertificateImport;
        $row = [
            0 => '1',
            1 => $student->dni,
            2 => $student->names,
            3 => $this->certificate->course->name,
            4 => '2026-12-01',
            5 => '2026-12-10',
            6 => '2026-12-26',
            7 => '80',
            8 => '17',
            9 => 'Diecisiete',
            10 => '18',
            11 => 'Dieciocho',
            12 => '17.5',
            13 => 'Presencial',
            14 => '',
            15 => '', // Empty participation_type -> defaults to ASISTENTE
        ];

        $import->collection(collect([$row]));

        $this->assertEquals(1, $import->importedCount);
        $createdCert = Certificate::where('user_id', $student->id)
            ->where('issue_date', '2026-12-26')
            ->first();

        $this->assertNotNull($createdCert);
        $this->assertEquals('ASISTENTE', $createdCert->participation_type);
        $this->assertMatchesRegularExpression('/^CERT-71238888-\d+$/', $createdCert->code);

        Certificate::where('issue_date', '2026-12-26')->where('user_id', $student->id)->delete();
        $student->delete();
    }

    public function test_admin_certificates_view_renders_checkboxes_and_bulk_delete_controls(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin-certificados');

        $response->assertStatus(200);
        $response->assertSee('Eliminar Todos');
        $response->assertSee('Eliminar Seleccionados');
        $response->assertSee('toggleSelectAll');
        $response->assertSee('selectedCerts');
    }

    public function test_bulk_delete_selected_certificates_successfully(): void
    {
        $cert1 = Certificate::create([
            'certificate_code' => 'TEST-BULK-DEL-1',
            'code' => 'TEST-BULK-DEL-1',
            'user_id' => $this->certificate->user_id,
            'course_id' => $this->certificate->course_id,
            'certificate_type' => 'capacitacion',
            'participation_type' => 'ASISTENTE',
            'event_name' => 'Evento Test Bulk 1',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-02',
            'duration' => '10 horas',
            'modality' => 'Presencial',
            'issue_date' => '2026-10-05',
            'is_active' => true,
        ]);

        $cert2 = Certificate::create([
            'certificate_code' => 'TEST-BULK-DEL-2',
            'code' => 'TEST-BULK-DEL-2',
            'user_id' => $this->certificate->user_id,
            'course_id' => $this->certificate->course_id,
            'certificate_type' => 'capacitacion',
            'participation_type' => 'PONENTE',
            'event_name' => 'Evento Test Bulk 2',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-02',
            'duration' => '10 horas',
            'modality' => 'Presencial',
            'issue_date' => '2026-10-05',
            'is_active' => true,
        ]);

        $certKeep = Certificate::create([
            'certificate_code' => 'TEST-BULK-KEEP',
            'code' => 'TEST-BULK-KEEP',
            'user_id' => $this->certificate->user_id,
            'course_id' => $this->certificate->course_id,
            'certificate_type' => 'capacitacion',
            'participation_type' => 'ORGANIZADOR',
            'event_name' => 'Evento Test Bulk Keep',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-02',
            'duration' => '10 horas',
            'modality' => 'Presencial',
            'issue_date' => '2026-10-05',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post('/admin-certificados/eliminar-masivo', [
                'ids' => [$cert1->id, $cert2->id],
            ]);

        $response->assertRedirect('/admin-certificados');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('certificates', ['id' => $cert1->id]);
        $this->assertDatabaseMissing('certificates', ['id' => $cert2->id]);
        $this->assertDatabaseHas('certificates', ['id' => $certKeep->id]);

        $certKeep->delete();
    }

    public function test_bulk_delete_via_json_request(): void
    {
        $cert = Certificate::create([
            'certificate_code' => 'TEST-BULK-JSON',
            'code' => 'TEST-BULK-JSON',
            'user_id' => $this->certificate->user_id,
            'course_id' => $this->certificate->course_id,
            'certificate_type' => 'capacitacion',
            'participation_type' => 'ASISTENTE',
            'event_name' => 'Evento Test JSON',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-02',
            'duration' => '10 horas',
            'modality' => 'Presencial',
            'issue_date' => '2026-10-05',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson('/admin-certificados/eliminar-masivo', [
                'ids' => [$cert->id],
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);

        $this->assertDatabaseMissing('certificates', ['id' => $cert->id]);
    }

    public function test_bulk_delete_validates_empty_selection(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->postJson('/admin-certificados/eliminar-masivo', [
                'ids' => [],
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Debe seleccionar al menos un certificado para eliminar.',
        ]);
    }

    public function test_delete_all_certificates_successfully(): void
    {
        $this->assertGreaterThan(0, Certificate::count());

        $response = $this->actingAs($this->adminUser)
            ->deleteJson('/admin-certificados/eliminar-todos');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertEquals(0, Certificate::count());
    }

    public function test_delete_all_certificates_when_database_is_empty(): void
    {
        Certificate::query()->delete();

        $response = $this->actingAs($this->adminUser)
            ->deleteJson('/admin-certificados/eliminar-todos');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'No hay certificados registrados para eliminar.',
        ]);
    }
}
