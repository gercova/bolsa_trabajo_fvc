<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\Tupa;
use App\Models\TupaCategory;
use App\Models\TupaProcedure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Procedures/PartsTable')
            ->has('enterprise')
            ->where('enterprise.company_name', 'Test Enterprise')
        );
    }

    public function test_public_user_can_access_tupa_page(): void
    {
        $this->createEnterprise();

        $tupa = Tupa::create([
            'title' => 'TUPA 2026',
            'description' => 'Texto Único de Procedimientos Administrativos',
            'file_path' => 'tupa/tupa-2026.pdf',
            'effective_start_date' => now()->toDateString(),
            'is_active' => true,
        ]);

        $category = TupaCategory::create([
            'name' => 'Trámites Académicos',
            'icon' => 'bi-journal-check',
            'is_active' => true,
        ]);

        TupaProcedure::create([
            'tupa_id' => $tupa->id,
            'category_id' => $category->id,
            'code' => 'TUPA-001',
            'name' => 'Certificado de Estudios',
            'description' => 'Expedición de certificados oficiales',
            'requirements' => ['Solicitud FUT', 'Comprobante de pago'],
            'cost' => 25.00,
            'uit_percent' => '0.5%',
            'qualification' => 'Automática',
            'duration' => '3 días hábiles',
            'office' => 'Secretaría Académica',
            'is_active' => true,
        ]);

        $response = $this->get('/tramites/tupa');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Procedures/Tupa')
            ->has('enterprise')
            ->has('currentTupa')
            ->has('tupaHistory')
            ->has('procedures', 1)
            ->where('procedures.0.category', 'Trámites Académicos')
            ->where('procedures.0.items.0.code', 'TUPA-001')
        );
    }
}
