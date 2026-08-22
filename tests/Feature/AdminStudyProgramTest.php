<?php

namespace Tests\Feature;

use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $response->assertViewHas('programs');
    }

    public function test_admin_can_access_create_program_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin-programas/crear-programa');
        
        $response->assertOk();
        $response->assertSee('Nuevo Programa de Estudio');
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

        $response->assertRedirect('/admin-programas');
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
        $response->assertSee('Editar Programa: Agropecuaria');
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

        $response->assertRedirect('/admin-programas');
        $response->assertSessionHas('success');

        $program->refresh();
        $this->assertEquals('Agropecuaria Editada', $program->name);
        $this->assertEquals('agropecuaria-editada', $program->slug);
        $this->assertEquals('Nueva descripción', $program->description);
        $this->assertEquals('Nuevo detalle', $program->details);
        $this->assertFalse($program->is_active);
        
        Storage::disk('public')->assertExists($program->logo_path);
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

        $response->assertRedirect('/admin-programas');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('study_programs', [
            'id' => $program->id,
        ]);

        Storage::disk('public')->assertMissing($logoPath);
    }
}
