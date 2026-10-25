<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentSidebarTest extends TestCase
{
    protected ?User $student = null;

    protected ?User $otherStudent = null;

    protected ?User $admin = null;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('document_type')->updateOrInsert(
            ['id' => 1],
            ['name' => 'DNI', 'abreviation' => 'DNI', 'is_active' => true]
        );

        $studentRole = Role::findOrCreate('Estudiante', 'web');
        $adminRole = Role::findOrCreate('Admin', 'web');

        $studentPermissions = [
            'actualizar-informacion-personal',
            'acceder-biblioteca-virtual',
            'presentar-solicitudes-administrativas',
            'ver-certificados',
            'consultar-reglamento-interno',
        ];

        foreach ($studentPermissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        $adminPermissions = [
            'ver-dashboard',
            'gestionar-usuarios',
            'gestionar-roles',
            'gestionar-blogs',
            'actualizar-informacion-personal',
        ];

        foreach ($adminPermissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        $studentRole->syncPermissions($studentPermissions);
        $adminRole->syncPermissions($adminPermissions);

        // Limpiar usuarios de prueba previos
        User::whereIn('email', [
            'student_sidebar_test@iestp.edu.pe',
            'other_student_test@iestp.edu.pe',
            'admin_sidebar_test@iestp.edu.pe',
        ])->delete();

        $this->student = User::create([
            'document_type_id' => 1,
            'dni' => '71112233',
            'names' => 'Estudiante Sidebar Test',
            'email' => 'student_sidebar_test@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->student->assignRole('Estudiante');

        $this->otherStudent = User::create([
            'document_type_id' => 1,
            'dni' => '72223344',
            'names' => 'Otro Estudiante Test',
            'email' => 'other_student_test@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->otherStudent->assignRole('Estudiante');

        $this->admin = User::create([
            'document_type_id' => 1,
            'dni' => '10001000',
            'names' => 'Admin Sidebar Test',
            'email' => 'admin_sidebar_test@iestp.edu.pe',
            'role' => 'Admin',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->admin->assignRole('Admin');
    }

    protected function tearDown(): void
    {
        if ($this->student) {
            $this->student->delete();
        }
        if ($this->otherStudent) {
            $this->otherStudent->delete();
        }
        if ($this->admin) {
            $this->admin->delete();
        }

        User::whereIn('email', [
            'student_sidebar_test@iestp.edu.pe',
            'other_student_test@iestp.edu.pe',
            'admin_sidebar_test@iestp.edu.pe',
        ])->delete();

        parent::tearDown();
    }

    /**
     * Prueba que el menú lateral (aside) muestra únicamente las 5 opciones permitidas para el estudiante
     * y oculta todas las opciones administrativas.
     */
    public function test_student_sidebar_displays_dedicated_section_and_only_allowed_options(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.profile.edit', $this->student));

        $response->assertStatus(200);

        // Sección y distintivo de Estudiante / Postulante
        $response->assertSee('Estudiante / Postulante');

        // Las 5 opciones exclusivas requeridas:
        // 1. Actualizar información personal
        $response->assertSee('Actualizar Datos');
        $response->assertSee(route('admin.profile.edit', $this->student));

        // 2. Biblioteca virtual (como lector)
        $response->assertSee('Biblioteca Virtual');
        $response->assertSee(route('biblioteca.index'));

        // 3. Mesa de Partes (formulario de solicitudes)
        $response->assertSee('Mesa de Partes');
        $response->assertSee(route('mesa-de-partes'));

        // 4. Mis Certificados
        $response->assertSee('Mis Certificados');
        $response->assertSee(route('admin.certificates.index'));

        // 5. Reglamento Interno
        $response->assertSee('Reglamento Interno');
        $response->assertSee(route('admin.regulations.index'));

        // Asegurar que NO se muestran las opciones administrativas del sidebar
        $response->assertDontSee('Resumen General');
        $response->assertDontSee('Gestionar Blogs');
        $response->assertDontSee('Roles del sistema');
        $response->assertDontSee('Inversión y Gastos');
        $response->assertDontSee(route('admin.works.index'));
        $response->assertDontSee(route('admin.dashboard.index'));
    }

    /**
     * Prueba que el pie del menú lateral muestra el rol correcto del estudiante y no "Administrador" de forma estática.
     */
    public function test_student_sidebar_bottom_widget_shows_student_role(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.profile.edit', $this->student));

        $response->assertStatus(200);
        $response->assertSee($this->student->names);
        $response->assertSee('Estudiante');
        $response->assertDontSee('text-slate-400 truncate">Administrador</p>', false);
    }

    /**
     * Prueba que un usuario Administrador sí ve las opciones administrativas en su menú lateral
     * y no ve la sección exclusiva de estudiante.
     */
    public function test_admin_sidebar_displays_administrative_options(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.profile.edit', $this->admin));

        $response->assertStatus(200);

        // Opciones administrativas presentes
        $response->assertSee('Resumen General');
        $response->assertSee('Gestionar Blogs');
        $response->assertSee('Roles del sistema');

        // No debe mostrar la sección exclusiva de estudiante en el sidebar
        $response->assertDontSee('SECCIÓN DEDICADA: ESTUDIANTE', false);
    }

    /**
     * Prueba que un estudiante no puede acceder al perfil de otro usuario y es redirigido a su propio perfil.
     */
    public function test_student_cannot_view_or_edit_another_users_profile(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.profile.edit', $this->otherStudent));

        $response->assertRedirect(route('admin.profile.edit', $this->student));
        $response->assertSessionHas('error');
    }

    /**
     * Prueba que un estudiante puede acceder a su panel de certificados exclusivo.
     */
    public function test_student_can_access_scoped_certificates_dashboard(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.certificates.index'));

        $response->assertStatus(200);
        $response->assertSee('Mis Certificados Oficiales');
        $response->assertSee('Portal del Estudiante');
    }

    /**
     * Prueba que un estudiante puede acceder a la consulta del Reglamento Interno.
     */
    public function test_student_can_access_internal_regulations_page(): void
    {
        $response = $this->actingAs($this->student)->get(route('admin.regulations.index'));

        $response->assertStatus(200);
        $response->assertSee('Reglamento Interno Institucional');
    }

    /**
     * Prueba que al acceder a Mesa de Partes, los datos del estudiante aparecen pre-cargados.
     */
    public function test_student_data_is_prefilled_in_parts_table(): void
    {
        $response = $this->actingAs($this->student)->get(route('mesa-de-partes'));

        $response->assertStatus(200);
        $response->assertSee($this->student->dni);
        $response->assertSee($this->student->email);
    }
}
