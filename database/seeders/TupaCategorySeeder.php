<?php

namespace Database\Seeders;

use App\Models\TupaCategory;
use Illuminate\Database\Seeder;

class TupaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Trámites Académicos y Certificación',
                'icon' => 'bi-journal-check',
                'is_active' => true,
            ],
            [
                'name' => 'Matrícula, Reincorporación y Traslados',
                'icon' => 'bi-person-badge',
                'is_active' => true,
            ],
            [
                'name' => 'Evaluaciones y Exámenes',
                'icon' => 'bi-clipboard-check',
                'is_active' => true,
            ],
            [
                'name' => 'Servicios Complementarios y Carnés',
                'icon' => 'bi-card-heading',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            TupaCategory::create($category);
        }
    }
}