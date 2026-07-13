<?php

namespace Database\Seeders;

use App\Models\Charge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChargesSeeder extends Seeder
{
    public function run(): void
    {
        // Mapeo: nombre del cargo => nombre del cargo superior (null si no tiene)
        $chargesData = [
            'Docente del Programa de Estudios de Administración de Redes y Comunicaciones' => 'Coordinador del Programa de Estudios de Administración de Redes y Comunicaciones',
            'Docente del Programa de Estudios de Manejo Forestal' => 'Coordinador del Programa de Estudios de Manejo Forestal',
            'Coordinador del Programa de Estudios de Administración de Redes y Comunicaciones' => 'Jefatura de Unidad Académica',
            'Coordinador del Programa de Estudios de Manejo Forestal' => 'Jefatura de Unidad Académica',
            'Coordinadora del Programa de Estudios de Asistencia Administrativa' => 'Jefatura de Unidad Académica',
            'Jefatura de Unidad Académica' => 'Director General',
            'Docente del Programa de Estudios de Asistencia Administrativa' => 'Coordinadora del Programa de Estudios de Asistencia Administrativa',
            'Coordinador del Programa de Estudios de Producción Agropecuaria' => 'Jefatura de Unidad Académica',
            'Docente del Programa de Estudios de Producción Agropecuaria' => 'Coordinador del Programa de Estudios de Producción Agropecuaria',
            'Director General' => null,
            'Secretaria Académica' => 'Director General',
            'Coordinadora del Área de Calidad' => 'Director General',
            'Jefe Administrativo' => 'Director General',
            'Jefe de Producción' => 'Director General',
            'Coordinador del Área de Bienestar y Empleabilidad' => 'Director General',
            'Coordinador del Área de Formación Continua' => 'Director General',
            'Secretaria de Dirección General' => 'Director General',
            'Secretaria de Jefatura de Unidad Académica' => 'Jefatura de Unidad Académica',
        ];

        // 1. Insertar todos los cargos sin relación (higher_position_id = null)
        foreach (array_keys($chargesData) as $name) {
            Charge::create([
                'name'               => $name,
                'higher_position_id' => null,
            ]);
        }

        // 2. Actualizar la referencia al cargo superior
        foreach ($chargesData as $name => $superiorName) {
            if ($superiorName === null) {
                continue;
            }

            $charge = Charge::where('name', $name)->first();
            $superior = Charge::where('name', $superiorName)->first();

            if ($charge && $superior) {
                $charge->higher_position_id = $superior->id;
                $charge->save();
            }
        }
    }
}
