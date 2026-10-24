<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserCreateTest extends TestCase
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
            ['email' => 'admin_create_test@iestpfvc.edu.pe'],
            [
                'dni' => '88880001',
                'names' => 'Admin Create Test',
                'password' => bcrypt('secret123'),
                'document_type_id' => 1,
                'role' => 'Admin',
                'is_active' => true,
            ]
        );

        if (! $this->admin->hasRole('Admin')) {
            $this->admin->assignRole('Admin');
        }
    }

    public function test_admin_can_access_create_user_view(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.create'));

        $response->assertOk();
        $response->assertSee('Crear Nuevo Usuario');
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
        $response->assertSee(route('admin.users.store'), false);
    }

    public function test_admin_can_create_user_with_custom_password(): void
    {
        $userData = [
            'dni' => '99000001',
            'names' => 'Carlos Mendoza Díaz',
            'email' => 'carlos.mendoza.unique@test.com',
            'role' => 'Docente',
            'job_position' => 'Profesor de Computación',
            'password' => 'MiClaveSegura123',
            'password_confirmation' => 'MiClaveSegura123',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $createdUser = User::where('dni', '99000001')->first();
        $this->assertNotNull($createdUser);
        $this->assertEquals('Carlos Mendoza Díaz', $createdUser->names);
        $this->assertEquals('carlos.mendoza.unique@test.com', $createdUser->email);
        $this->assertEquals('Docente', $createdUser->role);
        $this->assertTrue($createdUser->hasRole('Docente'));
        $this->assertTrue(Hash::check('MiClaveSegura123', $createdUser->password));
    }

    public function test_admin_can_create_user_without_password_defaulting_to_dni(): void
    {
        $userData = [
            'dni' => '72345678',
            'names' => 'Ana Lucía Torres',
            'email' => 'ana.torres@test.com',
            'role' => 'Estudiante',
            'job_position' => null,
            'password' => '',
            'password_confirmation' => '',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $createdUser = User::where('dni', '72345678')->first();
        $this->assertNotNull($createdUser);
        $this->assertEquals('Ana Lucía Torres', $createdUser->names);
        $this->assertEquals('Estudiante', $createdUser->role);
        $this->assertTrue($createdUser->hasRole('Estudiante'));
        $this->assertTrue(Hash::check('72345678', $createdUser->password));
    }

    public function test_validation_fails_when_required_fields_missing(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'dni' => '',
            'names' => '',
            'email' => 'correo-invalido',
            'role' => '',
        ]);

        $response->assertSessionHasErrors(['dni', 'names', 'email', 'role']);
    }
}
