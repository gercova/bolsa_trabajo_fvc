<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Enterprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClaimTest extends TestCase
{
    use RefreshDatabase;

    private function createEnterprise(): Enterprise
    {
        $enterprise = new Enterprise();
        $enterprise->ruc = '20123456789';
        $enterprise->company_name = 'Test Enterprise';
        $enterprise->trade_name = 'Test Trade';
        $enterprise->legal_representative_dni = '12345678';
        $enterprise->legal_representative = 'John Doe';
        $enterprise->email = 'test@example.com';
        $enterprise->save();
        return $enterprise;
    }

    public function test_can_view_complaints_book_page(): void
    {
        $this->createEnterprise();

        $response = $this->get('/transparencia/libro-de-reclamaciones');

        $response->assertOk();
        $response->assertViewHas('enterprise');
    }

    public function test_can_submit_valid_claim(): void
    {
        Storage::fake('public');
        $this->createEnterprise();

        $attachment = UploadedFile::fake()->create('evidencia.jpg', 200, 'image/jpeg');

        $response = $this->post('/transparencia/libro-de-reclamaciones', [
            'dni' => '12345678',
            'names' => 'Carlos Perez',
            'email' => 'carlos@example.com',
            'subject' => 'Reclamo',
            'message' => 'Disconformidad con el servicio de admision',
            'file_path' => $attachment,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(); // redirects back

        // Assert record exists in database
        $this->assertDatabaseHas('claims', [
            'dni' => '12345678',
            'names' => 'Carlos Perez',
            'email' => 'carlos@example.com',
            'subject' => 'Reclamo',
            'message' => 'Disconformidad con el servicio de admision',
            'status' => 'pendiente',
        ]);

        $claim = Claim::first();
        $this->assertNotNull($claim->file_path);

        // Assert file exists in public disk
        Storage::disk('public')->assertExists($claim->file_path);
    }

    public function test_invalid_claim_data_fails_validation(): void
    {
        $this->createEnterprise();

        // Missing required fields & short DNI
        $response = $this->post('/transparencia/libro-de-reclamaciones', [
            'dni' => '123',
            'names' => '',
            'email' => 'not-an-email',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['dni', 'names', 'email', 'subject', 'message']);
    }
}
