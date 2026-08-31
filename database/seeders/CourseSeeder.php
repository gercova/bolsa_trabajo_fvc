<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Itinerary;
use App\Models\Module;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course = Course::create([
            'name'          => 'INGLÉS A NIVEL BÁSICO',
            'description'   => 'Inglés básico para todos los programas de los ciclos III - V',
            'is_active'     => true,
        ]);

        $modulesData = [
            [
                'course_id' => $course->id,
                'name'      => 'Módulo: I',
                'credits'   => 3,
                'is_active' => true,
            ],
            [
                'course_id' => $course->id,
                'name'      => 'Módulo: II',
                'credits'   => 3,
                'is_active' => true,
            ],
        ];

        $modules = [];
        foreach ($modulesData as $moduleData) {
            $modules[] = Module::create($moduleData);
        }

        $itineraries = [
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'Greatings and farewells',
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'The numbers 0 - 1,000',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'The numbers 1,000 - 999,999',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'The time (What time is it?)',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'Days and Celebres detes',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'The alphabet',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'The alphabet (Spelling)',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'Verb to be in present time in A.N.I Form',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'Verb to be in past and futuro A.N.I.form',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'Possessive Adjectives',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'The adjectives',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[0]->id,
                'name'      => 'There is and there are A.N.I form',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'Demostrative Pronuons A.N.I form',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'Regular and Irregular verbs',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'Kinds Preposition of places',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'Kind Preposition of Time',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'Countable and uncountable nouns',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'How much and Many',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'What questions',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'Comparative and Superlative',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'Simple Present sentence',    
            ],
            [
                'course_id' => $course->id,
                'module_id' => $modules[1]->id,
                'name'      => 'Simple past Sentence',    
            ],
        ];

        foreach ($itineraries as $itineraryData) {
            Itinerary::create($itineraryData);
        }
    }
}
