<?php

namespace Tests\Feature;

use App\Models\Scholarship;
use App\Models\ScholarshipBeneficiary;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScholarshipBeneficiariesFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $docTypeId = \Illuminate\Support\Facades\DB::table('document_type')->value('id') ?? 1;

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@iestpfvc.edu.pe'],
            [
                'dni'              => '77889900',
                'names'            => 'Admin Test',
                'password'         => bcrypt('secret123'),
                'document_type_id' => $docTypeId,
                'role'             => 'Admin',
            ]
        );
    }

    public function test_public_scholarships_page_displays_beneficiaries_section(): void
    {
        $response = $this->get(route('becas-y-creditos'));

        $response->assertStatus(200);
        $response->assertSee('Relación de Estudiantes Beneficiarios');
        $response->assertSee('Padrón de Beneficiarios');
        $response->assertSee('2026-I');
        $response->assertSee('Visualizar PDF');
    }

    public function test_admin_can_view_scholarships_dashboard_with_beneficiaries_tab(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.scholarships.index', ['tab' => 'beneficiaries']));

        $response->assertStatus(200);
        $response->assertSee('Padrón de Beneficiarios (PDF)');
        $response->assertSee('Subir Nuevo Padrón PDF');
        $response->assertSee('2026-I');
    }

    public function test_admin_can_upload_beneficiary_pdf(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('relacion_becarios_2027_i.pdf', 500, 'application/pdf');

        $payload = [
            'academic_period'   => '2027-I',
            'title'             => 'Padrón Test Beneficiarios 2027-I',
            'description'       => 'Descripción de prueba para el padrón 2027-I',
            'resolution_number' => 'R.D. N° 999-2027-IESTP-FVC',
            'publication_date'  => '2027-03-01',
            'file'              => $file,
            'is_active'         => '1',
            'sort_order'        => 5,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.scholarships.beneficiaries.store'), $payload);

        $response->assertRedirect(route('admin.scholarships.index', ['tab' => 'beneficiaries']));

        $this->assertDatabaseHas('scholarship_beneficiaries', [
            'academic_period'   => '2027-I',
            'title'             => 'Padrón Test Beneficiarios 2027-I',
            'resolution_number' => 'R.D. N° 999-2027-IESTP-FVC',
        ]);

        $created = ScholarshipBeneficiary::where('academic_period', '2027-I')->latest('id')->first();
        $this->assertNotNull($created);
        Storage::disk('public')->assertExists($created->file_path);
    }

    public function test_admin_can_toggle_beneficiary_status(): void
    {
        $beneficiary = ScholarshipBeneficiary::create([
            'academic_period' => '2028-I',
            'title'           => 'Padrón para toggle',
            'file_path'       => 'scholarships/beneficiaries/dummy.pdf',
            'is_active'       => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.scholarships.beneficiaries.toggle-status', $beneficiary));

        $response->assertRedirect(route('admin.scholarships.index', ['tab' => 'beneficiaries']));

        $this->assertFalse($beneficiary->fresh()->is_active);
    }

    public function test_admin_can_fetch_beneficiary_details_json(): void
    {
        $beneficiary = ScholarshipBeneficiary::create([
            'academic_period'   => '2029-I',
            'title'             => 'Padrón JSON test',
            'resolution_number' => 'R.D. 123',
            'file_path'         => 'scholarships/beneficiaries/dummy.pdf',
            'is_active'         => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.scholarships.beneficiaries.edit', $beneficiary));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data'    => [
                'id'              => $beneficiary->id,
                'academic_period' => '2029-I',
                'title'           => 'Padrón JSON test',
            ],
        ]);
    }

    public function test_admin_can_update_beneficiary(): void
    {
        $beneficiary = ScholarshipBeneficiary::create([
            'academic_period' => '2030-I',
            'title'           => 'Padrón Original',
            'file_path'       => 'scholarships/beneficiaries/original.pdf',
            'is_active'       => true,
        ]);

        $updateData = [
            'academic_period'   => '2030-II',
            'title'             => 'Padrón Actualizado',
            'resolution_number' => 'R.D. N° 555-2030',
            'is_active'         => '1',
            'sort_order'        => 2,
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.scholarships.beneficiaries.update', $beneficiary), $updateData);

        $response->assertRedirect(route('admin.scholarships.index', ['tab' => 'beneficiaries']));

        $this->assertDatabaseHas('scholarship_beneficiaries', [
            'id'              => $beneficiary->id,
            'academic_period' => '2030-II',
            'title'           => 'Padrón Actualizado',
        ]);
    }

    public function test_admin_can_delete_beneficiary(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('delete_me.pdf', 100, 'application/pdf');
        $path = $file->store('scholarships/beneficiaries', 'public');

        $beneficiary = ScholarshipBeneficiary::create([
            'academic_period' => '2031-I',
            'title'           => 'Padrón Para Eliminar',
            'file_path'       => $path,
            'is_active'       => true,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.scholarships.beneficiaries.destroy', $beneficiary));

        $response->assertRedirect(route('admin.scholarships.index', ['tab' => 'beneficiaries']));

        $this->assertDatabaseMissing('scholarship_beneficiaries', [
            'id' => $beneficiary->id,
        ]);

        Storage::disk('public')->assertMissing($path);
    }
}
