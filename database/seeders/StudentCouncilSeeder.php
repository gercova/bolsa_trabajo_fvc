<?php

namespace Database\Seeders;

use App\Models\StudentCouncil;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentCouncilSeeder extends Seeder
{
    public function run(): void
    {
        // Mapeo de nombres de programa (como vienen en la imagen) a los nombres exactos en la BD
        $programMapping = [
            'Asistencia Administrativa'        => 'Asistencia Administrativa',
            'Manejo Forestal'                  => 'Manejo Forestal',
            'Ad. Redes y Comunicación'         => 'Administración de Redes y Comunicaciones',
            'Enfermería Técnica'               => 'Enfermería Técnica',
            'Producción Agropecuaria'          => 'Producción Agropecuaria',
        ];

        // Datos extraídos de la imagen (orden, cargo, nombres, ciclo, DNI, celular, programa)
        $members = [
            [
                'position'    => 'Presidente',
                'names'       => 'Jhesmyt Jhunday Curo Cuchilla',  // ya en formato Nombres Apellidos
                'dni'         => '61543597',
                'phone'       => '930520821',
                'program_key' => 'Asistencia Administrativa',
            ],
            [
                'position'    => 'Vice Presidente',
                'names'       => 'Olinda Caldas Atalaya',
                'dni'         => '47753787',
                'phone'       => '969175240',
                'program_key' => 'Manejo Forestal',
            ],
            [
                'position'    => 'Secretaria de Organización',
                'names'       => 'Aylley Kaory Rengifo Pinche',
                'dni'         => '62906558',
                'phone'       => '940816853',
                'program_key' => 'Ad. Redes y Comunicación',
            ],
            [
                'position'    => 'Secretaria de Defensa de Derechos del Estudiante',
                'names'       => 'Ana Paula Ambrosio Ramos',
                'dni'         => '70507255',
                'phone'       => '913612983',
                'program_key' => 'Asistencia Administrativa',
            ],
            [
                'position'    => 'Secretaria de Bienestar Social',
                'names'       => 'Gary Antonio Rodríguez Sánchez',
                'dni'         => '62071952',
                'phone'       => '962399479',
                'program_key' => 'Ad. Redes y Comunicación',
            ],
            [
                'position'    => 'Secretaria de Prensa y Propaganda',
                'names'       => 'Brigitte Jasmin Olano Haro',
                'dni'         => '61869760',
                'phone'       => '963458826',
                'program_key' => 'Asistencia Administrativa',
            ],
            [
                'position'    => 'Secretaria de Economía',
                'names'       => 'Epifania Chombo Loya',
                'dni'         => '60683279',
                'phone'       => '982065232',
                'program_key' => 'Enfermería Técnica',
            ],
            [
                'position'    => 'Secretaria de Actas y Archivos',
                'names'       => 'Cámen Yesenia Reynoso Pinedo',
                'dni'         => '61575125',
                'phone'       => '931265847',
                'program_key' => 'Producción Agropecuaria',
            ],
            [
                'position'    => 'Secretaria de Deportes',
                'names'       => 'Aydee Yesenia Melgarejo Poso',
                'dni'         => '60125653',
                'phone'       => '985220692',
                'program_key' => 'Producción Agropecuaria',
            ],
            [
                'position'    => 'Secretaria de Arte y Cultura',
                'names'       => 'Jordy Jimmy Tello Ponce',
                'dni'         => '73307947',
                'phone'       => '957520702',
                'program_key' => 'Enfermería Técnica',
            ],
            [
                'position'    => 'Secretaria de Disciplina',
                'names'       => 'Maelith Daelith Torrejón Benancio',
                'dni'         => '62519762',
                'phone'       => '935804390',
                'program_key' => 'Asistencia Administrativa',
            ],
            [
                'position'    => 'Secretaria de Derechos Humanos',
                'names'       => 'Lincoln Ríos Mejía',
                'dni'         => '60036460',
                'phone'       => '912908750',
                'program_key' => 'Producción Agropecuaria',
            ],
            [
                'position'    => 'Secretaria de Ecología y Medio Ambiente',
                'names'       => 'Recier Danilo Campos Jara',
                'dni'         => '60036444',
                'phone'       => '917860794',
                'program_key' => 'Manejo Forestal',
            ],
            [
                'position'    => 'Secretaria Relaciones Nacionales y Exteriores',
                'names'       => 'Maela Cerón Pashanasi',
                'dni'         => '60402036',
                'phone'       => '902963246',
                'program_key' => 'Enfermería Técnica',
            ],
        ];

        // Período académico del consejo (según título de la imagen)
        $academicPeriod = '2026-2027';

        foreach ($members as $member) {
            // 1. Obtener o crear el usuario (por DNI)
            $user = User::firstOrCreate(
                ['dni' => $member['dni']],
                [
                    'names'            => $member['names'],
                    'email'            => 'estudiante_' . $member['dni'] . '@iestp.edu.pe',
                    'password'         => Hash::make('password'),
                    'job_position'     => $member['position'],
                    'phone'            => $member['phone'],
                    'role'             => 'Estudiante',
                    'email_verified_at' => now(),
                ]
            );

            // 2. Obtener el programa de estudios usando el mapeo
            $programName = $programMapping[$member['program_key']] ?? null;
            if (!$programName) {
                $this->command->warn("Programa no mapeado: {$member['program_key']} para el usuario {$member['dni']}");
                continue;
            }

            $program = StudyProgram::where('name', $programName)->first();
            if (!$program) {
                $this->command->warn("Programa no encontrado: {$programName} para el usuario {$member['dni']}");
                continue;
            }

            // 3. Crear el registro en student_councils
            StudentCouncil::firstOrCreate(
                [
                    'user_id'          => $user->id,
                    'academic_period'  => $academicPeriod,
                ],
                [
                    'study_program_id' => $program->id,
                    'name'             => $user->names, // o $member['names']
                    'position'         => $member['position'],
                    'is_active'        => true,
                ]
            );
        }
    }
}