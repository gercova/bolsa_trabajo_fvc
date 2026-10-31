<?php

namespace Tests\Feature;

use App\Models\StudyProgram;
use App\Models\User;
use App\Models\UserRoleDetail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminTeacherRoleTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Docente']);

        DB::table('document_type')->updateOrInsert(
            ['id' => 1],
            ['name' => 'Documento Nacional de Identidad', 'abreviation' => 'DNI', 'is_active' => true]
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_teacher_roles_test@iestpfvc.edu.pe'],
            [
                'dni' => '88880002',
                'names' => 'Admin Test',
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

    public function test_guest_cannot_access_teacher_roles(): void
    {
        $response = $this->get('/admin-docentes-roles');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_teacher_roles_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin-docentes-roles');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/TeacherRoles/Index')
            ->has('details')
            ->has('programs')
            ->has('teachers')
            ->has('roles')
        );
    }

    public function test_admin_can_toggle_teacher_role_status_via_form_redirect(): void
    {
        $teacher = User::create([
            'dni' => '99990001',
            'names' => 'Docente Prueba Redirect',
            'email' => 'docente_redirect_test@iestpfvc.edu.pe',
            'password' => bcrypt('secret123'),
            'document_type_id' => 1,
            'role' => 'Docente',
            'is_active' => true,
        ]);

        $program = StudyProgram::firstOrCreate(
            ['slug' => 'test-program-toggle'],
            ['name' => 'Programa Test Toggle', 'is_active' => true]
        );

        $detail = UserRoleDetail::create([
            'user_id' => $teacher->id,
            'program_id' => $program->id,
            'is_coordinator' => false,
            'specialty' => 'Desarrollo Web',
            'is_active' => true,
        ]);

        // Simulate submitting the form in teacher-roles.blade.php
        $response = $this->actingAs($this->admin)
            ->from('/admin-docentes-roles')
            ->patch("/admin-docentes-roles/estado/{$detail->id}");

        $response->assertRedirect('/admin-docentes-roles');
        $response->assertSessionHas('success');

        $detail->refresh();
        $this->assertFalse($detail->is_active);

        // Toggle back to active
        $response2 = $this->actingAs($this->admin)
            ->from('/admin-docentes-roles')
            ->patch("/admin-docentes-roles/estado/{$detail->id}");

        $response2->assertRedirect('/admin-docentes-roles');
        $response2->assertSessionHas('success');

        $detail->refresh();
        $this->assertTrue($detail->is_active);
    }

    public function test_admin_can_toggle_teacher_role_status_via_json(): void
    {
        $teacher = User::create([
            'dni' => '99990002',
            'names' => 'Docente Prueba JSON',
            'email' => 'docente_json_test@iestpfvc.edu.pe',
            'password' => bcrypt('secret123'),
            'document_type_id' => 1,
            'role' => 'Docente',
            'is_active' => true,
        ]);

        $detail = UserRoleDetail::create([
            'user_id' => $teacher->id,
            'program_id' => null,
            'is_coordinator' => false,
            'specialty' => 'Redes',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->patchJson("/admin-docentes-roles/estado/{$detail->id}");

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Estado actualizado.',
            'status' => false,
        ]);

        $detail->refresh();
        $this->assertFalse($detail->is_active);
    }
}
