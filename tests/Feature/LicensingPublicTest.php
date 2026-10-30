<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use Database\Seeders\LicensingPhaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Inertia\Testing\AssertableInertia as Assert;
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
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Transparency/Licensment')
            ->has('enterprise')
            ->has('phases', 5)
            ->has('currentPhase')
            ->has('cbcPhase')
            ->where('totalPhases', 5)
            ->where('completedPhases', 0)
            ->has('globalProgress')
        );
    }
}
