<?php

namespace Tests\Feature;

use App\Models\InstitutionalCarousel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminCarouselTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::factory()->create();
    }

    public function test_admin_can_view_carousel_index_page(): void
    {
        $admin = $this->createAdminUser();
        InstitutionalCarousel::create([
            'title' => 'Test Slide 1',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.carousel.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Carousel/Index')
            ->has('carousels.data', 1)
        );
    }

    public function test_admin_can_view_carousel_create_page(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.carousel.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Carousel/Create')
            ->has('nextOrder')
        );
    }

    public function test_admin_can_store_carousel_slide_with_image(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();

        $image = UploadedFile::fake()->image('slide.jpg', 1920, 800);

        $response = $this->actingAs($admin)->post(route('admin.carousel.store'), [
            'title' => 'Convocatoria Admisión',
            'highlight_text' => '2026-I',
            'tag' => 'Admisión',
            'tag_color' => 'amber',
            'order' => 1,
            'is_active' => '1',
            'image' => $image,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.carousel.index'));

        $this->assertDatabaseHas('institutional_carousels', [
            'title' => 'Convocatoria Admisión',
            'highlight_text' => '2026-I',
            'is_active' => true,
        ]);

        $carousel = InstitutionalCarousel::first();
        $this->assertNotNull($carousel->image);
        Storage::disk('public')->assertExists($carousel->image->path);
    }

    public function test_admin_can_view_carousel_edit_page(): void
    {
        $admin = $this->createAdminUser();
        $carousel = InstitutionalCarousel::create([
            'title' => 'Edit Me Slide',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.carousel.edit', $carousel));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Carousel/Edit')
            ->where('carousel.title', 'Edit Me Slide')
        );
    }

    public function test_admin_can_toggle_carousel_status(): void
    {
        $admin = $this->createAdminUser();
        $carousel = InstitutionalCarousel::create([
            'title' => 'Toggle Status Slide',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.carousel.toggle-status', $carousel));

        $response->assertSessionHasNoErrors();
        $this->assertFalse($carousel->fresh()->is_active);
    }
}
