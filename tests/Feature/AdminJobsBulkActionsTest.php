<?php

namespace Tests\Feature;

use App\Models\DocumentType;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminJobsBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        $docType = DocumentType::firstOrCreate(
            ['id' => 1],
            ['name' => 'DNI', 'abbreviation' => 'DNI', 'length' => 8]
        );

        return User::firstOrCreate(
            ['email' => 'admin_jobs_test@example.com'],
            [
                'document_type_id' => $docType->id,
                'dni'              => '99887766',
                'names'            => 'Admin Test Jobs',
                'role'             => 'Admin',
                'password'         => bcrypt('password'),
                'is_active'        => true,
            ]
        );
    }

    public function test_guest_cannot_bulk_delete_or_clear_all(): void
    {
        $response1 = $this->postJson('/admin-trabajos/eliminar-masivo', ['ids' => [1, 2]]);
        $response1->assertUnauthorized();

        $response2 = $this->deleteJson('/admin-trabajos/vaciar-tabla');
        $response2->assertUnauthorized();
    }

    public function test_view_contains_bulk_delete_and_clear_table_elements(): void
    {
        $user = $this->createAdminUser();

        // Create 15 job offers so pagination (10 per page) has 2 pages
        for ($i = 1; $i <= 15; $i++) {
            JobOffer::create([
                'title'       => "Oferta de Prueba {$i}",
                'company'     => "Empresa {$i}",
                'location'    => 'Lima, Perú',
                'description' => 'Descripción de la oferta laboral.',
                'url'         => 'https://example.com',
                'source'      => 'Bolsa Institucional',
                'is_active'   => true,
            ]);
        }

        $response = $this->actingAs($user)->get('/admin-trabajos');

        $response->assertOk();
        $response->assertSee('btn-clear-table');
        $response->assertSee('bulk-action-bar');
        $response->assertSee('selectAllPageCheckbox');
        $response->assertSee('job-checkbox');
        $response->assertSee('select-all-pages-banner');
        $response->assertSee('Vaciar Tabla');
        $response->assertSee('Eliminar Seleccionados');
    }

    public function test_bulk_delete_with_specific_ids(): void
    {
        $user = $this->createAdminUser();

        $job1 = JobOffer::create([
            'title' => 'Técnico en Redes',
            'company' => 'Tech Corp',
            'location' => 'Lima',
            'description' => 'Desc',
            'url' => 'https://example.com',
            'source' => 'Computrabajo',
            'is_active' => true,
        ]);

        $job2 = JobOffer::create([
            'title' => 'Desarrollador Web',
            'company' => 'Dev Studio',
            'location' => 'Remoto',
            'description' => 'Desc',
            'url' => 'https://example.com',
            'source' => 'Bumeran',
            'is_active' => true,
        ]);

        $job3 = JobOffer::create([
            'title' => 'Contador General',
            'company' => 'Finanzas SA',
            'location' => 'Arequipa',
            'description' => 'Desc',
            'url' => 'https://example.com',
            'source' => 'Bolsa Institucional',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/admin-trabajos/eliminar-masivo', [
            'ids' => [$job1->id, $job2->id],
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'count'   => 2,
        ]);

        $this->assertDatabaseMissing('job_offers', ['id' => $job1->id]);
        $this->assertDatabaseMissing('job_offers', ['id' => $job2->id]);
        $this->assertDatabaseHas('job_offers', ['id' => $job3->id]);
    }

    public function test_bulk_delete_validation_fails_on_empty_ids(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->postJson('/admin-trabajos/eliminar-masivo', [
            'ids' => [],
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_bulk_delete_all_matching_records_across_pages(): void
    {
        $user = $this->createAdminUser();

        // Create active offers matching search
        for ($i = 1; $i <= 12; $i++) {
            JobOffer::create([
                'title'       => "Especialista en Computación {$i}",
                'company'     => "Empresa IT {$i}",
                'location'    => 'Lima',
                'description' => 'Soporte y desarrollo',
                'url'         => 'https://example.com',
                'source'      => 'Computrabajo',
                'is_active'   => true,
            ]);
        }

        // Create an offer that does NOT match the search
        $differentJob = JobOffer::create([
            'title'       => 'Chef de Cocina',
            'company'     => 'Restaurante Gourmet',
            'location'    => 'Cusco',
            'description' => 'Cocina internacional',
            'url'         => 'https://example.com',
            'source'      => 'Bumeran',
            'is_active'   => true,
        ]);

        $response = $this->actingAs($user)->postJson('/admin-trabajos/eliminar-masivo', [
            'select_all' => true,
            'search'     => 'Computación',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'count'   => 12,
        ]);

        $this->assertDatabaseCount('job_offers', 1);
        $this->assertDatabaseHas('job_offers', ['id' => $differentJob->id]);
    }

    public function test_clear_all_deletes_all_records_from_table(): void
    {
        $user = $this->createAdminUser();

        for ($i = 1; $i <= 20; $i++) {
            JobOffer::create([
                'title'       => "Oferta {$i}",
                'company'     => "Empresa {$i}",
                'location'    => 'Lima',
                'description' => 'Trabajo técnico',
                'url'         => 'https://example.com',
                'source'      => 'Bolsa Institucional',
                'is_active'   => true,
            ]);
        }

        $this->assertDatabaseCount('job_offers', 20);

        $response = $this->actingAs($user)->deleteJson('/admin-trabajos/vaciar-tabla');

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'count'   => 20,
        ]);

        $this->assertDatabaseCount('job_offers', 0);
    }

    public function test_clear_all_handles_empty_table_gracefully(): void
    {
        $user = $this->createAdminUser();

        $this->assertDatabaseCount('job_offers', 0);

        $response = $this->actingAs($user)->deleteJson('/admin-trabajos/vaciar-tabla');

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'count'   => 0,
        ]);
    }
}
