<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SpaErrorPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/_test/400-error', function () {
            abort(400, 'Bad Request Test');
        });

        Route::get('/_test/403-error', function () {
            abort(403, 'Acceso Denegado Test');
        });

        Route::get('/_test/419-error', function () {
            abort(419, 'Page Expired Test');
        });

        Route::get('/_test/500-error', function () {
            abort(500, 'Server Error Test');
        });

        Route::get('/_test/503-error', function () {
            abort(503, 'Service Unavailable Test');
        });
    }

    public function test_direct_404_request_renders_spa_error_component_with_404_status(): void
    {
        $response = $this->get('/non-existent-url-spa-test');

        $response->assertStatus(404);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Error')
            ->where('status', 404)
        );
    }

    public function test_direct_403_request_renders_spa_error_component(): void
    {
        $response = $this->get('/_test/403-error');

        $response->assertStatus(403);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Error')
            ->where('status', 403)
        );
    }

    public function test_inertia_404_request_renders_spa_error_component_with_404_status(): void
    {
        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get('/non-existent-url-spa-test');

        $response->assertStatus(404);
        $response->assertHeader('X-Inertia', 'true');
        $response->assertJson([
            'component' => 'Error',
            'props' => [
                'status' => 404,
            ],
        ]);
    }

    public function test_403_forbidden_renders_spa_error_component(): void
    {
        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get('/_test/403-error');

        $response->assertStatus(403);
        $response->assertHeader('X-Inertia', 'true');
        $response->assertJson([
            'component' => 'Error',
            'props' => [
                'status' => 403,
            ],
        ]);
    }

    public function test_400_bad_request_renders_spa_error_component(): void
    {
        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get('/_test/400-error');

        $response->assertStatus(400);
        $response->assertHeader('X-Inertia', 'true');
        $response->assertJson([
            'component' => 'Error',
            'props' => [
                'status' => 400,
            ],
        ]);
    }

    public function test_419_session_expired_renders_spa_error_component(): void
    {
        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get('/_test/419-error');

        $response->assertStatus(419);
        $response->assertHeader('X-Inertia', 'true');
        $response->assertJson([
            'component' => 'Error',
            'props' => [
                'status' => 419,
            ],
        ]);
    }

    public function test_500_server_error_in_inertia_renders_spa_error_component(): void
    {
        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get('/_test/500-error');

        $response->assertStatus(500);
        $response->assertHeader('X-Inertia', 'true');
        $response->assertJson([
            'component' => 'Error',
            'props' => [
                'status' => 500,
            ],
        ]);
    }

    public function test_503_service_unavailable_renders_spa_error_component(): void
    {
        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get('/_test/503-error');

        $response->assertStatus(503);
        $response->assertHeader('X-Inertia', 'true');
        $response->assertJson([
            'component' => 'Error',
            'props' => [
                'status' => 503,
            ],
        ]);
    }

    public function test_legacy_admin_dashboard_url_redirects_to_canonical_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        // Unauthenticated visits to auth routes redirect to login
        $response->assertRedirect('/login');

        // Authenticated admin user visit redirects to /admin-dashboard
        $admin = User::factory()->create(['role' => 'Administrador']);
        $admin->assignRole('Administrador');

        $authResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $authResponse->assertRedirect('/admin-dashboard');
    }
}
