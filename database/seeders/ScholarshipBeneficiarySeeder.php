<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use App\Models\ScholarshipBeneficiary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ScholarshipBeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dir = storage_path('app/public/scholarships/beneficiaries');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $file1 = 'scholarships/beneficiaries/padron_2026-i_ejemplo.pdf';
        $fullPath1 = storage_path('app/public/' . $file1);
        $size1 = File::exists($fullPath1) ? File::size($fullPath1) : 245000;

        $file2 = 'scholarships/beneficiaries/padron_2026-ii_ejemplo.pdf';
        $fullPath2 = storage_path('app/public/' . $file2);
        $size2 = File::exists($fullPath2) ? File::size($fullPath2) : 310000;

        $firstScholarship = Scholarship::first();

        ScholarshipBeneficiary::updateOrCreate(
            [
                'academic_period'   => '2026-I',
                'title'             => 'Padrón Oficial de Estudiantes Beneficiarios de Becas y Exoneraciones 2026-I',
            ],
            [
                'description'       => 'Relación oficial general de alumnos beneficiados con becas de excelencia académica, convenios y exoneraciones por ley para el semestre académico 2026-I.',
                'resolution_number' => 'R.D. N° 048-2026-IESTP-FVC',
                'file_path'         => $file1,
                'file_size'         => $size1,
                'publication_date'  => '2026-03-15',
                'scholarship_id'    => null, // General / Todas las modalidades
                'is_active'         => true,
                'sort_order'        => 1,
            ]
        );

        ScholarshipBeneficiary::updateOrCreate(
            [
                'academic_period'   => '2026-II',
                'title'             => 'Padrón Preliminar de Beneficiarios de Becas Institucionales 2026-II',
            ],
            [
                'description'       => 'Nómina aprobada de estudiantes admitidos con exoneración arancelaria y reserva de vacantes para el semestre académico 2026-II.',
                'resolution_number' => 'R.D. N° 082-2026-IESTP-FVC',
                'file_path'         => $file2,
                'file_size'         => $size2,
                'publication_date'  => '2026-08-20',
                'scholarship_id'    => $firstScholarship ? $firstScholarship->id : null,
                'is_active'         => true,
                'sort_order'        => 2,
            ]
        );
    }
}
