<?php

namespace Tests\Feature;

use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminStudyProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_programs(): void
    {
        $response = $this->get('/admin-programas');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_admin_programs_index(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin-programas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Programs/Index')
            ->has('programs')
        );
    }

    public function test_admin_can_access_create_program_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin-programas/crear-programa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Programs/Create')
            ->has('nextOrder')
        );
    }

    public function test_admin_can_store_new_program(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $logo = UploadedFile::fake()->image('logo.png');

        $response = $this->actingAs($user)->post('/admin-programas/guardar', [
            'name' => 'Computación e Informática',
            'description' => 'Descripción del programa de computación e informática',
            'details' => 'Duración: 3 años',
            'logo_path' => $logo,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin-programas?tab=programs');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('study_programs', [
            'name' => 'Computación e Informática',
            'slug' => 'computacion-e-informatica',
            'description' => 'Descripción del programa de computación e informática',
            'details' => 'Duración: 3 años',
            'is_active' => true,
        ]);

        $program = StudyProgram::where('name', 'Computación e Informática')->first();
        $this->assertNotNull($program->logo_path);
        Storage::disk('public')->assertExists($program->logo_path);
    }

    public function test_store_program_validation(): void
    {
        $user = User::factory()->create();

        // Missing required fields
        $response = $this->actingAs($user)->post('/admin-programas/guardar', [
            'name' => '',
            'description' => '',
            'details' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'description', 'details']);
    }

    public function test_admin_can_access_edit_program_page(): void
    {
        $user = User::factory()->create();
        $program = StudyProgram::create([
            'name' => 'Agropecuaria',
            'slug' => 'agropecuaria',
            'description' => 'Prueba',
            'details' => 'Detalle',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get("/admin-programas/editar-programa/{$program->id}");

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Programs/Edit')
            ->where('program.id', $program->id)
            ->where('program.name', 'Agropecuaria')
        );
    }

    public function test_admin_can_update_program(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $program = StudyProgram::create([
            'name' => 'Agropecuaria',
            'slug' => 'agropecuaria',
            'description' => 'Prueba',
            'details' => 'Detalle',
            'is_active' => true,
        ]);

        $newLogo = UploadedFile::fake()->image('new-logo.png');

        $response = $this->actingAs($user)->put("/admin-programas/editar-programa/{$program->id}", [
            'name' => 'Agropecuaria Editada',
            'description' => 'Nueva descripción',
            'details' => 'Nuevo detalle',
            'logo_path' => $newLogo,
            'is_active' => '0',
        ]);

        $response->assertRedirect('/admin-programas?tab=programs');
        $response->assertSessionHas('success');

        $program->refresh();
        $this->assertEquals('Agropecuaria Editada', $program->name);
        $this->assertEquals('agropecuaria-editada', $program->slug);
        $this->assertEquals('Nueva descripción', $program->description);
        $this->assertEquals('Nuevo detalle', $program->details);
        $this->assertFalse($program->is_active);

        Storage::disk('public')->assertExists($program->logo_path);
    }

    public function test_updating_program_without_new_files_preserves_existing_logo_and_itinerary(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        Storage::disk('public')->put('programs/initial-logo.png', 'logo content');
        Storage::disk('public')->put('programs/documents/itinerary.pdf', 'pdf content');

        $program = StudyProgram::create([
            'name' => 'Enfermería Técnica',
            'slug' => 'enfermeria-tecnica',
            'description' => 'Descripción inicial',
            'details' => 'Detalle inicial',
            'logo_path' => 'programs/initial-logo.png',
            'training_itinerary_path' => 'programs/documents/itinerary.pdf',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put("/admin-programas/editar-programa/{$program->id}", [
            'name' => 'Enfermería Técnica Actualizada',
            'description' => 'Descripción modificada',
            'details' => 'Detalle modificado',
            'logo_path' => null,
            'training_itinerary_path' => null,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin-programas?tab=programs');
        $response->assertSessionHas('success');

        $program->refresh();
        $this->assertEquals('Enfermería Técnica Actualizada', $program->name);
        $this->assertEquals('programs/initial-logo.png', $program->logo_path);
        $this->assertEquals('programs/documents/itinerary.pdf', $program->training_itinerary_path);
        Storage::disk('public')->assertExists('programs/initial-logo.png');
        Storage::disk('public')->assertExists('programs/documents/itinerary.pdf');
    }

    public function test_admin_can_explicitly_remove_logo_and_itinerary(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        Storage::disk('public')->put('programs/initial-logo.png', 'logo content');
        Storage::disk('public')->put('programs/documents/itinerary.pdf', 'pdf content');

        $program = StudyProgram::create([
            'name' => 'Producción Agropecuaria',
            'slug' => 'produccion-agropecuaria',
            'description' => 'Descripción inicial',
            'details' => 'Detalle inicial',
            'logo_path' => 'programs/initial-logo.png',
            'training_itinerary_path' => 'programs/documents/itinerary.pdf',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put("/admin-programas/editar-programa/{$program->id}", [
            'name' => 'Producción Agropecuaria',
            'description' => 'Descripción inicial',
            'details' => 'Detalle inicial',
            'remove_logo' => true,
            'remove_training_itinerary' => true,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin-programas?tab=programs');

        $program->refresh();
        $this->assertNull($program->logo_path);
        $this->assertNull($program->training_itinerary_path);
        Storage::disk('public')->assertMissing('programs/initial-logo.png');
        Storage::disk('public')->assertMissing('programs/documents/itinerary.pdf');
    }

    public function test_admin_can_toggle_program_status(): void
    {
        $user = User::factory()->create();
        $program = StudyProgram::create([
            'name' => 'Agropecuaria',
            'slug' => 'agropecuaria',
            'description' => 'Prueba',
            'details' => 'Detalle',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->patch("/admin-programas/estado/{$program->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $program->refresh();
        $this->assertFalse($program->is_active);
    }

    public function test_admin_can_delete_program(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $logo = UploadedFile::fake()->image('logo.png');
        $logoPath = $logo->store('programs', 'public');

        $program = StudyProgram::create([
            'name' => 'Agropecuaria',
            'slug' => 'agropecuaria',
            'description' => 'Prueba',
            'details' => 'Detalle',
            'logo_path' => $logoPath,
            'is_active' => true,
        ]);

        Storage::disk('public')->assertExists($logoPath);

        $response = $this->actingAs($user)->delete("/admin-programas/{$program->id}");

        $response->assertRedirect('/admin-programas?tab=programs');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('study_programs', [
            'id' => $program->id,
        ]);

        Storage::disk('public')->assertMissing($logoPath);
    }

    public function test_admin_can_store_and_sort_programs_by_order(): void
    {
        $user = User::factory()->create();

        $prog3 = StudyProgram::create([
            'name' => 'Zootecnia',
            'slug' => 'zootecnia',
            'description' => 'Tercer programa',
            'details' => 'Detalle',
            'order' => 3,
            'is_active' => true,
        ]);

        $prog1 = StudyProgram::create([
            'name' => 'Administración',
            'slug' => 'administracion',
            'description' => 'Primer programa',
            'details' => 'Detalle',
            'order' => 1,
            'is_active' => true,
        ]);

        $prog2 = StudyProgram::create([
            'name' => 'Contabilidad',
            'slug' => 'contabilidad',
            'description' => 'Segundo programa',
            'details' => 'Detalle',
            'order' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin-programas');
        $response->assertOk();

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Programs/Index')
            ->where('programs.data.0.name', 'Administración')
            ->where('programs.data.0.order', 1)
            ->where('programs.data.2.name', 'Zootecnia')
            ->where('programs.data.2.order', 3)
        );
    }
}
