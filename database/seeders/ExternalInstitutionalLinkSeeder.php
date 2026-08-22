<?php

namespace Database\Seeders;

use App\Models\ExternalInstitutionalLink;
use Illuminate\Database\Seeder;

class ExternalInstitutionalLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            [
                'name' => 'Registra',
                'link' => 'https://registra.minedu.gob.pe/#!/',
                'icon' => 'bi-pencil-square',      // represents registration/form
            ],
            [
                'name' => 'Titula',
                'link' => 'https://titula.minedu.gob.pe/',
                'icon' => 'bi-award',               // represents degree/certification
            ],
            [
                'name' => 'Conecta',
                'link' => 'https://conecta.minedu.gob.pe/',
                'icon' => 'bi-people',              // represents connection/community
            ],
            [
                'name' => 'Avanza',
                'link' => 'https://avanza.minedu.gob.pe/',
                'icon' => 'bi-graph-up-arrow',      // represents progress/advancement
            ],
        ];

        foreach ($links as $linkData) {
            ExternalInstitutionalLink::create(array_merge($linkData, ['is_active' => true]));
        }
    }
}