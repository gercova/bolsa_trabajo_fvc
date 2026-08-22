<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutUsPublicTest extends TestCase
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
            'phrase' => 'Formando líderes',
            'description' => 'Descripción del instituto de prueba.',
            'mission' => 'Misión institucional de prueba.',
            'vision' => 'Visión institucional de prueba.',
            'principles' => 'Calidad, Transparencia',
            'values' => 'Responsabilidad, Orden',
        ]);
    }

    public function test_public_user_can_access_who_we_are_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/nosotros/quienes-somos');

        $response->assertOk();
        $response->assertSee('¿Quiénes Somos?');
        $response->assertSee('Nuestra Misión');
        $response->assertSee('Nuestra Visión');
        $response->assertSee('Nuestros Valores');
        $response->assertSee('Principios Institucionales');
        $response->assertSee('Datos de la Institución');
        
        // Assert SEO tags are present
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<script type="application/ld+json">', false);
    }
}
