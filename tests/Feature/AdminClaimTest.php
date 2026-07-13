<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminClaimTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_claims(): void
    {
        $response = $this->get('/admin-reclamos');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_admin_claims_index(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin-reclamos');
        
        $response->assertOk();
        $response->assertViewHas('claims');
    }

    public function test_admin_can_search_claims(): void
    {
        $user = User::factory()->create();
        
        Claim::create([
            'dni' => '11111111',
            'names' => 'Carlos Perez',
            'email' => 'carlos@example.com',
            'subject' => 'Reclamo',
            'message' => 'Disconformidad 1',
        ]);
        
        Claim::create([
            'dni' => '22222222',
            'names' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'subject' => 'Queja',
            'message' => 'Disconformidad 2',
        ]);

        // Search for Carlos
        $response = $this->actingAs($user)->get('/admin-reclamos?search=Carlos');
        $response->assertOk();
        $response->assertSee('Carlos Perez');
        $response->assertDontSee('Maria Gomez');

        // Search for maria@example.com
        $response = $this->actingAs($user)->get('/admin-reclamos?search=maria@example.com');
        $response->assertOk();
        $response->assertSee('Maria Gomez');
        $response->assertDontSee('Carlos Perez');
    }

    public function test_admin_can_filter_claims_by_status(): void
    {
        $user = User::factory()->create();
        
        Claim::create([
            'dni' => '11111111',
            'names' => 'Carlos Perez',
            'email' => 'carlos@example.com',
            'subject' => 'Reclamo',
            'message' => 'Disconformidad 1',
            'status' => 'leido',
        ]);
        
        Claim::create([
            'dni' => '22222222',
            'names' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'subject' => 'Queja',
            'message' => 'Disconformidad 2',
            'status' => 'pendiente',
        ]);

        // Filter by leido
        $response = $this->actingAs($user)->get('/admin-reclamos?status=leido');
        $response->assertOk();
        $response->assertSee('Carlos Perez');
        $response->assertDontSee('Maria Gomez');
    }

    public function test_admin_can_view_claim_details_json(): void
    {
        $user = User::factory()->create();
        $claim = Claim::create([
            'dni' => '11111111',
            'names' => 'Carlos Perez',
            'email' => 'carlos@example.com',
            'subject' => 'Reclamo',
            'message' => 'Disconformidad 1',
        ]);

        $response = $this->actingAs($user)->get("/admin-reclamos/{$claim->id}");
        $response->assertOk();
        $response->assertJson([
            'names' => 'Carlos Perez',
            'dni' => '11111111',
        ]);
    }

    public function test_admin_can_update_claim_status(): void
    {
        $user = User::factory()->create();
        $claim = Claim::create([
            'dni' => '11111111',
            'names' => 'Carlos Perez',
            'email' => 'carlos@example.com',
            'subject' => 'Reclamo',
            'message' => 'Disconformidad 1',
            'status' => 'pendiente',
        ]);

        $response = $this->actingAs($user)->post("/admin-reclamos/estado/{$claim->id}", [
            'status' => 'leido',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $claim->refresh();
        $this->assertEquals('leido', $claim->status);
    }
}
