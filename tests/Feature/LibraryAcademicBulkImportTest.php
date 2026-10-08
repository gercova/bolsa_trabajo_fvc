<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\StudyProgram;
use App\Models\User;
use App\Services\AcademicLibraryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LibraryAcademicBulkImportTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected User $estudiante;

    protected StudyProgram $program;

    protected function setUp(): void
    {
        parent::setUp();

        $docTypeId = DB::table('document_type')->value('id') ?? 1;

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_academic_test@iestpfvc.edu.pe'],
            [
                'dni' => '88776611',
                'names' => 'Admin Academico Test',
                'password' => bcrypt('secret123'),
                'document_type_id' => $docTypeId,
                'role' => 'Admin',
            ]
        );

        $this->estudiante = User::firstOrCreate(
            ['email' => 'estudiante_academic_test@iestpfvc.edu.pe'],
            [
                'dni' => '88776622',
                'names' => 'Estudiante Academico Test',
                'password' => bcrypt('secret123'),
                'document_type_id' => $docTypeId,
                'role' => 'Estudiante',
            ]
        );

        $this->program = StudyProgram::first() ?? StudyProgram::create([
            'name' => 'Manejo Forestal Test',
            'slug' => 'manejo-forestal-test',
            'description' => 'Programa de Prueba Manejo Forestal',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_trigger_academic_bulk_import(): void
    {
        $response = $this->postJson(route('admin.library.fetch-academic'), [
            'study_program_id' => $this->program->id,
            'limit' => 5,
        ]);

        $response->assertStatus(401);
    }

    public function test_student_cannot_trigger_academic_bulk_import(): void
    {
        $response = $this->actingAs($this->estudiante)->postJson(route('admin.library.fetch-academic'), [
            'study_program_id' => $this->program->id,
            'limit' => 5,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_repository_view_includes_academic_modal_and_iframe_visor(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.library.repository'));

        $response->assertStatus(200);
        $response->assertSee('Búsqueda Masiva de Revistas / Papers');
        $response->assertSee('openAcademicModal()');
        $response->assertSee('showAcademicModal');
        $response->assertSee('showIframeModal');
        $response->assertSee('SciELO');
        $response->assertSee('Redalyc');
    }

    public function test_academic_service_stores_external_books_and_deduplicates(): void
    {
        $service = app(AcademicLibraryService::class);

        $testItem = [
            'title' => 'Investigación Forestal y Silvicultura en el Bosque Tropical Peruano',
            'author' => 'Dr. Investigador Silvicultura',
            'publisher' => 'Revista Forestal Latinoamericana (SciELO)',
            'publication_year' => 2024,
            'category' => 'Paper',
            'study_program_id' => $this->program->id,
            'external_url' => 'https://scielo.org/journal/silvicultura-forestal-tropical-2024',
            'description' => 'Estudio riguroso sobre sostenibilidad forestal en la selva central peruana.',
            'language' => 'Español',
        ];

        // 1. First insertion -> Should be created
        $result1 = $service->storeAcademicItem($testItem);
        $this->assertEquals('created', $result1['status']);
        $this->assertNotNull($result1['book']);
        $this->assertTrue($result1['book']->is_external);
        $this->assertEquals($this->program->id, $result1['book']->study_program_id);

        // 2. Second insertion of identical external_url -> Should be updated or skipped (deduplicated)
        $result2 = $service->storeAcademicItem($testItem);
        $this->assertContains($result2['status'], ['updated', 'skipped']);
        $this->assertEquals($result1['book']->id, $result2['book']->id);
        $this->assertEquals(1, Book::where('external_url', $testItem['external_url'])->count());

        // Clean up created book
        $result1['book']->delete();
    }

    public function test_admin_can_bulk_import_academic_resources_via_controller(): void
    {
        // Mock service so test is deterministic and independent of live external internet access
        $mockService = $this->createMock(AcademicLibraryService::class);
        $mockService->expects($this->once())
            ->method('fetchAndImport')
            ->willReturn([
                'success' => true,
                'total' => 2,
                'saved' => 2,
                'updated' => 0,
                'skipped' => 0,
                'items' => [
                    [
                        'id' => 9991,
                        'title' => 'Paper 1: Agroforestería y Conservación',
                        'author' => 'Autor Forestal 1',
                        'category' => 'Paper',
                        'publisher' => 'SciELO',
                        'publication_year' => 2024,
                        'external_url' => 'https://scielo.org/paper-1',
                        'program_name' => 'Manejo Forestal',
                    ],
                    [
                        'id' => 9992,
                        'title' => 'Paper 2: Enfermería Técnica en Salud Comunitaria',
                        'author' => 'Autor Salud 2',
                        'category' => 'Revista',
                        'publisher' => 'Redalyc',
                        'publication_year' => 2023,
                        'external_url' => 'https://redalyc.org/paper-2',
                        'program_name' => 'Enfermería Técnica',
                    ],
                ],
                'message' => 'Se importaron exitosamente 2 nuevas publicaciones académicas.',
            ]);

        $this->app->instance(AcademicLibraryService::class, $mockService);

        $response = $this->actingAs($this->admin)->postJson(route('admin.library.fetch-academic'), [
            'study_program_id' => $this->program->id,
            'category' => 'all',
            'limit' => 2,
            'sources' => ['scielo', 'redalyc'],
            'custom_query' => 'agroforesteria',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total' => 2,
            'saved' => 2,
        ]);
        $response->assertJsonPath('items.0.title', 'Paper 1: Agroforestería y Conservación');
        $response->assertJsonPath('items.1.publisher', 'Redalyc');
    }

    public function test_saved_external_book_opens_in_site_iframe_reader(): void
    {
        $book = Book::create([
            'title' => 'Revista Científica de Enfermería Comunitaria',
            'slug' => 'revista-cientifica-de-enfermeria-comunitaria',
            'author' => 'Dra. María Gonzales',
            'category' => 'Revista',
            'study_program_id' => $this->program->id,
            'external_url' => 'https://scielo.isciii.es/scielo.php?script=sci_arttext&pid=S1132-12962024000100005',
            'publisher' => 'SciELO Enfermería',
            'is_active' => true,
            'views_count' => 0,
        ]);

        // Student opens reader
        $response = $this->actingAs($this->estudiante)->get(route('biblioteca.read', $book));

        // Must open within the site, NOT redirecting away
        $response->assertStatus(200);
        $response->assertViewIs('library.reader');
        $response->assertSee('<iframe', false);
        $response->assertSee('libraryViewerIframe');
        $response->assertSee($book->external_url);
        $response->assertSee('Permaneciendo en el sitio institucional');

        // Check access log recorded
        $this->assertDatabaseHas('library_access_logs', [
            'user_id' => $this->estudiante->id,
            'book_id' => $book->id,
            'access_type' => 'external_link',
        ]);
    }
}
