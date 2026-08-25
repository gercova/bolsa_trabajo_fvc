<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentType::create(['name' => 'Documento Nacional de Identidad', 'abreviation' => 'DNI', 'is_active' => true]);
        DocumentType::create(['name' => 'Carnet de Extranjería', 'abreviation' => 'CE', 'is_active' => true]);
        DocumentType::create(['name' => 'Pasaporte', 'abreviation' => 'PAS', 'is_active' => true]);
    }
}
