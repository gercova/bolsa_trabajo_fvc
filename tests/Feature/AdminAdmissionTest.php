<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAdmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_exams(): void
    {
        $response = $this->get('/admin-exams');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_admin_exams_index(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin-exams');
        
        $response->assertOk();
        $response->assertViewHas('admission');
    }

    public function test_admin_can_filter_exams_by_search(): void
    {
        $user = User::factory()->create();

        $exam1 = Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen ordinario de admisión',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        $exam2 = Admission::create([
            'period' => '2027-II',
            'total_vacancies' => 50,
            'observation' => 'Ciclo intensivo CEPRE',
            'exam_date' => '2026-08-20',
            'inscription_start_date' => '2026-06-15',
            'inscription_end_date' => '2026-08-15',
            'price' => 200.00,
            'type' => 'ordinario',
            'process' => 'cepre',
            'is_active' => true,
        ]);

        // Search for period "2026-I"
        $response = $this->actingAs($user)->get('/admin-exams?search=2026-I');
        $response->assertOk();
        $admissions = $response->viewData('admission');
        $this->assertCount(1, $admissions);
        $this->assertEquals('2026-I', $admissions->first()->period);

        // Search for observation "intensivo"
        $response = $this->actingAs($user)->get('/admin-exams?search=intensivo');
        $response->assertOk();
        $admissions = $response->viewData('admission');
        $this->assertCount(1, $admissions);
        $this->assertEquals('2027-II', $admissions->first()->period);
    }

    public function test_admin_can_filter_exams_by_process(): void
    {
        $user = User::factory()->create();

        Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen 1',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        Admission::create([
            'period' => '2026-II',
            'total_vacancies' => 50,
            'observation' => 'Examen 2',
            'exam_date' => '2026-08-20',
            'inscription_start_date' => '2026-06-15',
            'inscription_end_date' => '2026-08-15',
            'price' => 200.00,
            'type' => 'ordinario',
            'process' => 'cepre',
            'is_active' => true,
        ]);

        // Filter by process "cepre"
        $response = $this->actingAs($user)->get('/admin-exams?process=cepre');
        $response->assertOk();
        $admissions = $response->viewData('admission');
        $this->assertCount(1, $admissions);
        $this->assertEquals('cepre', $admissions->first()->process);
    }

    public function test_admin_can_filter_exams_by_type(): void
    {
        $user = User::factory()->create();

        Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen 1',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        Admission::create([
            'period' => '2026-II',
            'total_vacancies' => 50,
            'observation' => 'Examen 2',
            'exam_date' => '2026-08-20',
            'inscription_start_date' => '2026-06-15',
            'inscription_end_date' => '2026-08-15',
            'price' => 200.00,
            'type' => 'extraordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        // Filter by type "extraordinario"
        $response = $this->actingAs($user)->get('/admin-exams?type=extraordinario');
        $response->assertOk();
        $admissions = $response->viewData('admission');
        $this->assertCount(1, $admissions);
        $this->assertEquals('extraordinario', $admissions->first()->type);
    }

    public function test_admin_can_filter_exams_by_status(): void
    {
        $user = User::factory()->create();

        Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen 1',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        Admission::create([
            'period' => '2026-II',
            'total_vacancies' => 50,
            'observation' => 'Examen 2',
            'exam_date' => '2026-08-20',
            'inscription_start_date' => '2026-06-15',
            'inscription_end_date' => '2026-08-15',
            'price' => 200.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => false,
        ]);

        // Filter by status "inactive"
        $response = $this->actingAs($user)->get('/admin-exams?status=inactive');
        $response->assertOk();
        $admissions = $response->viewData('admission');
        $this->assertCount(1, $admissions);
        $this->assertFalse($admissions->first()->is_active);
    }

    public function test_admin_can_filter_exams_by_date_range(): void
    {
        $user = User::factory()->create();

        Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen 1',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        Admission::create([
            'period' => '2026-II',
            'total_vacancies' => 50,
            'observation' => 'Examen 2',
            'exam_date' => '2026-08-20',
            'inscription_start_date' => '2026-06-15',
            'inscription_end_date' => '2026-08-15',
            'price' => 200.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        // Filter by date range enclosing only the first exam
        $response = $this->actingAs($user)->get('/admin-exams?date=2026-03-01 - 2026-03-31');
        $response->assertOk();
        $admissions = $response->viewData('admission');
        $this->assertCount(1, $admissions);
        $this->assertEquals('2026-03-15', $admissions->first()->exam_date);
    }

    public function test_index_validation_fails_for_invalid_parameters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin-exams?process=invalid_process');
        $response->assertSessionHasErrors(['process']);

        $response = $this->actingAs($user)->get('/admin-exams?type=invalid_type');
        $response->assertSessionHasErrors(['type']);

        $response = $this->actingAs($user)->get('/admin-exams?status=invalid_status');
        $response->assertSessionHasErrors(['status']);
    }

    public function test_admin_can_toggle_exam_status(): void
    {
        $user = User::factory()->create();
        $exam = Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen 1',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->patch("/admin-exams/estado/{$exam->id}");
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $exam->refresh();
        $this->assertFalse($exam->is_active);
    }

    public function test_admin_can_delete_exam(): void
    {
        $user = User::factory()->create();
        $exam = Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen 1',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete("/admin-exams/{$exam->id}");
        $response->assertRedirect('/admin-exams');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('admission', [
            'id' => $exam->id,
        ]);
    }

    public function test_admin_can_access_create_exam_page(): void
    {
        $user = User::factory()->create();
        $program = StudyProgram::create([
            'name' => 'Computación e Informática',
            'slug' => 'computacion-e-informatica',
            'description' => 'Descripción',
            'details' => 'Detalles',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin-exams/crear-examen');
        
        $response->assertOk();
        $response->assertSee('Crear Nuevo Examen / CEPRE');
        $response->assertSee('Computación e Informática');
    }

    public function test_admin_can_store_new_exam(): void
    {
        $user = User::factory()->create();
        $program = StudyProgram::create([
            'name' => 'Enfermería Técnica',
            'slug' => 'enfermeria-tecnica',
            'description' => 'Descripción',
            'details' => 'Detalles',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post('/admin-exams/guardar', [
            'period' => '2026-II',
            'total_vacancies' => 120,
            'observation' => 'Nuevo examen especial',
            'exam_date' => '2026-09-10',
            'inscription_start_date' => '2026-07-01',
            'inscription_end_date' => '2026-09-05',
            'price' => 180.50,
            'type' => 'extraordinario',
            'process' => 'cepre',
            'is_active' => '1',
            'programs' => [
                [
                    'program_id' => $program->id,
                    'vacancies' => 45,
                ]
            ]
        ]);

        $response->assertRedirect('/admin-exams');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admission', [
            'period' => '2026-II',
            'total_vacancies' => 120,
            'observation' => 'Nuevo examen especial',
            'exam_date' => '2026-09-10',
            'price' => 180.50,
            'type' => 'extraordinario',
            'process' => 'cepre',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('admission_details', [
            'program_id' => $program->id,
            'vacancies' => 45,
        ]);
    }

    public function test_store_exam_validation_fails(): void
    {
        $user = User::factory()->create();

        // Send missing required fields and invalid types
        $response = $this->actingAs($user)->post('/admin-exams/guardar', [
            'period' => '',
            'total_vacancies' => -10,
            'price' => 'invalid-price',
            'type' => 'invalid-type',
            'process' => 'invalid-process',
        ]);

        $response->assertSessionHasErrors([
            'period',
            'total_vacancies',
            'price',
            'type',
            'process',
            'exam_date',
            'inscription_start_date',
            'inscription_end_date',
        ]);
    }

    public function test_admin_can_access_edit_exam_page(): void
    {
        $user = User::factory()->create();
        $exam = Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen 1',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        $program = StudyProgram::create([
            'name' => 'Contabilidad',
            'slug' => 'contabilidad',
            'description' => 'Descripción',
            'details' => 'Detalles',
            'is_active' => true,
        ]);

        $exam->admissionDetail()->create([
            'program_id' => $program->id,
            'vacancies' => 25,
        ]);

        $response = $this->actingAs($user)->get("/admin-exams/editar-examen/{$exam->id}");
        
        $response->assertOk();
        $response->assertSee('Actualizar Examen / CEPRE');
        $response->assertSee('Contabilidad');
    }

    public function test_admin_can_update_exam(): void
    {
        $user = User::factory()->create();
        $exam = Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen Antiguo',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        $program = StudyProgram::create([
            'name' => 'Secretariado Ejecutivo',
            'slug' => 'secretariado-ejecutivo',
            'description' => 'Descripción',
            'details' => 'Detalles',
            'is_active' => true,
        ]);

        $exam->admissionDetail()->create([
            'program_id' => $program->id,
            'vacancies' => 20,
        ]);

        $response = $this->actingAs($user)->put("/admin-exams/editar-examen/{$exam->id}", [
            'period' => '2026-I-Modificado',
            'total_vacancies' => 110,
            'observation' => 'Examen Actualizado',
            'exam_date' => '2026-03-20',
            'inscription_start_date' => '2026-01-20',
            'inscription_end_date' => '2026-03-15',
            'price' => 160.00,
            'type' => 'extraordinario',
            'process' => 'cepre',
            'is_active' => '1',
            'programs' => [
                [
                    'program_id' => $program->id,
                    'vacancies' => 30,
                ]
            ]
        ]);

        $response->assertRedirect('/admin-exams');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admission', [
            'id' => $exam->id,
            'period' => '2026-I-Modificado',
            'total_vacancies' => 110,
            'observation' => 'Examen Actualizado',
            'price' => 160.00,
            'type' => 'extraordinario',
            'process' => 'cepre',
        ]);

        $this->assertDatabaseHas('admission_details', [
            'admission_id' => $exam->id,
            'program_id' => $program->id,
            'vacancies' => 30,
        ]);
    }

    public function test_update_exam_validation_fails(): void
    {
        $user = User::factory()->create();
        $exam = Admission::create([
            'period' => '2026-I',
            'total_vacancies' => 100,
            'observation' => 'Examen 1',
            'exam_date' => '2026-03-15',
            'inscription_start_date' => '2026-01-15',
            'inscription_end_date' => '2026-03-10',
            'price' => 150.00,
            'type' => 'ordinario',
            'process' => 'admisión',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put("/admin-exams/editar-examen/{$exam->id}", [
            'period' => '',
            'total_vacancies' => -10,
            'price' => 'invalid-price',
        ]);

        $response->assertSessionHasErrors([
            'period',
            'total_vacancies',
            'price',
        ]);
    }
}
