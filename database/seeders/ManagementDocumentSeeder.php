<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManagementDocument;
use Carbon\Carbon;

class ManagementDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'title'             => 'MANUAL DE PERFIL DE PUESTOS 2026',
                'description'       => 'Establece y estandariza los perfiles de puestos del IESTP "Francisco Vigo Caballero", definiendo funciones, responsabilidades, requisitos y competencias, con el fin de optimizar la gestión del talento humano y elevar la calidad del servicio educativo.',
                'details'           => 'El documento contiene: Índice, Presentación, Objetivos (general y específicos), Marco normativo, Información General (identificación, creación, alcance, programas de estudio, finalidad, naturaleza, integrantes), Organigramas (Estructural y Funcional), Cuadro orgánico de plazas y cargos, Descripción de funciones a nivel de cargos (Órgano de Dirección, Área de Administración, Unidad Académica, Investigación, Formación Continua, Bienestar y Empleabilidad) y Glosario de términos.',
                'file_path'         => null,
                'validity_period'   => Carbon::create(2026, 12, 31),
                'is_active'         => true,
            ],
            [
                'title'             => 'PLAN ANUAL DE TRABAJO (PAT) 2026',
                'description'       => 'Plan Anual de Trabajo del IESTP "Francisco Vigo Caballero" para el año 2026. Transforma los objetivos del PEI en acciones concretas, asegurando la pertinencia con el sector productivo y el cumplimiento de las Condiciones Básicas de Calidad (CBC).',
                'details'           => 'El documento contiene: Presentación, Información general, Oferta educativa (nivel formativo, programas de estudio, modalidad), Identidad institucional (visión, misión, principios y valores), Diagnóstico (estudiantes matriculados y proyectados), Objetivos del PAT (vinculados a los objetivos estratégicos del PEI), Cronograma de actividades, tareas, responsables y presupuesto (Presupuesto total S/ 731,995.00), Estrategias de seguimiento y evaluación, y Anexos (ingresos y gastos proyectados).',
                'file_path'         => null,
                'validity_period'   => Carbon::create(2026, 12, 31),
                'is_active'         => true,
            ],
            [
                'title'             => 'REGLAMENTO INTERNO (RI) PERIODO 2026-2027',
                'description'     => 'Reglamento Interno del IESTP "Francisco Vigo Caballero" para el periodo 2026-2027. Regula el funcionamiento administrativo y académico, establece las normas de convivencia, y protege los derechos y obligaciones de estudiantes y personal, alineado con la Ley N° 30512.',
                'details'           => 'El documento contiene: Presentación, Marco Normativo, Título I: Disposiciones Generales, Título II: Deberes, derechos, obligaciones y estímulos de los docentes, Título III: Deberes, derechos, obligaciones y estímulos de los administrativos, Título IV: Deberes, derechos, obligaciones y estímulos de los estudiantes, Título V: Aspectos de los lineamientos académicos generales (admisión, matrícula, evaluación, certificación, titulación), Título VI: Disposiciones y acciones para la prevención, atención y sanción en caso de violencia y hostigamiento sexual, Título VII: Fuentes de financiamiento y patrimonio, Título VIII: Servicios educacionales complementarios básicos y seguimiento de egresados, y Disposiciones finales y complementarias.',
                'file_path'         => null,
                'validity_period'   => Carbon::create(2027, 12, 31),
                'is_active'         => true,
            ],
        ];

        foreach ($documents as $document) {
            ManagementDocument::create($document);
        }
    }
}