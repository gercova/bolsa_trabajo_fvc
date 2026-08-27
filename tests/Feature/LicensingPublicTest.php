<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\LicensingPhase;
use Database\Seeders\LicensingPhaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LicensingPublicTest extends TestCase
{
    use DatabaseTransactions;

    private function createEnterprise(): void
    {
        Enterprise::firstOrCreate(
            ['ruc' => '20123456789'],
            [
                'company_name' => 'IESTP Francisco Vigo Caballero',
                'trade_name' => 'IESTP FVC',
                'legal_representative_dni' => '12345678',
                'legal_representative' => 'Director General',
                'email' => 'informes@iestpfvc.edu.pe',
                'phone_number_1' => '+51 987654321',
                'address' => 'Av. Ricardo Palma N° 1401',
                'city' => 'Uchiza',
            ]
        );
    }

    public function test_public_user_can_access_licensing_page(): void
    {
        $this->createEnterprise();
        $this->seed(LicensingPhaseSeeder::class);

        $response = $this->get('/transparencia/licenciamiento');

        $response->assertOk();
        $response->assertSee('Licenciamiento');
        $response->assertSee('Institucional');

        // Assert 5 phases are rendered
        $response->assertSee('Condiciones Básicas de Calidad (CBC)');
        $response->assertSee('Presentación y Registro');
        $response->assertSee('Revisión Documentaria');
        $response->assertSee('Levantamiento y Subsanación de Observaciones');
        $response->assertSee('Aprobación y Otorgamiento de Licencia');

        // Assert Current Stage (P) is displayed
        $response->assertSee('(P)');
        $response->assertSee('En Proceso');

        // Assert 7 CBCs are displayed
        $response->assertSee('CBC 1');
        $response->assertSee('Gestión Institucional');
        $response->assertSee('Gestión Académica');
        $response->assertSee('Infraestructura');

        // Assert SEO Tags
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<script type="application/ld+json">', false);
    }
}
