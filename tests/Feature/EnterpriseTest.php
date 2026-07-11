<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EnterpriseTest extends TestCase
{
    use RefreshDatabase;

    private function createEnterprise(array $overrides = []): Enterprise
    {
        $enterprise = new Enterprise();
        $enterprise->ruc = '20123456789';
        $enterprise->company_name = 'Test Enterprise';
        $enterprise->trade_name = 'Test Trade';
        $enterprise->legal_representative_dni = '12345678';
        $enterprise->legal_representative = 'John Doe';
        $enterprise->email = 'test@example.com';
        
        foreach ($overrides as $key => $value) {
            $enterprise->{$key} = $value;
        }
        
        $enterprise->save();
        return $enterprise;
    }

    public function test_admin_can_view_enterprise_edit_page(): void
    {
        $user = User::factory()->create();
        $this->createEnterprise();

        $response = $this
            ->actingAs($user)
            ->get('/admin-empresa/editar');

        $response->assertOk();
    }

    public function test_admin_can_update_enterprise_and_upload_complaints_book(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $enterprise = $this->createEnterprise([
            'company_name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $pdf = UploadedFile::fake()->create('libro_reclamaciones.pdf', 500, 'application/pdf');

        $response = $this
            ->actingAs($user)
            ->put(route('admin.enterprise.update', $enterprise), [
                'ruc' => '20123456789',
                'company_name' => 'Updated Company Name',
                'trade_name' => 'Updated Trade Name',
                'legal_representative_dni' => '12345678',
                'legal_representative' => 'John Doe',
                'email' => 'updated@example.com',
                'complaints_book_path' => $pdf,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.enterprise.edit'));

        $enterprise->refresh();

        $this->assertEquals('Updated Company Name', $enterprise->company_name);
        $this->assertNotNull($enterprise->complaints_book_path);

        // Verify the file was stored in public disk
        $relativePath = str_replace('/storage/', '', $enterprise->complaints_book_path);
        Storage::disk('public')->assertExists($relativePath);
    }

    public function test_invalid_file_format_fails_validation(): void
    {
        $user = User::factory()->create();
        $enterprise = $this->createEnterprise();

        $txtFile = UploadedFile::fake()->create('documento.txt', 100, 'text/plain');

        $response = $this
            ->actingAs($user)
            ->put(route('admin.enterprise.update', $enterprise), [
                'ruc' => '20123456789',
                'company_name' => 'Updated Company Name',
                'trade_name' => 'Updated Trade Name',
                'legal_representative_dni' => '12345678',
                'legal_representative' => 'John Doe',
                'email' => 'updated@example.com',
                'complaints_book_path' => $txtFile,
            ]);

        $response->assertSessionHasErrors(['complaints_book_path']);
    }
}
