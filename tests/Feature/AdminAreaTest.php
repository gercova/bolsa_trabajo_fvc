<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminAreaTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::factory()->create();
    }

    public function test_admin_can_view_areas_index(): void
    {
        $admin = $this->createAdminUser();
        $program = StudyProgram::create([
            'name' => 'Desarrollo de Sistemas',
            'slug' => 'desarrollo-sistemas',
        ]);

        Area::create([
            'name' => 'Unidad Académica',
            'program_id' => $program->id,
            'user_id' => $admin->id,
            'description' => 'Área encargada de los planes curriculares',
            'details' => 'Detalles de funciones académicas',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.areas.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Areas/Index')
            ->has('areas.data', 1)
            ->where('totalAreas', 1)
            ->where('withProgramCount', 1)
            ->where('withLeaderCount', 1)
            ->where('generalAreasCount', 0)
        );
    }

    public function test_admin_can_view_areas_create_page(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.areas.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Areas/Create')
            ->has('programs')
            ->has('users')
        );
    }

    public function test_admin_can_store_institutional_area(): void
    {
        $admin = $this->createAdminUser();
        $program = StudyProgram::create([
            'name' => 'Enfermería Técnica',
            'slug' => 'enfermeria-tecnica',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.areas.store'), [
            'name' => 'Coordinación de Prácticas',
            'program_id' => $program->id,
            'user_id' => $admin->id,
            'description' => 'Coordinación y supervisión de prácticas pre-profesionales',
            'details' => 'Supervisión en hospitales y centros de salud',
        ]);

        $response->assertRedirect(route('admin.areas.index'));
        $this->assertDatabaseHas('area', [
            'name' => 'Coordinación de Prácticas',
            'program_id' => $program->id,
            'user_id' => $admin->id,
            'description' => 'Coordinación y supervisión de prácticas pre-profesionales',
        ]);
    }

    public function test_admin_can_view_area_edit_page(): void
    {
        $admin = $this->createAdminUser();
        $area = Area::create([
            'name' => 'Secretaría General',
            'description' => 'Trámites y archivo institucional',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.areas.edit', $area));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Areas/Edit')
            ->where('area.id', $area->id)
            ->where('area.name', 'Secretaría General')
            ->has('programs')
            ->has('users')
        );
    }

    public function test_admin_can_update_institutional_area(): void
    {
        $admin = $this->createAdminUser();
        $area = Area::create([
            'name' => 'Área de Bienestar Original',
            'description' => 'Descripción previa',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.areas.update', $area), [
            'name' => 'Área de Bienestar Estudiantil y Empleabilidad',
            'description' => 'Atención psicológica y bolsa de trabajo',
            'details' => 'Horario: 8am - 5pm',
        ]);

        $response->assertRedirect(route('admin.areas.index'));
        $this->assertDatabaseHas('area', [
            'id' => $area->id,
            'name' => 'Área de Bienestar Estudiantil y Empleabilidad',
            'description' => 'Atención psicológica y bolsa de trabajo',
        ]);
    }

    public function test_admin_can_delete_institutional_area(): void
    {
        $admin = $this->createAdminUser();
        $area = Area::create([
            'name' => 'Área Temporal',
            'description' => 'Para eliminar',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.areas.destroy', $area));

        $response->assertRedirect(route('admin.areas.index'));
        $this->assertDatabaseMissing('area', [
            'id' => $area->id,
        ]);
    }
}
