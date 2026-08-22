<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransparencyPublicTest extends TestCase
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
            'complaints_book_path' => 'enterprise/pdf/libro-reclamaciones-test.pdf',
        ]);
    }

    public function test_public_user_can_access_complaints_book_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/transparencia/libro-de-reclamaciones');

        $response->assertOk();
        $response->assertSee('Libro de Reclamaciones');
        $response->assertSee('Formulario de Reclamos y Quejas');
        $response->assertSee('Identificación del Consumidor Reclamante');
        $response->assertSee('Descargar Libro de Reclamaciones');
        
        // Assert SEO tags are present
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<script type="application/ld+json">', false);
    }
}
