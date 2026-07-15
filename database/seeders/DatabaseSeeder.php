<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ChargesSeeder::class);
        $this->call(AreasSeeder::class);
        $this->call(UbigeoRegionSeeder::class);
        $this->call(UbigeoProvinceSeeder::class);
        $this->call(UbigeoDistrictSeeder::class);
        $this->call(EnterpriseSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StudyProgramSeeder::class);
        $this->call(AdmissionSeeder::class);
        $this->call(HistoricalReviewSeeder::class);
        $this->call(AdmissionRequirementSeeder::class);
        $this->call(PartnersSeeder::class);
    }
}
