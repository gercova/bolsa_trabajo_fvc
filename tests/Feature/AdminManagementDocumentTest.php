<?php

namespace Tests\Feature;

use App\Models\ManagementDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminManagementDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_documents(): void
    {
        $response = $this->get('/admin-documentos');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_documents_index(): void
    {
        $user = User::factory()->create();

        ManagementDocument::create([
            'title' => 'Reglamento Interno de Trabajo 2026',
            'description' => 'Normativa interna para personal',
            'details' => 'Aprobado por RD N° 015-2026',
            'validity_period' => '2026-12-31',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin-documentos');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Documents/Index')
            ->has('documents')
            ->has('filters')
        );
    }

    public function test_admin_can_access_admin_documents_create(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin-documentos/crear');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Documents/Create')
        );
    }

    public function test_admin_can_store_management_document(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('rit-2026.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($user)->post('/admin-documentos/guardar', [
            'title' => 'Reglamento Interno Oficial 2026',
            'description' => 'Documento de gestión anual',
            'details' => 'Resolución Directoral N° 020-2026',
            'file_path' => $file,
            'validity_period' => '2026-12-31',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.documents.index'));
        $this->assertDatabaseHas('management_documents', [
            'title' => 'Reglamento Interno Oficial 2026',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_admin_documents_edit(): void
    {
        $user = User::factory()->create();

        $doc = ManagementDocument::create([
            'title' => 'PEI 2024-2027',
            'description' => 'Plan Estratégico Institucional',
            'validity_period' => '2027-12-31',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get("/admin-documentos/editar/{$doc->id}");

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Documents/Edit')
            ->has('managementDocument')
            ->where('managementDocument.id', $doc->id)
            ->where('managementDocument.title', 'PEI 2024-2027')
        );
    }

    public function test_admin_can_update_management_document(): void
    {
        $user = User::factory()->create();

        $doc = ManagementDocument::create([
            'title' => 'POI 2025 Original',
            'description' => 'Plan Operativo Institucional',
            'validity_period' => '2025-12-31',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put("/admin-documentos/editar/{$doc->id}", [
            'title' => 'POI 2026 Actualizado',
            'description' => 'Plan Operativo Institucional Actualizado',
            'validity_period' => '2026-12-31',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.documents.index'));
        $this->assertDatabaseHas('management_documents', [
            'id' => $doc->id,
            'title' => 'POI 2026 Actualizado',
        ]);
    }

    public function test_admin_can_toggle_management_document_status(): void
    {
        $user = User::factory()->create();

        $doc = ManagementDocument::create([
            'title' => 'Documento para Toggle',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->patch("/admin-documentos/estado/{$doc->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('management_documents', [
            'id' => $doc->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_management_document(): void
    {
        $user = User::factory()->create();

        $doc = ManagementDocument::create([
            'title' => 'Documento Temporal Para Eliminar',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete("/admin-documentos/{$doc->id}");

        $response->assertRedirect(route('admin.documents.index'));
        $this->assertDatabaseMissing('management_documents', [
            'id' => $doc->id,
        ]);
    }
}
