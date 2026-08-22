<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definir Permisos del Sistema
        $permissions = [
            'ver-dashboard'               => 'Ver Panel de Control',
            'gestionar-examenes'          => 'Gestionar Exámenes y Admisión',
            'gestionar-programas'         => 'Gestionar Programas de Estudio',
            'gestionar-tupa'              => 'Gestionar Reglamento TUPA',
            'gestionar-trabajos'          => 'Gestionar Bolsa de Trabajo',
            'gestionar-usuarios'          => 'Gestionar Usuarios',
            'gestionar-roles'             => 'Gestionar Roles y Permisos',
            'gestionar-reclamos'          => 'Gestionar Libro de Reclamaciones',
            'gestionar-empresa'           => 'Configurar Empresa e Institución',
            'gestionar-partners'          => 'Gestionar Alianzas y Partners',
            'gestionar-investigacion'     => 'Gestionar Unidad de Investigación',
            'gestionar-educacion-continua'=> 'Gestionar Educación Continua',
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::findOrCreate($name, 'web');
        }

        // 2. Definir Roles requeridos
        $rolesData = [
            'Director' => [
                'ver-dashboard', 'gestionar-examenes', 'gestionar-programas', 'gestionar-tupa',
                'gestionar-trabajos', 'gestionar-usuarios', 'gestionar-roles', 'gestionar-reclamos',
                'gestionar-empresa', 'gestionar-partners', 'gestionar-investigacion', 'gestionar-educacion-continua'
            ],
            'Administrador' => [
                'ver-dashboard', 'gestionar-examenes', 'gestionar-programas', 'gestionar-tupa',
                'gestionar-trabajos', 'gestionar-usuarios', 'gestionar-roles', 'gestionar-reclamos',
                'gestionar-empresa', 'gestionar-partners', 'gestionar-investigacion', 'gestionar-educacion-continua'
            ],
            'Admin' => [ // Compatibilidad
                'ver-dashboard', 'gestionar-examenes', 'gestionar-programas', 'gestionar-tupa',
                'gestionar-trabajos', 'gestionar-usuarios', 'gestionar-roles', 'gestionar-reclamos',
                'gestionar-empresa', 'gestionar-partners'
            ],
            'Docente' => [
                'ver-dashboard', 'gestionar-programas', 'gestionar-examenes'
            ],
            'Coordinador de Área' => [
                'ver-dashboard', 'gestionar-programas', 'gestionar-examenes'
            ],
            'Coordinador de Calidad' => [
                'ver-dashboard', 'gestionar-tupa', 'gestionar-programas', 'gestionar-reclamos'
            ],
            'Coordinador de Empleabilidad' => [
                'ver-dashboard', 'gestionar-trabajos', 'gestionar-partners'
            ],
            'Coordinador de Educación Continua' => [
                'ver-dashboard', 'gestionar-educacion-continua', 'gestionar-programas'
            ],
            'Coordinador de Investigación' => [
                'ver-dashboard', 'gestionar-investigacion'
            ],
            'Administrativo' => [
                'ver-dashboard', 'gestionar-tupa', 'gestionar-reclamos'
            ],
        ];

        foreach ($rolesData as $roleName => $assignedPermissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($assignedPermissions);
        }
    }
}
