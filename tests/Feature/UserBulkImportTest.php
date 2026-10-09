<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserBulkImportTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Estudiante']);
        Role::firstOrCreate(['name' => 'Docente']);

        // Ensure document_type 1 exists
        DB::table('document_type')->updateOrInsert(
            ['id' => 1],
            ['name' => 'Documento Nacional de Identidad', 'abreviation' => 'DNI', 'is_active' => true]
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_user_import_test@iestpfvc.edu.pe'],
            [
                'dni' => '99887766',
                'names' => 'Admin User Import Test',
                'password' => bcrypt('secret123'),
                'document_type_id' => 1,
                'role' => 'Admin',
            ]
        );

        if (! $this->admin->hasRole('Admin')) {
            $this->admin->assignRole('Admin');
        }
    }

    public function test_guest_cannot_download_user_template_or_import(): void
    {
        $response = $this->get(route('admin.users.template'));
        $response->assertRedirect(route('login'));

        $responseImport = $this->post(route('admin.users.import'));
        $responseImport->assertRedirect(route('login'));
    }

    public function test_admin_can_download_user_template_xlsx_and_csv(): void
    {
        $this->actingAs($this->admin);

        // Download Excel template
        $responseXlsx = $this->get(route('admin.users.template', ['format' => 'xlsx']));
        $responseXlsx->assertOk();
        $this->assertStringContainsString('plantilla_usuarios_', $responseXlsx->headers->get('content-disposition'));

        // Download CSV template
        $responseCsv = $this->get(route('admin.users.template', ['format' => 'csv']));
        $responseCsv->assertOk();
        $this->assertStringContainsString('.csv', $responseCsv->headers->get('content-disposition'));
    }

    public function test_admin_can_bulk_import_users_with_all_specifications(): void
    {
        $this->actingAs($this->admin);

        // Build CSV content
        $csvRows = [
            // Header
            ['N°', 'DNI', 'Nombres', 'Apellido Paterno', 'Apellido Materno', 'job_position', 'email', 'Rol', 'Teléfono'],
            // Row 1: student/graduate, no email -> should generate jperezg@iestpfvc.edu.pe
            ['1', '72001122', 'Juan Carlos', 'Pérez', 'Gómez', 'student/graduate', '', 'Estudiante', '987654321'],
            // Row 2: teaching/administrative staff, no email -> should generate mramosc@iestpfvc.edu.pe
            ['2', '45003344', 'María Elena', 'Ramos', 'Castillo', 'teaching/administrative staff', '', 'Docente', '976543210'],
            // Row 3: student/graduate, explicit email provided
            ['3', '78005566', 'Luis Alberto', 'Vásquez', 'Torres', 'student/graduate', 'lvasquez_custom@gmail.com', 'Estudiante', '965432109'],
        ];

        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM
        foreach ($csvRows as $row) {
            $csvContent .= implode(',', array_map(fn ($f) => '"'.str_replace('"', '""', $f).'"', $row))."\n";
        }

        $file = UploadedFile::fake()->createWithContent('import_test_users.csv', $csvContent);

        $response = $this->post(route('admin.users.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        // Verify User 1
        $user1 = User::where('dni', '72001122')->first();
        $this->assertNotNull($user1);
        $this->assertEquals(1, $user1->document_type_id, 'document_type_id must always be 1 (DNI)');
        $this->assertEquals('student/graduate', $user1->job_position);
        $this->assertEquals('jperezg@iestpfvc.edu.pe', $user1->email, 'Email should follow initial+paternal+initial_maternal formula');
        $this->assertTrue(Hash::check('P4$$w0rd*', $user1->password), 'Password must be P4$$w0rd*');

        // Verify User 2
        $user2 = User::where('dni', '45003344')->first();
        $this->assertNotNull($user2);
        $this->assertEquals(1, $user2->document_type_id, 'document_type_id must always be 1 (DNI)');
        $this->assertEquals('teaching/administrative staff', $user2->job_position);
        $this->assertEquals('mramosc@iestpfvc.edu.pe', $user2->email);
        $this->assertTrue(Hash::check('P4$$w0rd*', $user2->password), 'Password must be P4$$w0rd*');

        // Verify User 3
        $user3 = User::where('dni', '78005566')->first();
        $this->assertNotNull($user3);
        $this->assertEquals(1, $user3->document_type_id);
        $this->assertEquals('student/graduate', $user3->job_position);
        $this->assertEquals('lvasquez_custom@gmail.com', $user3->email, 'Explicit email should be kept');
        $this->assertTrue(Hash::check('P4$$w0rd*', $user3->password), 'Password must be P4$$w0rd*');
    }

    public function test_bulk_import_validates_and_rejects_invalid_job_position_and_dni(): void
    {
        $this->actingAs($this->admin);

        $csvRows = [
            ['N°', 'DNI', 'Nombres', 'Apellido Paterno', 'Apellido Materno', 'job_position', 'email', 'Rol', 'Teléfono'],
            // Invalid job position
            ['1', '79001122', 'Carlos', 'Gómez', 'Rojas', 'astronaut_scientist', '', 'Estudiante', '987111222'],
            // Invalid DNI (too short)
            ['2', '12', 'Ana', 'Torres', 'Luna', 'student/graduate', '', 'Estudiante', '987333444'],
            // Valid row
            ['3', '74003344', 'Rosa', 'Medina', 'Vargas', 'teaching/administrative staff', '', 'Docente', '987555666'],
        ];

        $csvContent = "\xEF\xBB\xBF";
        foreach ($csvRows as $row) {
            $csvContent .= implode(',', array_map(fn ($f) => '"'.str_replace('"', '""', $f).'"', $row))."\n";
        }

        $file = UploadedFile::fake()->createWithContent('invalid_positions.csv', $csvContent);

        $response = $this->post(route('admin.users.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertNotEmpty($errors);

        // Ensure invalid users were not created
        $this->assertNull(User::where('dni', '79001122')->first());
        $this->assertNull(User::where('dni', '12')->first());

        // Ensure valid user was created
        $validUser = User::where('dni', '74003344')->first();
        $this->assertNotNull($validUser);
        $this->assertEquals(1, $validUser->document_type_id);
        $this->assertEquals('teaching/administrative staff', $validUser->job_position);
        $this->assertEquals('rmedinav@iestpfvc.edu.pe', $validUser->email);
        $this->assertTrue(Hash::check('P4$$w0rd*', $validUser->password));
    }

    public function test_bulk_import_updates_existing_user_with_new_rules(): void
    {
        $this->actingAs($this->admin);

        // Pre-create user with old data
        $existing = User::create([
            'document_type_id' => null, // e.g. Old non-assigned
            'dni' => '81818181',
            'names' => 'Old Name',
            'email' => 'old_email@gmail.com',
            'job_position' => 'student/graduate',
            'password' => bcrypt('old_password'),
            'role' => 'Estudiante',
            'is_active' => true,
        ]);

        $csvRows = [
            ['N°', 'DNI', 'Nombres', 'Apellido Paterno', 'Apellido Materno', 'job_position', 'email', 'Rol', 'Teléfono'],
            ['1', '81818181', 'Nuevo Nombre', 'Herrera', 'Silva', 'teaching/administrative staff', '', 'Docente', '999888777'],
        ];

        $csvContent = "\xEF\xBB\xBF";
        foreach ($csvRows as $row) {
            $csvContent .= implode(',', array_map(fn ($f) => '"'.str_replace('"', '""', $f).'"', $row))."\n";
        }

        $file = UploadedFile::fake()->createWithContent('update_user.csv', $csvContent);

        $response = $this->post(route('admin.users.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $existing->refresh();
        $this->assertEquals(1, $existing->document_type_id, 'Must be updated to DNI [1]');
        $this->assertEquals('teaching/administrative staff', $existing->job_position);
        $this->assertTrue(Hash::check('P4$$w0rd*', $existing->password), 'Password must be updated to P4$$w0rd*');
    }
}
