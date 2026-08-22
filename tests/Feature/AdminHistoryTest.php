<?php

namespace Tests\Feature;

use App\Models\HistoricalReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_history(): void
    {
        $response = $this->get('/admin-historia');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_admin_history_index(): void
    {
        $user = User::factory()->create();
        HistoricalReview::create([
            'title'       => 'Etapa de Prueba 1991',
            'description' => 'Descripción de prueba.',
            'start_year'  => 1991,
            'end_year'    => 2000,
            'order'       => 1,
            'is_active'   => true,
        ]);

        $response = $this->actingAs($user)->get('/admin-historia');
        
        $response->assertOk();
        $response->assertSee('Historia Institucional');
        $response->assertSee('Etapa de Prueba 1991');
        $response->assertSee('1991 - 2000');
    }

    public function test_admin_can_access_create_history_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin-historia/crear');
        
        $response->assertOk();
        $response->assertSee('Registrar Hito Histórico');
    }

    public function test_admin_can_store_new_history_with_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('hitorico.jpg', 800, 600);

        $response = $this->actingAs($user)->post('/admin-historia/guardar', [
            'title'       => 'Creación del IESTP',
            'description' => 'Historia detallada sobre la creación.',
            'start_year'  => 1991,
            'end_year'    => 2000,
            'order'       => 1,
            'is_active'   => 1,
            'image'       => $image,
        ]);

        $response->assertRedirect('/admin-historia');
        $this->assertDatabaseHas('historical_reviews', [
            'title'      => 'Creación del IESTP',
            'start_year' => 1991,
            'end_year'   => 2000,
            'is_active'  => true,
        ]);

        $history = HistoricalReview::where('title', 'Creación del IESTP')->first();
        $this->assertNotNull($history->image_path);
        Storage::disk('public')->assertExists($history->image_path);
    }

    public function test_admin_can_access_edit_history_page(): void
    {
        $user = User::factory()->create();
        $history = HistoricalReview::create([
            'title'       => 'Hito para editar',
            'description' => 'Texto original.',
            'start_year'  => 2005,
            'order'       => 2,
            'is_active'   => true,
        ]);

        $response = $this->actingAs($user)->get("/admin-historia/editar/{$history->id}");
        
        $response->assertOk();
        $response->assertSee('Editar Hito Histórico');
        $response->assertSee('Hito para editar');
    }

    public function test_admin_can_update_history(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $history = HistoricalReview::create([
            'title'       => 'Hito Inicial',
            'description' => 'Descripción vieja.',
            'start_year'  => 2000,
            'order'       => 1,
            'is_active'   => true,
        ]);

        $newImage = UploadedFile::fake()->image('nueva_foto.png');

        $response = $this->actingAs($user)->put("/admin-historia/editar/{$history->id}", [
            'title'       => 'Hito Actualizado',
            'description' => 'Descripción nueva.',
            'start_year'  => 2001,
            'end_year'    => 2015,
            'order'       => 2,
            'is_active'   => 1,
            'image'       => $newImage,
        ]);

        $response->assertRedirect('/admin-historia');
        $this->assertDatabaseHas('historical_reviews', [
            'id'          => $history->id,
            'title'       => 'Hito Actualizado',
            'description' => 'Descripción nueva.',
            'start_year'  => 2001,
            'end_year'    => 2015,
        ]);

        $history->refresh();
        $this->assertNotNull($history->image_path);
        Storage::disk('public')->assertExists($history->image_path);
    }

    public function test_admin_can_toggle_history_status(): void
    {
        $user = User::factory()->create();
        $history = HistoricalReview::create([
            'title'       => 'Hito Toggle',
            'description' => 'Prueba toggle.',
            'start_year'  => 2010,
            'is_active'   => true,
        ]);

        $response = $this->actingAs($user)->patchJson("/admin-historia/estado/{$history->id}");

        $response->assertOk();
        $response->assertJson(['success' => true, 'is_active' => false]);
        $this->assertFalse($history->fresh()->is_active);
    }

    public function test_admin_can_delete_history(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('foto_a_borrar.jpg');
        $path = $image->store('history', 'public');

        $history = HistoricalReview::create([
            'title'       => 'Hito a Eliminar',
            'description' => 'Texto a eliminar.',
            'image_path'  => $path,
            'is_active'   => true,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($user)->delete("/admin-historia/{$history->id}");

        $response->assertRedirect('/admin-historia');
        $this->assertDatabaseMissing('historical_reviews', ['id' => $history->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
