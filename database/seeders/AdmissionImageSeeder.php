<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class AdmissionImageSeeder extends Seeder
{
    /**
     * Seed hero banner images for the CEPRE and Examen de Admisión public pages.
     *
     * Uses imageable_type as a plain string key ('cepre' / 'admision') and
     * imageable_id = 1 as a fixed identifier for global/singleton banners.
     */
    public function run(): void
    {
        // Clean existing banner images
        Image::whereIn('imageable_type', ['cepre', 'admision'])->delete();

        // CEPRE hero banner
        Image::create([
            'path'           => 'images/cepre_hero_banner.png',
            'is_main'        => true,
            'imageable_type' => 'cepre',
            'imageable_id'   => 1,
        ]);

        // Examen de Admisión hero banner
        Image::create([
            'path'           => 'images/admission_hero_banner.png',
            'is_main'        => true,
            'imageable_type' => 'admision',
            'imageable_id'   => 1,
        ]);
    }
}
