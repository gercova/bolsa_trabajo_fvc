<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\ManagementDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Transparency/ComplaintsBook')
            ->has('enterprise')
            ->where('enterprise.complaints_book_path', '/storage/enterprise/pdf/libro-reclamaciones-test.pdf')
        );
    }

    public function test_public_user_can_access_management_documents_page(): void
    {
        $this->createEnterprise();

        ManagementDocument::create([
            'title' => 'Plan Anual de Trabajo 2026',
            'description' => 'Documento de gestión anual',
            'details' => 'Aprobado mediante resolución',
            'is_active' => true,
        ]);

        $response = $this->get('/transparencia/documentos-de-gestion');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Transparency/ManagementDocuments')
            ->has('documents', 1)
            ->where('documents.0.title', 'Plan Anual de Trabajo 2026')
        );
    }

    public function test_public_user_can_access_statistics_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/transparencia/estadisticas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Transparency/Statistics')
            ->has('enterprise')
            ->has('totalMatriculas')
            ->has('totalAdmisiones')
            ->has('totalTitulos')
            ->has('totalProgramas')
            ->has('totalPeriodos')
            ->has('enrollmentByPeriod')
            ->has('degreesByYear')
        );
    }

    public function test_public_user_can_access_investment_and_management_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/transparencia/inversion-y-gestion');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Transparency/InvestmentAndManagement')
            ->has('availableYears')
            ->has('selectedYear')
            ->has('totalRecords')
            ->has('totalAmount')
            ->has('records')
            ->has('categoryGroups')
        );
    }

    public function test_public_user_can_access_licensing_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/transparencia/licenciamiento');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Transparency/Licensment')
            ->has('phases')
            ->has('totalPhases')
            ->has('completedPhases')
            ->has('globalProgress')
        );
    }
}
