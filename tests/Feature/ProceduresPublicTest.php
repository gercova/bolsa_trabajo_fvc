<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProceduresPublicTest extends TestCase
{
    use RefreshDatabase;

    private function createEnterprise(): void
    {
        Enterprise::create([
            'ruc' => '20123456789',
            'company_name' => 'Test Enterprise',
            'trade_name' => 'Test Trade',
            'legal_representative_dni' => '12345678',
            'legal_representative' => 'John Doe',
            'email' => 'test@example.com',
            'phone_number_1' => '+51 987654321',
            'whatsapp_link' => 'https://wa.me/51987654321',
            'address' => 'Av. de prueba 123',
            'city' => 'Trujillo',
            'color' => 'bg-blue-500',
        ]);
    }

    public function test_public_user_can_access_parts_table_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/tramites/mesa-de-partes');

        $response->assertOk();
        $response->assertSee('Mesa de Partes Virtual');
        $response->assertSee('Formulario de Envío Digital');
        $response->assertSee('Datos del Solicitante');
        
        // Assert SEO tags are present in the response
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<script type="application/ld+json">', false);
    }
}
