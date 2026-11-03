<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminModuleTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Admin']);

        $docType = DocumentType::firstOrCreate(
            ['abreviation' => 'DNI'],
            ['name' => 'Documento Nacional de Identidad', 'is_active' => true]
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_module_test@iestpfvc.edu.pe'],
            [
                'dni' => '77889901',
                'names' => 'Admin Module Tester',
                'password' => bcrypt('password123'),
                'document_type_id' => $docType->id,
                'role' => 'Admin',
                'is_active' => true,
            ]
        );

        if (! $this->admin->hasRole('Admin')) {
            $this->admin->assignRole('Admin');
        }

        $this->course = Course::create([
            'name' => 'Curso Base Para Modulos',
            'certificate_type' => 'modular',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_modules_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.modules.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Modules/Index')
            ->has('modules.data')
            ->has('courses')
            ->has('totalModules')
        );
    }

    public function test_admin_can_create_module_with_inertia_redirect(): void
    {
        $response = $this->actingAs($this->admin)
            ->withHeaders(['X-Inertia' => 'true'])
            ->post(route('admin.modules.store'), [
                'name' => 'Módulo I: Gestión y Soporte de TI',
                'course_id' => $this->course->id,
                'credits' => '12',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.modules.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('modules', [
            'name' => 'Módulo I: Gestión y Soporte de TI',
            'course_id' => $this->course->id,
            'credits' => 12,
        ]);
    }
}
