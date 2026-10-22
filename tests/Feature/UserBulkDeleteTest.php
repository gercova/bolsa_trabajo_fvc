<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserBulkDeleteTest extends TestCase
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
            ['email' => 'admin_bulk_test@iestpfvc.edu.pe'],
            [
                'dni' => '88887777',
                'names' => 'Admin Bulk Test',
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

    public function test_guest_cannot_bulk_delete_or_count(): void
    {
        $responseDelete = $this->delete(route('admin.users.bulk-delete'), ['ids' => [1, 2]]);
        $responseDelete->assertRedirect(route('login'));

        $responseCount = $this->postJson(route('admin.users.count-bulk-delete'), ['role' => 'Estudiante']);
        $responseCount->assertUnauthorized();
    }

    public function test_view_contains_bulk_delete_elements_and_job_position_filter(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Eliminación Masiva');
        $response->assertSee('name="job_position"', false);
        $response->assertSee('Todos los puestos');
        $response->assertSee('bulkDeleteModalOpen');
    }

    public function test_admin_can_bulk_delete_users_by_explicit_ids(): void
    {
        $user1 = User::create([
            'dni' => '11112222',
            'names' => 'Usuario Prueba 1',
            'email' => 'usuario1_test@example.com',
            'password' => bcrypt('secret123'),
            'document_type_id' => 1,
            'role' => 'Estudiante',
            'job_position' => 'student/graduate',
            'is_active' => true,
        ]);

        $user2 = User::create([
            'dni' => '11112223',
            'names' => 'Usuario Prueba 2',
            'email' => 'usuario2_test@example.com',
            'password' => bcrypt('secret123'),
            'document_type_id' => 1,
            'role' => 'Estudiante',
            'job_position' => 'student/graduate',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.users.bulk-delete'), [
                'ids' => [$user1->id, $user2->id],
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'deleted_count' => 2,
        ]);

        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseMissing('users', ['id' => $user2->id]);
    }

    public function test_bulk_delete_protects_authenticated_user_from_self_deletion(): void
    {
        $user1 = User::create([
            'dni' => '11112224',
            'names' => 'Usuario Prueba 3',
            'email' => 'usuario3_test@example.com',
            'password' => bcrypt('secret123'),
            'document_type_id' => 1,
            'role' => 'Estudiante',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.users.bulk-delete'), [
                'ids' => [$this->admin->id, $user1->id],
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'deleted_count' => 1,
            'excluded_self' => true,
        ]);

        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_bulk_delete_users_by_filters(): void
    {
        $uniqueJob = 'TemporaryStaffUnique_'.uniqid();

        $user1 = User::create([
            'dni' => '33334441',
            'names' => 'Docente Filtrado 1',
            'email' => 'docente_filt1@example.com',
            'password' => bcrypt('secret123'),
            'document_type_id' => 1,
            'role' => 'Docente',
            'job_position' => $uniqueJob,
            'is_active' => false,
        ]);

        $user2 = User::create([
            'dni' => '33334442',
            'names' => 'Docente Filtrado 2',
            'email' => 'docente_filt2@example.com',
            'password' => bcrypt('secret123'),
            'document_type_id' => 1,
            'role' => 'Docente',
            'job_position' => $uniqueJob,
            'is_active' => false,
        ]);

        // Another user with different job position that should NOT be deleted
        $userOther = User::create([
            'dni' => '33334443',
            'names' => 'Otro Usuario',
            'email' => 'otro_user@example.com',
            'password' => bcrypt('secret123'),
            'document_type_id' => 1,
            'role' => 'Docente',
            'job_position' => 'OtherJobPosition',
            'is_active' => false,
        ]);

        // Count endpoint check
        $countResponse = $this->actingAs($this->admin)
            ->postJson(route('admin.users.count-bulk-delete'), [
                'job_position' => $uniqueJob,
                'status' => 'inactive',
            ]);

        $countResponse->assertOk();
        $countResponse->assertJson([
            'success' => true,
            'count' => 2,
        ]);

        // Execute bulk delete by filters
        $deleteResponse = $this->actingAs($this->admin)
            ->deleteJson(route('admin.users.bulk-delete'), [
                'by_filters' => true,
                'job_position' => $uniqueJob,
                'status' => 'inactive',
            ]);

        $deleteResponse->assertOk();
        $deleteResponse->assertJson([
            'success' => true,
            'deleted_count' => 2,
        ]);

        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseMissing('users', ['id' => $user2->id]);
        $this->assertDatabaseHas('users', ['id' => $userOther->id]);
    }
}
