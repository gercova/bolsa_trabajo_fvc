<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookFavorite;
use App\Models\LibraryAccessLog;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryModuleTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $docente;
    protected User $estudiante;
    protected StudyProgram $program;

    protected function setUp(): void
    {
        parent::setUp();

        $docTypeId = DB::table('document_type')->value('id') ?? 1;

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test_lib@iestpfvc.edu.pe'],
            [
                'dni'              => '99887711',
                'names'            => 'Admin Biblioteca',
                'password'         => bcrypt('secret123'),
                'document_type_id' => $docTypeId,
                'role'             => 'Admin',
            ]
        );

        $this->docente = User::firstOrCreate(
            ['email' => 'docente_test_lib@iestpfvc.edu.pe'],
            [
                'dni'              => '99887722',
                'names'            => 'Docente Biblioteca',
                'password'         => bcrypt('secret123'),
                'document_type_id' => $docTypeId,
                'role'             => 'Docente',
            ]
        );

        $this->estudiante = User::firstOrCreate(
            ['email' => 'estudiante_test_lib@iestpfvc.edu.pe'],
            [
                'dni'              => '99887733',
                'names'            => 'Estudiante Biblioteca',
                'password'         => bcrypt('secret123'),
                'document_type_id' => $docTypeId,
                'role'             => 'Estudiante',
            ]
        );

        $this->program = StudyProgram::first() ?? StudyProgram::create([
            'name'        => 'Administración de Redes y Comunicaciones',
            'slug'        => 'administracion-redes-comunicaciones',
            'description' => 'Programa de prueba',
            'is_active'   => true,
        ]);
    }

    public function test_guest_is_redirected_from_admin_library(): void
    {
        $response = $this->get(route('admin.library.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_library_assigned_books(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.library.index'));

        $response->assertStatus(200);
        $response->assertSee('Libros Asignados');
        $response->assertSee('Subir Contenido');
        $response->assertSee('Biblioteca Virtual');
    }

    public function test_admin_can_access_library_repository(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.library.repository'));

        $response->assertStatus(200);
        $response->assertSee('Repositorio de Contenidos');
        $response->assertSee('Subir Nuevo Contenido');
    }

    public function test_admin_can_access_library_readers_monitoring_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.library.readers'));

        $response->assertStatus(200);
        $response->assertSee('Monitoreo de Lectores');
        $response->assertSee('Lectores Totales');
    }

    public function test_admin_can_store_book_with_pdf(): void
    {
        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('libro_redes.pdf', 2048, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.library.store'), [
            'title'            => 'Fundamentos de Redes Cisco 2026',
            'author'           => 'Andrew Tanenbaum',
            'category'         => 'Libro',
            'study_program_id' => $this->program->id,
            'source_type'      => 'file',
            'file'             => $pdf,
            'pages'            => 450,
            'language'         => 'Español',
            'publisher'        => 'Pearson Education',
            'year'             => 2026,
            'is_active'        => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('books', [
            'title'            => 'Fundamentos de Redes Cisco 2026',
            'author'           => 'Andrew Tanenbaum',
            'category'         => 'Libro',
            'study_program_id' => $this->program->id,
            'is_active'        => 1,
        ]);
    }

    public function test_admin_can_store_book_with_external_link(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.library.store'), [
            'title'            => 'Paper Científico de Ciberseguridad 2026',
            'author'           => 'Revista IEEE',
            'category'         => 'Paper',
            'study_program_id' => $this->program->id,
            'source_type'      => 'link',
            'external_url'     => 'https://scielo.org/articulo-ciberseguridad-2026',
            'language'         => 'Inglés',
            'year'             => 2026,
            'is_active'        => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('books', [
            'title'        => 'Paper Científico de Ciberseguridad 2026',
            'category'     => 'Paper',
            'external_url' => 'https://scielo.org/articulo-ciberseguridad-2026',
        ]);
    }

    public function test_admin_can_toggle_book_status(): void
    {
        $book = Book::create([
            'title'            => 'Libro Desactivable',
            'slug'             => 'libro-desactivable',
            'author'           => 'Autor X',
            'category'         => 'Libro',
            'study_program_id' => $this->program->id,
            'external_url'     => 'https://example.com/libro',
            'is_active'        => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.library.toggle-status', $book));

        $response->assertRedirect();
        $this->assertDatabaseHas('books', [
            'id'        => $book->id,
            'is_active' => 0,
        ]);
    }

    public function test_reader_home_displays_personalized_greeting_and_sections(): void
    {
        $response = $this->actingAs($this->docente)->get(route('biblioteca.index'));

        $response->assertStatus(200);
        $response->assertSee('Hola, ' . $this->docente->names);
        $response->assertSee('Mi biblioteca');
        $response->assertSee('Leídos últimamente');
    }

    public function test_reader_can_filter_books_in_catalog(): void
    {
        $book = Book::create([
            'title'            => 'Manual Especializado de Algoritmos',
            'slug'             => 'manual-especializado-de-algoritmos',
            'author'           => 'Knuth D.',
            'category'         => 'Manual',
            'study_program_id' => $this->program->id,
            'external_url'     => 'https://example.com/manual',
            'is_active'        => true,
        ]);

        $response = $this->actingAs($this->estudiante)->get(route('biblioteca.search', ['q' => 'Algoritmos']));

        $response->assertStatus(200);
        $response->assertSee('Manual Especializado de Algoritmos');
    }

    public function test_reader_can_toggle_favorite_book_via_ajax(): void
    {
        $book = Book::create([
            'title'            => 'Libro Para Favoritos',
            'slug'             => 'libro-para-favoritos',
            'author'           => 'Autor F',
            'category'         => 'Libro',
            'study_program_id' => $this->program->id,
            'external_url'     => 'https://example.com/fav',
            'is_active'        => true,
        ]);

        // Toggle ON
        $response = $this->actingAs($this->estudiante)
            ->postJson(route('biblioteca.favorite.toggle', $book));

        $response->assertStatus(200);
        $response->assertJson([
            'success'   => true,
            'favorited' => true,
        ]);

        $this->assertDatabaseHas('book_favorites', [
            'user_id' => $this->estudiante->id,
            'book_id' => $book->id,
        ]);

        // Toggle OFF
        $response2 = $this->actingAs($this->estudiante)
            ->postJson(route('biblioteca.favorite.toggle', $book));

        $response2->assertStatus(200);
        $response2->assertJson([
            'success'   => true,
            'favorited' => false,
        ]);

        $this->assertDatabaseMissing('book_favorites', [
            'user_id' => $this->estudiante->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_my_library_displays_user_favorites(): void
    {
        $book = Book::create([
            'title'            => 'Libro en Mi Biblioteca Favorito',
            'slug'             => 'libro-en-mi-biblioteca-favorito',
            'author'           => 'Autor Fav',
            'category'         => 'Libro',
            'study_program_id' => $this->program->id,
            'external_url'     => 'https://example.com/fav2',
            'is_active'        => true,
        ]);

        BookFavorite::create([
            'user_id' => $this->docente->id,
            'book_id' => $book->id,
        ]);

        $response = $this->actingAs($this->docente)->get(route('biblioteca.favorites'));

        $response->assertStatus(200);
        $response->assertSee('Mi Biblioteca');
        $response->assertSee('Libro en Mi Biblioteca Favorito');
    }

    public function test_reading_external_book_registers_access_log_and_redirects(): void
    {
        $book = Book::create([
            'title'            => 'Libro Enlace Externo Test',
            'slug'             => 'libro-enlace-externo-test',
            'author'           => 'Autor E',
            'category'         => 'Paper',
            'study_program_id' => $this->program->id,
            'external_url'     => 'https://scielo.org/articulo-test',
            'is_active'        => true,
            'views_count'      => 0,
        ]);

        $response = $this->actingAs($this->estudiante)->get(route('biblioteca.read', $book));

        $response->assertRedirect('https://scielo.org/articulo-test');

        $this->assertDatabaseHas('library_access_logs', [
            'user_id'     => $this->estudiante->id,
            'book_id'     => $book->id,
            'access_type' => 'external_link',
        ]);

        $book->refresh();
        $this->assertEquals(1, $book->views_count);
    }

    public function test_reading_pdf_book_registers_access_log_and_shows_viewer(): void
    {
        $book = Book::create([
            'title'            => 'Libro PDF Interno Test',
            'slug'             => 'libro-pdf-interno-test',
            'author'           => 'Autor P',
            'category'         => 'Libro',
            'study_program_id' => $this->program->id,
            'file_path'        => 'library/books/test.pdf',
            'is_active'        => true,
            'views_count'      => 0,
        ]);

        $response = $this->actingAs($this->estudiante)->get(route('biblioteca.read', $book));

        $response->assertStatus(200);
        $response->assertSee('Libro PDF Interno Test');

        $this->assertDatabaseHas('library_access_logs', [
            'user_id'     => $this->estudiante->id,
            'book_id'     => $book->id,
            'access_type' => 'view',
        ]);

        $book->refresh();
        $this->assertEquals(1, $book->views_count);
    }
}
