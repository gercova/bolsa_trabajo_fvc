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
        Role::findOrCreate('Student', 'web');
        Role::findOrCreate('Admin', 'web');

        // Limpiar posibles usuarios previos de prueba
        User::whereIn('email', [
            'estudiante_unit_test@iestp.edu.pe',
            'student_role_unit_test@iestp.edu.pe',
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
            'student_role_unit_test@iestp.edu.pe',
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

    /**
     * Prueba que un usuario con rol "Student" puede iniciar sesión y recibe exclusivamente los 5 permisos iniciales permitidos.
     */
    public function test_user_with_student_role_can_login_and_has_exclusive_initial_permissions(): void
    {
        $this->testStudent = User::create([
            'document_type_id' => 1,
            'dni' => '88776633',
            'names' => 'Ana Estudiante Student Role',
            'email' => 'student_role_unit_test@iestp.edu.pe',
            'role' => 'Student',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->testStudent->assignRole('Student');

        $response = $this->post('/login', [
            'email' => 'student_role_unit_test@iestp.edu.pe',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('biblioteca.index', absolute: false));
        $this->assertAuthenticatedAs($this->testStudent);

        $this->testStudent->refresh();

        // 1. Actualizar su información personal
        $this->assertTrue(
            $this->testStudent->can('actualizar-informacion-personal') || $this->testStudent->can('update-personal-information'),
            'El estudiante debe tener permiso para actualizar su información personal.'
        );

        // 2. Acceder a la biblioteca virtual
        $this->assertTrue(
            $this->testStudent->can('acceder-biblioteca-virtual') || $this->testStudent->can('access-virtual-library'),
            'El estudiante debe tener permiso para acceder a la biblioteca virtual.'
        );

        // 3. Presentar solicitudes administrativas
        $this->assertTrue(
            $this->testStudent->can('presentar-solicitudes-administrativas') || $this->testStudent->can('submit-administrative-requests'),
            'El estudiante debe tener permiso para presentar solicitudes administrativas.'
        );

        // 4. Ver sus certificados
        $this->assertTrue(
            $this->testStudent->can('ver-certificados') || $this->testStudent->can('view-certificates'),
            'El estudiante debe tener permiso para ver sus certificados.'
        );

        // 5. Consultar el reglamento interno
        $this->assertTrue(
            $this->testStudent->can('consultar-reglamento-interno') || $this->testStudent->can('consult-internal-regulations'),
            'El estudiante debe tener permiso para consultar el reglamento interno.'
        );

        // Asegurar que NO tiene permisos administrativos
        $this->assertFalse(
            $this->testStudent->can('ver-dashboard'),
            'El estudiante NO debe tener permiso para ver el panel de control administrativo.'
        );
        $this->assertFalse(
            $this->testStudent->can('gestionar-usuarios'),
            'El estudiante NO debe tener permiso para gestionar usuarios.'
        );
        $this->assertFalse(
            $this->testStudent->can('gestionar-roles'),
            'El estudiante NO debe tener permiso para gestionar roles.'
        );
        $this->assertFalse(
            $this->testStudent->can('gestionar-inversiones'),
            'El estudiante NO debe tener permiso para gestionar inversiones.'
        );
    }

    /**
     * Prueba que si un estudiante tenía permisos o roles administrativos por error, se revocan al iniciar sesión dejando solo los 5 permitidos.
     */
    public function test_student_with_residual_admin_permissions_is_stripped_to_only_student_permissions_on_login(): void
    {
        $this->testStudent = User::create([
            'document_type_id' => 1,
            'dni' => '88776622',
            'names' => 'Estudiante Con Permisos Residuales',
            'email' => 'estudiante_unit_test@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->testStudent->assignRole('Estudiante');

        // Asignar indebidamente un permiso administrativo previo
        $this->testStudent->givePermissionTo('gestionar-usuarios');
        $this->assertTrue($this->testStudent->can('gestionar-usuarios'));

        $response = $this->post('/login', [
            'email' => 'estudiante_unit_test@iestp.edu.pe',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('biblioteca.index', absolute: false));

        $this->testStudent->refresh();

        // Tras el inicio de sesión, el permiso administrativo fue revocado
        $this->assertFalse(
            $this->testStudent->can('gestionar-usuarios'),
            'Los permisos administrativos no permitidos deben haber sido eliminados al iniciar sesión.'
        );

        // Los 5 permisos requeridos siguen presentes
        $this->assertTrue($this->testStudent->can('actualizar-informacion-personal'));
        $this->assertTrue($this->testStudent->can('acceder-biblioteca-virtual'));
        $this->assertTrue($this->testStudent->can('presentar-solicitudes-administrativas'));
        $this->assertTrue($this->testStudent->can('ver-certificados'));
        $this->assertTrue($this->testStudent->can('consultar-reglamento-interno'));
    }

    /**
     * Prueba que múltiples inicios de sesión consecutivos de estudiantes no arrojan error de clave duplicada (Integrity constraint violation).
     */
    public function test_multiple_consecutive_student_logins_do_not_throw_duplicate_entry_exception(): void
    {
        $this->testStudent = User::create([
            'document_type_id' => 1,
            'dni' => '88776611',
            'names' => 'Estudiante Inicios Consecutivos',
            'email' => 'student_consecutive@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->testStudent->assignRole('Estudiante');

        // Primer login
        $response1 = $this->post('/login', [
            'email' => 'student_consecutive@iestp.edu.pe',
            'password' => 'password123',
        ]);
        $response1->assertRedirect(route('biblioteca.index', absolute: false));

        $this->post('/logout');

        // Segundo login consecutivo (comprueba que no falla en la inserción de permisos existentes)
        $response2 = $this->post('/login', [
            'email' => 'student_consecutive@iestp.edu.pe',
            'password' => 'password123',
        ]);
        $response2->assertRedirect(route('biblioteca.index', absolute: false));
        $this->assertAuthenticatedAs($this->testStudent);

        $this->testStudent->delete();
    }
}
