<?php

namespace Tests\Feature;

use App\Models\DocumentType;
use App\Models\LicensingPhase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminLicensingTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdminUser(): User
    {
        $docType = DocumentType::firstOrCreate(
            ['id' => 1],
            ['name' => 'DNI', 'abbreviation' => 'DNI', 'length' => 8]
        );

        return User::firstOrCreate(
            ['email' => 'admin_licensing_test@example.com'],
            [
                'document_type_id' => $docType->id,
                'dni'              => '99887766',
                'names'            => 'Admin Test Licensing',
                'role'             => 'Admin',
                'password'         => bcrypt('password'),
                'is_active'        => true,
            ]
        );
    }

    public function test_guest_cannot_access_admin_licensing(): void
    {
        $response = $this->get('/admin-licenciamiento');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_access_admin_licensing_index(): void
    {
        $admin = $this->createAdminUser();

        LicensingPhase::create([
            'phase_number'        => 1,
            'title'               => 'Documentos de Gestión de las 7 CBC',
            'subtitle'            => 'Subtítulo de prueba',
            'code'                => 'CBC-01',
            'stage_tag'           => 'P',
            'status'              => 'in_progress',
            'is_current'          => true,
            'progress_percentage' => 85,
            'description'         => 'Descripción de prueba para licenciamiento.',
            'order'               => 1,
            'is_active'           => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin-licenciamiento');

        $response->assertOk();
        $response->assertSee('Licenciamiento');
        $response->assertSee('Documentos de Gestión de las 7 CBC');
        $response->assertSee('CBC-01');
    }

    public function test_admin_can_access_create_phase_page(): void
    {
        $admin = $this->createAdminUser();
        $response = $this->actingAs($admin)->get('/admin-licenciamiento/crear');

        $response->assertOk();
        $response->assertSee('Registrar Fase de Licenciamiento');
    }

    public function test_admin_can_store_new_phase(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->post('/admin-licenciamiento/guardar', [
            'phase_number'        => 6,
            'title'               => 'Fase de Evaluación Complementaria',
            'subtitle'            => 'Evaluación de impacto institucional',
            'code'                => 'EVA-06',
            'stage_tag'           => 'PTE',
            'status'              => 'pending',
            'is_current'          => 0,
            'progress_percentage' => 10,
            'description'         => 'Detalle de la fase creada mediante test.',
            'resolution_number'   => 'R.D. N° 100-2026',
            'legal_basis'         => 'Ley N° 30512',
            'order'               => 6,
            'is_active'           => 1,
        ]);

        $response->assertRedirect('/admin-licenciamiento');
        $this->assertDatabaseHas('licensing_phases', [
            'phase_number' => 6,
            'title'        => 'Fase de Evaluación Complementaria',
            'code'         => 'EVA-06',
            'status'       => 'pending',
        ]);
    }

    public function test_admin_can_access_edit_phase_page(): void
    {
        $admin = $this->createAdminUser();
        $phase = LicensingPhase::create([
            'phase_number'        => 2,
            'title'               => 'Presentación y Registro',
            'code'                => 'REG-02',
            'status'              => 'pending',
            'is_current'          => false,
            'progress_percentage' => 0,
            'order'               => 2,
            'is_active'           => true,
        ]);

        $response = $this->actingAs($admin)->get("/admin-licenciamiento/editar/{$phase->id}");

        $response->assertOk();
        $response->assertSee('Editar Fase');
        $response->assertSee('Presentación y Registro');
    }

    public function test_admin_can_update_phase_and_status(): void
    {
        $admin = $this->createAdminUser();
        $phase = LicensingPhase::create([
            'phase_number'        => 2,
            'title'               => 'Fase Inicial Para Actualizar',
            'code'                => 'REG-02',
            'status'              => 'pending',
            'is_current'          => false,
            'progress_percentage' => 0,
            'order'               => 2,
            'is_active'           => true,
        ]);

        $response = $this->actingAs($admin)->put("/admin-licenciamiento/editar/{$phase->id}", [
            'phase_number'        => 2,
            'title'               => 'Fase Modificada y En Proceso',
            'code'                => 'REG-02-MOD',
            'status'              => 'in_progress',
            'is_current'          => 1,
            'progress_percentage' => 50,
            'description'         => 'Descripción actualizada correctamente.',
            'order'               => 2,
            'is_active'           => 1,
        ]);

        $response->assertRedirect('/admin-licenciamiento');
        $this->assertDatabaseHas('licensing_phases', [
            'id'                  => $phase->id,
            'title'               => 'Fase Modificada y En Proceso',
            'status'              => 'in_progress',
            'is_current'          => true,
            'progress_percentage' => 50,
        ]);
    }

    public function test_admin_can_toggle_phase_status(): void
    {
        $admin = $this->createAdminUser();
        $phase = LicensingPhase::create([
            'phase_number' => 3,
            'title'        => 'Fase para Toggle',
            'status'       => 'pending',
            'is_active'    => true,
            'order'        => 3,
        ]);

        $response = $this->actingAs($admin)->patchJson("/admin-licenciamiento/estado/{$phase->id}");

        $response->assertOk();
        $response->assertJson(['success' => true, 'is_active' => false]);
        $this->assertFalse($phase->fresh()->is_active);
    }

    public function test_admin_can_set_current_stage(): void
    {
        $admin = $this->createAdminUser();
        $phase1 = LicensingPhase::create([
            'phase_number' => 1,
            'title'        => 'Fase 1 Actual',
            'status'       => 'in_progress',
            'is_current'   => true,
            'order'        => 1,
        ]);

        $phase2 = LicensingPhase::create([
            'phase_number' => 2,
            'title'        => 'Fase 2 Siguiente',
            'status'       => 'pending',
            'is_current'   => false,
            'order'        => 2,
        ]);

        $response = $this->actingAs($admin)->patchJson("/admin-licenciamiento/etapa-actual/{$phase2->id}");

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertFalse($phase1->fresh()->is_current);
        $this->assertTrue($phase2->fresh()->is_current);
        $this->assertEquals('in_progress', $phase2->fresh()->status);
    }

    public function test_admin_can_delete_phase(): void
    {
        $admin = $this->createAdminUser();
        $phase = LicensingPhase::create([
            'phase_number' => 10,
            'title'        => 'Fase a Eliminar',
            'status'       => 'pending',
            'order'        => 10,
            'is_active'    => true,
        ]);

        $response = $this->actingAs($admin)->delete("/admin-licenciamiento/{$phase->id}");

        $response->assertRedirect('/admin-licenciamiento');
        $this->assertDatabaseMissing('licensing_phases', ['id' => $phase->id]);
    }
}
