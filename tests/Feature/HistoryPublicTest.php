<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\HistoricalReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HistoryPublicTest extends TestCase
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
        ]);
    }

    public function test_public_user_can_access_history_page_in_ascending_chronological_order(): void
    {
        $this->createEnterprise();

        // Create historical reviews in unordered years
        HistoricalReview::create([
            'start_year' => 2024,
            'title' => 'Licenciamiento Institucional',
            'description' => 'El instituto se licencia ante el MINEDU.',
            'is_active' => true,
        ]);

        HistoricalReview::create([
            'start_year' => 1991,
            'title' => 'Fundación del Instituto',
            'description' => 'Nace como instituto tecnológico.',
            'is_active' => true,
        ]);

        HistoricalReview::create([
            'start_year' => 2006,
            'title' => 'Revalidación de Carreras',
            'description' => 'Se revalidan los programas formativos.',
            'is_active' => true,
        ]);

        $response = $this->get('/nosotros/historia');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('AboutUs/History')
            ->has('enterprise')
            ->has('histories', 3)
            ->where('histories.0.start_year', 1991)
            ->where('histories.0.title', 'Fundación del Instituto')
            ->where('histories.1.start_year', 2006)
            ->where('histories.1.title', 'Revalidación de Carreras')
            ->where('histories.2.start_year', 2024)
            ->where('histories.2.title', 'Licenciamiento Institucional')
        );
    }
}
