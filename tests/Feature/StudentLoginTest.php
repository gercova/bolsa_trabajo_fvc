<?php

namespace Tests\Feature;

use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentLoginTest extends TestCase
{
    protected ?User $testStudent = null;

    protected ?User $testAdmin = null;

    protected function setUp(): void
    {
        parent::setUp();

        DocumentType::firstOrCreate(
            ['id' => 1],
            ['name' => 'DNI', 'abreviation' => 'DNI', 'is_active' => true]
        );

        Role::findOrCreate('Estudiante', 'web');
        Role::findOrCreate('Admin', 'web');

        // Limpiar posibles usuarios previos de prueba
        User::whereIn('email', [
            'estudiante_unit_test@iestp.edu.pe',
            'estudiante_inactivo@iestp.edu.pe',
            'admin_unit_test@iestp.edu.pe',
        ])->delete();
    }

    protected function tearDown(): void
    {
        if ($this->testStudent) {
            $this->testStudent->delete();
        }

        if ($this->testAdmin) {
            $this->testAdmin->delete();
        }

        User::whereIn('email', [
            'estudiante_unit_test@iestp.edu.pe',
            'estudiante_inactivo@iestp.edu.pe',
            'admin_unit_test@iestp.edu.pe',
        ])->delete();

        parent::tearDown();
    }

    /**
     * Prueba que un estudiante puede iniciar sesión con su correo electrónico y es redirigido a la biblioteca virtual.
     */
    public function test_student_can_login_with_email_and_is_redirected_to_library(): void
    {
        $this->testStudent = User::create([
            'document_type_id' => 1,
            'dni' => '88776655',
            'names' => 'Carlos Estudiante Prueba',
            'email' => 'estudiante_unit_test@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->testStudent->assignRole('Estudiante');

        $response = $this->post('/login', [
            'email' => 'estudiante_unit_test@iestp.edu.pe',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('biblioteca.index', absolute: false));
        $this->assertAuthenticatedAs($this->testStudent);
    }

    /**
     * Prueba que un estudiante puede iniciar sesión utilizando su número de DNI.
     */
    public function test_student_can_login_with_dni_and_is_redirected_to_library(): void
    {
        $this->testStudent = User::create([
            'document_type_id' => 1,
            'dni' => '88776655',
            'names' => 'Carlos Estudiante Prueba',
            'email' => 'estudiante_unit_test@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->testStudent->assignRole('Estudiante');

        $response = $this->post('/login', [
            'email' => '88776655',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('biblioteca.index', absolute: false));
        $this->assertAuthenticatedAs($this->testStudent);
    }

    /**
     * Prueba que un usuario inactivo no puede iniciar sesión y recibe un mensaje explicativo claro.
     */
    public function test_inactive_student_cannot_login_and_gets_informative_error(): void
    {
        $inactiveStudent = User::create([
            'document_type_id' => 1,
            'dni' => '88776644',
            'names' => 'Estudiante Inactivo',
            'email' => 'estudiante_inactivo@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'estudiante_inactivo@iestp.edu.pe',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();

        $errors = session('errors')->get('email');
        $this->assertStringContainsString('inactiva', $errors[0]);

        $inactiveStudent->delete();
    }

    /**
     * Prueba que un usuario con perfil administrativo es redirigido al panel de control.
     */
    public function test_admin_user_login_redirects_to_admin_dashboard(): void
    {
        $this->testAdmin = User::create([
            'document_type_id' => 1,
            'dni' => '11223344',
            'names' => 'Admin Prueba',
            'email' => 'admin_unit_test@iestp.edu.pe',
            'role' => 'Admin',
            'password' => Hash::make('admin12345'),
            'is_active' => true,
        ]);
        $this->testAdmin->assignRole('Admin');

        $response = $this->post('/login', [
            'email' => 'admin_unit_test@iestp.edu.pe',
            'password' => 'admin12345',
        ]);

        $response->assertRedirect(route('admin.dashboard.index', absolute: false));
        $this->assertAuthenticatedAs($this->testAdmin);
    }

    /**
     * Prueba que si un estudiante autenticado intenta acceder al panel de control administrativo, es redirigido a la biblioteca.
     */
    public function test_student_accessing_admin_dashboard_is_redirected_to_library(): void
    {
        $this->testStudent = User::create([
            'document_type_id' => 1,
            'dni' => '88776655',
            'names' => 'Carlos Estudiante Prueba',
            'email' => 'estudiante_unit_test@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->testStudent->assignRole('Estudiante');

        $response = $this->actingAs($this->testStudent)->get('/admin-dashboard');

        $response->assertRedirect(route('biblioteca.index'));
    }

    /**
     * Prueba que el portal de la biblioteca virtual carga exitosamente para el estudiante autenticado.
     */
    public function test_virtual_library_portal_renders_successfully_for_authenticated_student(): void
    {
        $this->testStudent = User::create([
            'document_type_id' => 1,
            'dni' => '88776655',
            'names' => 'Carlos Estudiante Prueba',
            'email' => 'estudiante_unit_test@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->testStudent->assignRole('Estudiante');

        $response = $this->actingAs($this->testStudent)->get('/biblioteca');

        $response->assertStatus(200);
        $response->assertSee('Hola, Carlos Estudiante Prueba');
    }
}
