<?php

namespace Tests\Feature;

use App\Models\DocumentType;
use App\Models\Enterprise;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudyProgramImagesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $docType = DocumentType::create([
            'name' => 'DNI',
            'abreviation' => 'DNI',
            'is_active' => true,
        ]);

        Enterprise::firstOrCreate(['id' => 1], [
            'ruc' => '20123456789',
            'company_name' => 'IESTP Francisco Vigo Caballero',
            'trade_name' => 'FVC',
            'legal_representative_dni' => '12345678',
            'legal_representative' => 'Director FVC',
            'email' => 'contacto@fvc.edu.pe',
            'phone_number_1' => '+51 987654321',
            'whatsapp_link' => 'https://wa.me/51987654321',
            'address' => 'Av. Fernando Belaunde Terry Km 1.5',
            'city' => 'Uchiza',
        ]);

        $this->admin = User::factory()->create([
            'role' => 'Admin',
            'document_type_id' => $docType->id,
        ]);
    }

    public function test_can_create_study_program_with_multiple_gallery_images(): void
    {
        Storage::fake('public');

        $image1 = UploadedFile::fake()->image('laboratorio.jpg', 800, 600);
        $image2 = UploadedFile::fake()->image('taller.png', 800, 600);
        $image3 = UploadedFile::fake()->image('campo.webp', 800, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.programs.store'), [
            'name' => 'Administración de Redes y Comunicaciones',
            'description' => 'Formación técnica en conectividad e infraestructura de redes.',
            'details' => 'Duración: 3 años. Título a Nombre de la Nación.',
            'images' => [$image1, $image2, $image3],
            'main_image_index' => 1, // Marcar la segunda como principal
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.programs.index', ['tab' => 'programs']));
        $response->assertSessionHas('success');

        $program = StudyProgram::where('name', 'Administración de Redes y Comunicaciones')->first();
        $this->assertNotNull($program);

        // Verificar que se hayan creado 3 registros en la tabla images asociados a este programa
        $this->assertCount(3, $program->images);

        $images = $program->images;
        foreach ($images as $img) {
            $this->assertEquals(StudyProgram::class, $img->imageable_type);
            $this->assertEquals($program->id, $img->imageable_id);
            Storage::disk('public')->assertExists($img->path);
        }

        // Verificar que la imagen con índice 1 esté marcada como principal
        $mainImage = $program->images()->where('is_main', true)->first();
        $this->assertNotNull($mainImage);
        $this->assertEquals($images[1]->id, $mainImage->id);
    }

    public function test_edit_program_view_displays_existing_images(): void
    {
        Storage::fake('public');

        $program = StudyProgram::create([
            'name' => 'Enfermería Técnica',
            'slug' => 'enfermeria-tecnica',
            'description' => 'Cuidado integral de la salud del paciente.',
            'details' => 'Duración: 3 años',
            'is_active' => true,
        ]);

        $image = $program->images()->create([
            'path' => 'programs/gallery/enfermeria_lab.jpg',
            'is_main' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.programs.edit', $program));

        $response->assertOk();
        $response->assertSee('Galería de Fotos del Programa');
        $response->assertSee('enfermeria_lab.jpg');
    }

    public function test_can_update_program_and_upload_new_gallery_images(): void
    {
        Storage::fake('public');

        $program = StudyProgram::create([
            'name' => 'Producción Agropecuaria',
            'slug' => 'produccion-agropecuaria',
            'description' => 'Producción agrícola y pecuaria sostenible.',
            'details' => 'Duración: 3 años',
            'is_active' => true,
        ]);

        $existingImg = $program->images()->create([
            'path' => 'programs/gallery/fundo1.jpg',
            'is_main' => true,
        ]);
        Storage::disk('public')->put($existingImg->path, 'dummy content');

        $newImage = UploadedFile::fake()->image('invernadero.jpg');

        $response = $this->actingAs($this->admin)->put(route('admin.programs.update', $program), [
            'name' => 'Producción Agropecuaria',
            'description' => 'Descripción actualizada',
            'details' => 'Duración: 3 años',
            'images' => [$newImage],
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.programs.index', ['tab' => 'programs']));

        $program->refresh();
        $this->assertCount(2, $program->images);
    }

    public function test_can_delete_existing_image_on_program_update(): void
    {
        Storage::fake('public');

        $program = StudyProgram::create([
            'name' => 'Manejo Forestal',
            'slug' => 'manejo-forestal',
            'description' => 'Gestión y conservación de recursos forestales.',
            'details' => 'Duración: 3 años',
            'is_active' => true,
        ]);

        $imgToDelete = $program->images()->create([
            'path' => 'programs/gallery/bosque_eliminar.jpg',
            'is_main' => false,
        ]);
        Storage::disk('public')->put($imgToDelete->path, 'content to delete');

        $imgToKeep = $program->images()->create([
            'path' => 'programs/gallery/vivero.jpg',
            'is_main' => true,
        ]);
        Storage::disk('public')->put($imgToKeep->path, 'content to keep');

        $response = $this->actingAs($this->admin)->put(route('admin.programs.update', $program), [
            'name' => 'Manejo Forestal',
            'description' => 'Gestión y conservación de recursos forestales.',
            'details' => 'Duración: 3 años',
            'delete_images' => [$imgToDelete->id],
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.programs.index', ['tab' => 'programs']));

        $this->assertDatabaseMissing('images', ['id' => $imgToDelete->id]);
        Storage::disk('public')->assertMissing($imgToDelete->path);

        $this->assertDatabaseHas('images', ['id' => $imgToKeep->id]);
        Storage::disk('public')->assertExists($imgToKeep->path);
    }

    public function test_can_delete_image_via_dedicated_endpoint(): void
    {
        Storage::fake('public');

        $program = StudyProgram::create([
            'name' => 'Asistencia Administrativa',
            'slug' => 'asistencia-administrativa',
            'description' => 'Gestión documental y empresarial.',
            'details' => 'Duración: 3 años',
            'is_active' => true,
        ]);

        $img = $program->images()->create([
            'path' => 'programs/gallery/oficina.jpg',
            'is_main' => true,
        ]);
        Storage::disk('public')->put($img->path, 'test office');

        $response = $this->actingAs($this->admin)->deleteJson(
            route('admin.programs.images.destroy', [$program, $img])
        );

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('images', ['id' => $img->id]);
        Storage::disk('public')->assertMissing($img->path);
    }

    public function test_public_detail_view_displays_gallery_images(): void
    {
        Storage::fake('public');

        $program = StudyProgram::create([
            'name' => 'Guía Oficial de Turismo',
            'slug' => 'guia-oficial-de-turismo',
            'description' => 'Servicios turísticos y guiado profesional.',
            'details' => 'Duración: 3 años',
            'is_active' => true,
        ]);

        $img1 = $program->images()->create([
            'path' => 'programs/gallery/turismo1.jpg',
            'is_main' => true,
        ]);
        $img2 = $program->images()->create([
            'path' => 'programs/gallery/turismo2.jpg',
            'is_main' => false,
        ]);

        $response = $this->get(route('programas-de-estudio.detalle', $program->slug));

        $response->assertOk();
        $response->assertSee('Álbum de Fotos & Galería', false);
        $response->assertSee($img1->path);
        $response->assertSee($img2->path);
    }
}
