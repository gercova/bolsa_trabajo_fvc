<?php

namespace Tests\Feature;

use App\Imports\AccountBalanceImport;
use App\Models\AccountBalance;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AccountBalanceManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        $docType = DocumentType::firstOrCreate(
            ['id' => 1],
            ['name' => 'DNI', 'abbreviation' => 'DNI', 'length' => 8]
        );

        $perm = Permission::firstOrCreate(['name' => 'gestionar-inversiones', 'guard_name' => 'web']);
        $role = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $role->givePermissionTo($perm);

        $user = User::firstOrCreate(
            ['email' => 'admin_investments_test@example.com'],
            [
                'document_type_id' => $docType->id,
                'dni'              => '99887755',
                'names'            => 'Admin Test Investments',
                'role'             => 'Administrador',
                'password'         => bcrypt('password'),
                'is_active'        => true,
            ]
        );

        $user->assignRole($role);

        return $user;
    }

    public function test_public_view_displays_grouped_categories_and_year_pills(): void
    {
        // Seed some records
        AccountBalance::create([
            'month'          => 'ENERO',
            'date'           => '2025-01-01',
            'receipt_number' => '567',
            'client'         => 'AILCIA EMELDA ALVARADO HERRERA',
            'description'    => 'CERTIFICACIÓN MODULAR A.A',
            'category'       => 'CERTIFICACIÓN MODULAR',
            'program_code'   => 'A.A',
            'program_name'   => 'Asistencia Administrativa',
            'amount'         => 245.00,
        ]);

        AccountBalance::create([
            'month'          => 'ENERO',
            'date'           => '2025-01-01',
            'receipt_number' => '574',
            'client'         => 'HINOSTROZA CERNA AZUCENA',
            'description'    => 'CONSTANCIA DE EGRESADO',
            'category'       => 'CONSTANCIA',
            'program_code'   => 'A.A',
            'program_name'   => 'Asistencia Administrativa',
            'amount'         => 21.00,
        ]);

        $response = $this->get('/transparencia/inversion-y-gestion?year=2025');

        $response->assertOk();
        $response->assertSee('filter-pill active', false);
        $response->assertSee('Año 2025');
        $response->assertSee('CERTIFICACIÓN MODULAR');
        $response->assertSee('CONSTANCIA');
        $response->assertSee('Detalle de Registros por Categoría');
        $response->assertSee('245.00');
        $response->assertSee('21.00');
    }

    public function test_import_correctly_parses_4_digit_years_and_peruvian_decimals(): void
    {
        $importer = new AccountBalanceImport();

        // Simulate Excel rows with 4-digit year in column B (1), commas in amount (8), and metadata row
        $rows = new Collection([
            // Header row (to be skipped)
            ['MES', 'FECHA', 'N° B/V', 'CLIENTE', 'DESCRIPCIÓN', 'CATEGORÍA', 'PROGRAMA (COD.)', 'PROGRAMA (NOMBRE)', 'MONTO (S/)', 'MOTIVO'],
            // Summary row with null amount (to be skipped)
            ['TOTAL', '', '', '', '', 'TOTAL', '', '', null, ''],
            // Real row 1: 4-digit year 2025, comma decimal S/245,00
            ['ENERO', 2025, '567', 'AILCIA EMELDA ALVARADO HERRERA', 'CERTIFICACIÓN MODULAR A.A', 'CERTIFICACIÓN MODULAR', 'A.A', 'Asistencia Administrativa', 'S/245,00', ''],
            // Real row 2: year 2025, comma decimal S/524,00
            ['ENERO', '2025', '569', 'GUIDO DENNIS RODRIGUEZ SAENZ', 'CERTIFICACION MODULAR P.A', 'CERTIFICACIÓN MODULAR', 'P.A', 'Producción Agropecuaria', 'S/524,00', ''],
            // Real row 3: year 2025, comma decimal S/21,00
            ['FEBRERO', 2025, '574', 'HINOSTROZA CERNA AZUCENA', 'CONSTANCIA DE EGRESADO', 'CONSTANCIA', 'A.A', 'Asistencia Administrativa', 'S/21,00', ''],
        ]);

        $importer->collection($rows);

        $this->assertEquals(3, $importer->importedCount);
        $this->assertDatabaseCount('account_balances', 3);

        $rec1 = AccountBalance::where('receipt_number', '567')->first();
        $this->assertNotNull($rec1);
        $this->assertEquals('2025-01-01', $rec1->date->format('Y-m-d'));
        $this->assertEquals(245.00, (float) $rec1->amount);

        $rec2 = AccountBalance::where('receipt_number', '569')->first();
        $this->assertNotNull($rec2);
        $this->assertEquals('2025-01-01', $rec2->date->format('Y-m-d'));
        $this->assertEquals(524.00, (float) $rec2->amount);

        $rec3 = AccountBalance::where('receipt_number', '574')->first();
        $this->assertNotNull($rec3);
        $this->assertEquals('2025-02-01', $rec3->date->format('Y-m-d'));
        $this->assertEquals(21.00, (float) $rec3->amount);
    }

    public function test_admin_can_clear_records_for_specific_year(): void
    {
        $admin = $this->createAdminUser();

        // 2 records in 2025
        AccountBalance::create([
            'month'          => 'ENERO',
            'date'           => '2025-01-01',
            'receipt_number' => '101',
            'client'         => 'Juan Pérez',
            'category'       => 'MATRÍCULA',
            'amount'         => 150.00,
        ]);
        AccountBalance::create([
            'month'          => 'FEBRERO',
            'date'           => '2025-02-01',
            'receipt_number' => '102',
            'client'         => 'María López',
            'category'       => 'MATRÍCULA',
            'amount'         => 150.00,
        ]);

        // 1 record in 2024
        $rec2024 = AccountBalance::create([
            'month'          => 'NOVIEMBRE',
            'date'           => '2024-11-01',
            'receipt_number' => '201',
            'client'         => 'Carlos Díaz',
            'category'       => 'CONSTANCIA',
            'amount'         => 20.00,
        ]);

        $this->assertDatabaseCount('account_balances', 3);

        // Delete only 2025 records
        $response = $this->actingAs($admin)->delete('/admin-inversiones/limpiar-tabla', [
            'year' => '2025',
        ]);

        $response->assertRedirect('/admin-inversiones');
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('account_balances', 1);
        $this->assertDatabaseHas('account_balances', ['id' => $rec2024->id]);
    }

    public function test_admin_can_clear_entire_table(): void
    {
        $admin = $this->createAdminUser();

        AccountBalance::create([
            'month'          => 'ENERO',
            'date'           => '2025-01-01',
            'receipt_number' => '101',
            'client'         => 'Juan Pérez',
            'category'       => 'MATRÍCULA',
            'amount'         => 150.00,
        ]);

        $this->assertDatabaseCount('account_balances', 1);

        // Clear all
        $response = $this->actingAs($admin)->delete('/admin-inversiones/limpiar-tabla', [
            'year' => 'all',
        ]);

        $response->assertRedirect('/admin-inversiones');
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('account_balances', 0);
    }
}
