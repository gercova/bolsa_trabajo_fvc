<?php

namespace Tests\Feature;

use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserPasswordUpdateTest extends TestCase
{
    protected ?User $admin = null;

    protected ?User $targetUser = null;

    protected function setUp(): void
    {
        parent::setUp();

        DocumentType::firstOrCreate(
            ['id' => 1],
            ['name' => 'DNI', 'abreviation' => 'DNI', 'is_active' => true]
        );

        Role::findOrCreate('Admin', 'web');
        Role::findOrCreate('Estudiante', 'web');

        // Limpiar usuarios previos de prueba
        User::whereIn('email', [
            'admin_pwd_test@iestp.edu.pe',
            'target_pwd_test@iestp.edu.pe',
        ])->delete();

        $this->admin = User::create([
            'document_type_id' => 1,
            'dni' => '99881122',
            'names' => 'Admin Test Password',
            'email' => 'admin_pwd_test@iestp.edu.pe',
            'role' => 'Admin',
            'password' => Hash::make('adminpassword123'),
            'is_active' => true,
        ]);
        $this->admin->assignRole('Admin');

        $this->targetUser = User::create([
            'document_type_id' => 1,
            'dni' => '99881133',
            'names' => 'Target User Test',
            'email' => 'target_pwd_test@iestp.edu.pe',
            'role' => 'Estudiante',
            'password' => Hash::make('oldpassword123'),
            'is_active' => true,
        ]);
        $this->targetUser->assignRole('Estudiante');
    }

    protected function tearDown(): void
    {
        if ($this->admin) {
            $this->admin->delete();
        }

        if ($this->targetUser) {
            $this->targetUser->delete();
        }

        User::whereIn('email', [
            'admin_pwd_test@iestp.edu.pe',
            'target_pwd_test@iestp.edu.pe',
        ])->delete();

        parent::tearDown();
    }

    /**
     * Prueba que la ruta /admin/usuarios/{id}/password actualiza la contraseña exitosamente.
     */
    public function test_admin_can_update_user_password_via_admin_usuarios_route(): void
    {
        $response = $this->actingAs($this->admin)->patch("/admin/usuarios/{$this->targetUser->id}/password", [
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->targetUser->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword123!', $this->targetUser->password));
    }

    /**
     * Prueba que la ruta /admin-usuarios/{id}/password también actualiza la contraseña exitosamente.
     */
    public function test_admin_can_update_user_password_via_admin_dash_usuarios_route(): void
    {
        $response = $this->actingAs($this->admin)->patch("/admin-usuarios/{$this->targetUser->id}/password", [
            'password' => 'AnotherPassword2026#',
            'password_confirmation' => 'AnotherPassword2026#',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->targetUser->refresh();
        $this->assertTrue(Hash::check('AnotherPassword2026#', $this->targetUser->password));
    }

    /**
     * Prueba que una petición AJAX/JSON recibe una respuesta JSON 200 con success true.
     */
    public function test_admin_can_update_user_password_via_json_ajax_request(): void
    {
        $response = $this->actingAs($this->admin)->patchJson("/admin/usuarios/{$this->targetUser->id}/password", [
            'password' => 'AjaxPassword2026!',
            'password_confirmation' => 'AjaxPassword2026!',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->targetUser->refresh();
        $this->assertTrue(Hash::check('AjaxPassword2026!', $this->targetUser->password));
    }

    /**
     * Prueba que la validación falla si las contraseñas no coinciden.
     */
    public function test_validation_fails_when_passwords_do_not_match(): void
    {
        $response = $this->actingAs($this->admin)->patchJson("/admin/usuarios/{$this->targetUser->id}/password", [
            'password' => 'PasswordOne123',
            'password_confirmation' => 'PasswordTwo456',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

        // La contraseña antigua no debe haber cambiado
        $this->targetUser->refresh();
        $this->assertTrue(Hash::check('oldpassword123', $this->targetUser->password));
    }

    /**
     * Prueba que la validación falla si la contraseña tiene menos de 8 caracteres.
     */
    public function test_validation_fails_when_password_is_shorter_than_8_chars(): void
    {
        $response = $this->actingAs($this->admin)->patchJson("/admin/usuarios/{$this->targetUser->id}/password", [
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    /**
     * Prueba que al acceder por GET a /admin/usuarios/{id}/password no se produce un error 404, sino que redirige limpiamente.
     */
    public function test_get_on_password_endpoint_redirects_cleanly_without_404(): void
    {
        $response = $this->actingAs($this->admin)->get("/admin/usuarios/{$this->targetUser->id}/password");

        $response->assertRedirect(route('admin.users.index'));
        $response->assertStatus(302);
    }
}
