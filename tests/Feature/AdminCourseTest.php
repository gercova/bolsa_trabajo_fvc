<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\DocumentType;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminCourseTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Admin']);

        $docType = DocumentType::firstOrCreate(
            ['abreviation' => 'DNI'],
            ['name' => 'Documento Nacional de Identidad', 'is_active' => true]
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_course_test@iestpfvc.edu.pe'],
            [
                'dni' => '77889900',
                'names' => 'Admin Course Tester',
                'password' => bcrypt('password123'),
                'document_type_id' => $docType->id,
                'role' => 'Admin',
                'is_active' => true,
            ]
        );

        if (! $this->admin->hasRole('Admin')) {
            $this->admin->assignRole('Admin');
        }
    }

    public function test_guest_cannot_access_admin_courses(): void
    {
        $response = $this->get(route('admin.courses.index'));
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_courses_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.courses.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Courses/Index')
            ->has('courses.data')
            ->has('studyPrograms')
            ->has('totalCourses')
            ->has('activeCourses')
        );
    }

    public function test_admin_can_create_new_course_with_inertia_redirect(): void
    {
        $program = StudyProgram::firstOrCreate(
            ['slug' => 'test-program-courses'],
            ['name' => 'Programa Test Cursos', 'is_active' => true]
        );

        $courseData = [
            'name' => 'SEMANA TÉCNICA: ADMINISTRACIÓN DE REDES Y COMUNICACIONES',
            'study_program_id' => $program->id,
            'certificate_type' => 'capacitacion',
            'event_name' => 'Semana Técnica 2026',
            'description' => 'Curso de capacitación técnica especializada',
            'is_active' => true,
        ];

        // Simulate Inertia request
        $response = $this->actingAs($this->admin)
            ->withHeaders(['X-Inertia' => 'true'])
            ->post(route('admin.courses.store'), $courseData);

        // Crucial test: Inertia requests must receive a redirect response, NOT plain JSON
        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('courses', [
            'name' => 'SEMANA TÉCNICA: ADMINISTRACIÓN DE REDES Y COMUNICACIONES',
            'event_name' => 'Semana Técnica 2026',
            'study_program_id' => $program->id,
            'certificate_type' => 'capacitacion',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_course(): void
    {
        $course = Course::create([
            'name' => 'Curso Original Para Actualizar',
            'certificate_type' => 'capacitacion',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeaders(['X-Inertia' => 'true'])
            ->put(route('admin.courses.update', $course), [
                'name' => 'Curso Actualizado Con Éxito',
                'certificate_type' => 'modular',
                'description' => 'Nueva descripción',
                'is_active' => false,
            ]);

        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('success');

        $course->refresh();
        $this->assertSame('Curso Actualizado Con Éxito', $course->name);
        $this->assertSame('modular', $course->certificate_type);
        $this->assertFalse($course->is_active);
    }

    public function test_admin_can_toggle_course_status(): void
    {
        $course = Course::create([
            'name' => 'Curso Para Toggle',
            'certificate_type' => 'capacitacion',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeaders(['X-Inertia' => 'true'])
            ->patch(route('admin.courses.toggle-status', $course));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $course->refresh();
        $this->assertFalse($course->is_active);
    }

    public function test_admin_can_delete_course(): void
    {
        $course = Course::create([
            'name' => 'Curso Para Eliminar',
            'certificate_type' => 'capacitacion',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeaders(['X-Inertia' => 'true'])
            ->delete(route('admin.courses.destroy', $course));

        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('courses', [
            'id' => $course->id,
        ]);
    }
}
