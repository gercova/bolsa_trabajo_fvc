<?php

namespace Tests\Feature;

use App\Models\ExternalInstitutionalLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminLinkTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::factory()->create();
    }

    public function test_admin_can_view_links_index(): void
    {
        $admin = $this->createAdminUser();
        ExternalInstitutionalLink::create([
            'name' => 'Registra Minedu',
            'link' => 'https://registra.minedu.gob.pe',
            'icon' => 'bi-pencil-square',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.links.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Links/Index')
            ->has('links.data', 1)
            ->where('totalLinks', 1)
            ->where('activeLinks', 1)
            ->where('inactiveLinks', 0)
        );
    }

    public function test_admin_can_view_links_create_page(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.links.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Links/Create')
        );
    }

    public function test_admin_can_store_institutional_link(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.links.store'), [
            'name' => 'Titula Certificados',
            'link' => 'https://titula.minedu.gob.pe',
            'icon' => 'bi-award',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.links.index'));
        $this->assertDatabaseHas('external_institutional_links', [
            'name' => 'Titula Certificados',
            'link' => 'https://titula.minedu.gob.pe',
            'icon' => 'bi-award',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_link_edit_page(): void
    {
        $admin = $this->createAdminUser();
        $link = ExternalInstitutionalLink::create([
            'name' => 'Conecta Alumnos',
            'link' => 'https://conecta.fvc.edu.pe',
            'icon' => 'bi-people',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.links.edit', $link));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Links/Edit')
            ->where('link.name', 'Conecta Alumnos')
            ->where('link.id', $link->id)
        );
    }

    public function test_admin_can_update_institutional_link(): void
    {
        $admin = $this->createAdminUser();
        $link = ExternalInstitutionalLink::create([
            'name' => 'Original Name',
            'link' => 'https://original.com',
            'icon' => 'bi-box-arrow-up-right',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.links.update', $link), [
            'name' => 'Updated Name',
            'link' => 'https://updated.com',
            'icon' => 'bi-globe',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.links.index'));
        $this->assertDatabaseHas('external_institutional_links', [
            'id' => $link->id,
            'name' => 'Updated Name',
            'link' => 'https://updated.com',
            'icon' => 'bi-globe',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_toggle_link_status(): void
    {
        $admin = $this->createAdminUser();
        $link = ExternalInstitutionalLink::create([
            'name' => 'Toggle Link',
            'link' => 'https://toggle.com',
            'icon' => 'bi-link',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.links.toggle-status', $link));

        $response->assertRedirect();
        $this->assertFalse($link->fresh()->is_active);
    }

    public function test_admin_can_delete_institutional_link(): void
    {
        $admin = $this->createAdminUser();
        $link = ExternalInstitutionalLink::create([
            'name' => 'Delete Link',
            'link' => 'https://delete.com',
            'icon' => 'bi-trash',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.links.destroy', $link));

        $response->assertRedirect(route('admin.links.index'));
        $this->assertDatabaseMissing('external_institutional_links', [
            'id' => $link->id,
        ]);
    }
}
