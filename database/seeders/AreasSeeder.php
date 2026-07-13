<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreasSeeder extends Seeder
{
    
    public function run(): void
    {
        $area = [
            'Director General',
            'Jefatura de Unidad Académica',
            'Coordinador del Programa de Estudios de Enfermería Técnica',
            'Docente del Programa de Estudios de Enfermería Técnica',
            'Coordinador del Programa de Estudios de Administración de Redes y Comunicaciones',
            'Docente del Programa de Estudios de Administración de Redes y Comunicaciones',
            'Coordinador del Programa de Estudios de Manejo Forestal',
            'Docente del Programa de Estudios de Manejo Forestal',
            'Coordinadora del Programa de Estudios de Asistencia Administrativa',
            'Docente del Programa de Estudios de Asistencia Administrativa',
            'Coordinador del Programa de Estudios de Producción Agropecuaria',
            'Docente del Programa de Estudios de Producción Agropecuaria',
            'Secretaria de Jefatura de Unidad Académica',
            'Secretaria Académica',
            'Coordinadora del Área de Calidad',
            'Jefe Administrativo',
            'Responsable de Patrimonio Institucional',
            'Asistente de Tesorería',
            'Administrador',
            'Contador Publico Colegiado',
            'Guardianía Nocturna',
            'Guardianía Diurna',
            'Apoyo Administrativo',
            'Personal de Campo',
            'Jefe de Producción',
            'Coordinador del Área de Bienestar y Empleabilidad',
            'Coordinador del Área de Formación Continua',
            'Secretaria de Dirección General',
            'Coordinador del Área de Investigación',
        ];

        foreach ($area as $item) {
            Area::create([
                'name' => $item,
            ]);
        }
        
    }
}
