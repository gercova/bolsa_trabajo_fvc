<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminPartnerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::factory()->create();
    }

    public function test_admin_can_view_partners_index(): void
    {
        $admin = $this->createAdminUser();
        Partner::create([
            'company' => 'Partner ABC',
            'link' => 'https://partnerabc.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.partners.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Partners/Index')
            ->has('partners.data', 1)
        );
    }

    public function test_admin_can_view_partners_create_page(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.partners.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Partners/Create')
        );
    }

    public function test_admin_can_store_partner_with_image(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();
        $image = UploadedFile::fake()->image('logo.png', 400, 400);

        $response = $this->actingAs($admin)->post(route('admin.partners.store'), [
            'company' => 'Empresa Tecnológica',
            'link' => 'https://emtp.com',
            'is_active' => '1',
            'image' => $image,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.partners.index'));

        $this->assertDatabaseHas('partners', [
            'company' => 'Empresa Tecnológica',
            'link' => 'https://emtp.com',
            'is_active' => true,
        ]);

        $partner = Partner::first();
        $this->assertNotNull($partner->image_url);
        Storage::disk('public')->assertExists($partner->image_url);
    }

    public function test_admin_can_view_partner_edit_page(): void
    {
        $admin = $this->createAdminUser();
        $partner = Partner::create([
            'company' => 'Partner Edit',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.partners.edit', $partner));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Partners/Edit')
            ->where('partner.company', 'Partner Edit')
        );
    }

    public function test_admin_can_toggle_partner_status(): void
    {
        $admin = $this->createAdminUser();
        $partner = Partner::create([
            'company' => 'Partner Status',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.partners.toggle-status', $partner));

        $response->assertSessionHasNoErrors();
        $this->assertFalse($partner->fresh()->is_active);
    }

    public function test_admin_can_delete_partner(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();
        $image = UploadedFile::fake()->image('logo.png');
        $path = $image->store('partners', 'public');

        $partner = Partner::create([
            'company' => 'Partner To Delete',
            'image_url' => $path,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.partners.destroy', $partner));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
