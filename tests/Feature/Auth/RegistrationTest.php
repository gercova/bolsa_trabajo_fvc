<?php

namespace Tests\Feature\Auth;

use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    protected int $docTypeId;

    protected function setUp(): void
    {
        parent::setUp();

        $docType = DocumentType::first() ?? DocumentType::create([
            'name' => 'DNI',
            'abreviation' => 'DNI',
            'is_active' => true,
        ]);
        $this->docTypeId = $docType->id;

        Role::findOrCreate('Estudiante', 'web');
        Role::findOrCreate('Docente', 'web');

        User::whereIn('dni', ['72345678', '71122334', '70011223', '99887766'])->delete();
        User::whereIn('email', [
            'mramosc@iestpfvc.edu.pe',
            'jperezg@iestpfvc.edu.pe',
            'avegar@iestpfvc.edu.pe',
        ])->delete();
        User::whereIn('personal_email', [
            'juan.perez.personal@gmail.com',
            'anita.vega@hotmail.com',
            'externo@gmail.com',
        ])->delete();
    }

    protected function tearDown(): void
    {
        User::whereIn('dni', ['72345678', '71122334', '70011223', '99887766'])->delete();
        User::whereIn('email', [
            'mramosc@iestpfvc.edu.pe',
            'jperezg@iestpfvc.edu.pe',
            'avegar@iestpfvc.edu.pe',
        ])->delete();
        User::whereIn('personal_email', [
            'juan.perez.personal@gmail.com',
            'anita.vega@hotmail.com',
            'externo@gmail.com',
        ])->delete();

        parent::tearDown();
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Registro de Cuenta Institucional');
        $response->assertSee('Validación de Padrón Institucional');
    }

    public function test_dni_verification_endpoint_returns_user_data_for_existing_member(): void
    {
        $existing = User::create([
            'document_type_id' => $this->docTypeId,
            'dni' => '72345678',
            'names' => 'MARIA ELENA RAMOS CASTILLO',
            'birthdate' => '2002-05-15',
            'phone' => '987654321',
            'email' => 'mramosc@iestpfvc.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $response = $this->getJson(route('register.verify-dni', ['dni' => '72345678']));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'already_registered' => false,
            'user' => [
                'dni' => '72345678',
                'names' => 'MARIA ELENA RAMOS CASTILLO',
                'birthdate' => '2002-05-15',
                'phone' => '987654321',
                'institutional_email' => 'mramosc@iestpfvc.edu.pe',
            ],
        ]);
    }

    public function test_dni_verification_endpoint_rejects_non_institutional_visitor(): void
    {
        $response = $this->getJson(route('register.verify-dni', ['dni' => '99999999']));

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'registered' => false,
        ]);
    }

    public function test_visitor_not_in_users_table_cannot_register(): void
    {
        $response = $this->post('/register', [
            'dni' => '99887766',
            'names' => 'Persona Externa',
            'personal_email' => 'externo@gmail.com',
            'password' => 'NewSecurePass123!',
            'password_confirmation' => 'NewSecurePass123!',
        ]);

        $response->assertSessionHasErrors(['dni']);
        $this->assertGuest();
    }

    public function test_existing_institutional_member_can_complete_registration_with_personal_email_and_cv(): void
    {
        Storage::fake('public');

        $user = User::create([
            'document_type_id' => $this->docTypeId,
            'dni' => '71122334',
            'names' => 'JUAN CARLOS PEREZ GOMEZ',
            'birthdate' => '2001-09-20',
            'email' => 'jperezg@iestpfvc.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $cvFile = UploadedFile::fake()->create('mi_curriculum.pdf', 500, 'application/pdf');

        $response = $this->post('/register', [
            'dni' => '71122334',
            'names' => 'JUAN CARLOS PEREZ GOMEZ',
            'birthdate' => '2001-09-20',
            'personal_email' => 'juan.perez.personal@gmail.com',
            'phone' => '955443322',
            'cv_file' => $cvFile,
            'password' => 'MiPasswordSegura123!',
            'password_confirmation' => 'MiPasswordSegura123!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('biblioteca.index', absolute: false));

        $user->refresh();
        $this->assertEquals('juan.perez.personal@gmail.com', $user->personal_email);
        $this->assertEquals('955443322', $user->phone);
        $this->assertTrue(Hash::check('MiPasswordSegura123!', $user->password));
        $this->assertNotNull($user->cv_file);
        Storage::disk('public')->assertExists($user->cv_file);
    }

    public function test_user_can_login_with_personal_email_after_registration(): void
    {
        $user = User::create([
            'document_type_id' => $this->docTypeId,
            'dni' => '70011223',
            'names' => 'ANA MARIA VEGA RUIZ',
            'email' => 'avegar@iestpfvc.edu.pe',
            'personal_email' => 'anita.vega@hotmail.com',
            'role' => 'Estudiante',
            'password' => Hash::make('MiPasswordSegura123!'),
            'is_active' => true,
        ]);
        $user->assignRole('Estudiante');

        // Test login with personal_email
        $response = $this->post('/login', [
            'email' => 'anita.vega@hotmail.com',
            'password' => 'MiPasswordSegura123!',
        ]);

        $response->assertRedirect(route('biblioteca.index', absolute: false));
        $this->assertAuthenticatedAs($user);
    }
}
