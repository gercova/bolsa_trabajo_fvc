<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\HistoricalReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        ]);

        HistoricalReview::create([
            'start_year' => 1991,
            'title' => 'Fundación del Instituto',
            'description' => 'Nace como instituto tecnológico.',
        ]);

        HistoricalReview::create([
            'start_year' => 2006,
            'title' => 'Revalidación de Carreras',
            'description' => 'Se revalidan los programas formativos.',
        ]);

        $response = $this->get('/nosotros/historia');

        $response->assertOk();
        $response->assertSee('Reseña Histórica');
        $response->assertSee('Fundación del Instituto');
        $response->assertSee('Revalidación de Carreras');
        $response->assertSee('Licenciamiento Institucional');

        // Assert chronological ascending order: 1991 (Fundación) -> 2006 (Revalidación) -> 2024 (Licenciamiento)
        $html = $response->getContent();
        $pos1991 = strpos($html, 'Fundación del Instituto');
        $pos2006 = strpos($html, 'Revalidación de Carreras');
        $pos2024 = strpos($html, 'Licenciamiento Institucional');

        $this->assertTrue($pos1991 < $pos2006, 'El hito de 1991 debe mostrarse antes del de 2006.');
        $this->assertTrue($pos2006 < $pos2024, 'El hito de 2006 debe mostrarse antes del de 2024.');

        // Assert SEO tags are present
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<script type="application/ld+json">', false);
    }
}
