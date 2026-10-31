<?php

namespace Tests\Feature;

use App\Models\Tupa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminTupaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_tupa(): void
    {
        $response = $this->get('/admin-tupa');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_tupa_index(): void
    {
        $user = User::factory()->create();

        Tupa::create([
            'title' => 'TUPA Institucional 2026',
            'description' => 'Texto Único de Procedimientos Administrativos',
            'file_path' => 'tupa/tupa-2026.pdf',
            'effective_start_date' => '2026-01-01',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin-tupa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Tupa/Index')
            ->has('tupas')
            ->has('categories')
            ->has('procedures')
            ->has('activeTab')
        );
    }

    public function test_admin_can_access_admin_tupa_create(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin-tupa/crear-tupa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Tupa/Create')
        );
    }

    public function test_admin_can_store_tupa_document(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('tupa-2026.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($user)->post('/admin-tupa/guardar', [
            'title' => 'TUPA 2026 Oficial',
            'description' => 'Documento oficial con tasas',
            'file_path' => $file,
            'effective_start_date' => '2026-01-01',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.tupa.index', ['tab' => 'documents']));
        $this->assertDatabaseHas('tupa', [
            'title' => 'TUPA 2026 Oficial',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_admin_tupa_edit(): void
    {
        $user = User::factory()->create();

        $tupa = Tupa::create([
            'title' => 'TUPA 2025 Original',
            'description' => 'Versión anterior',
            'file_path' => 'tupa/tupa-2025.pdf',
            'effective_start_date' => '2025-01-01',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get("/admin-tupa/editar-tupa/{$tupa->id}");

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Tupa/Edit')
            ->has('tupa')
            ->where('tupa.id', $tupa->id)
            ->where('tupa.title', 'TUPA 2025 Original')
        );
    }

    public function test_admin_can_update_tupa_document(): void
    {
        $user = User::factory()->create();

        $tupa = Tupa::create([
            'title' => 'TUPA Original',
            'description' => 'Descripción previa',
            'file_path' => 'tupa/tupa-original.pdf',
            'effective_start_date' => '2025-01-01',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put("/admin-tupa/editar-tupa/{$tupa->id}", [
            'title' => 'TUPA Actualizado',
            'description' => 'Descripción nueva',
            'effective_start_date' => '2026-01-01',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.tupa.index', ['tab' => 'documents']));
        $this->assertDatabaseHas('tupa', [
            'id' => $tupa->id,
            'title' => 'TUPA Actualizado',
        ]);
    }

    public function test_admin_can_toggle_tupa_status(): void
    {
        $user = User::factory()->create();

        $tupa = Tupa::create([
            'title' => 'TUPA Toggle Test',
            'description' => 'Prueba de estado',
            'file_path' => 'tupa/tupa-toggle.pdf',
            'effective_start_date' => '2026-01-01',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->patch("/admin-tupa/estado/{$tupa->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('tupa', [
            'id' => $tupa->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_tupa_document(): void
    {
        $user = User::factory()->create();

        $tupa = Tupa::create([
            'title' => 'TUPA Para Eliminar',
            'description' => 'Temporal',
            'file_path' => 'tupa/tupa-delete.pdf',
            'effective_start_date' => '2026-01-01',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete("/admin-tupa/{$tupa->id}");

        $response->assertRedirect(route('admin.tupa.index', ['tab' => 'documents']));
        $this->assertDatabaseMissing('tupa', [
            'id' => $tupa->id,
        ]);
    }
}
