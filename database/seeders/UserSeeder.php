<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'dni' => '12345678',
            'names' => 'Administrador del Sistema',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'job_position' => 'Administrador',
            'ubigeo' => '010101',
            'phone' => '123456789',
            'address' => 'Av. Principal 123',
            'role' => 'Admin',
            'email_verified_at' => now(),
        ]);

        // Datos completos con nombres y apellidos correctamente formateados
        $users = [
            // Director General
            [
                'dni' => '1014494',
                'names' => 'Teodorico Ganoza Medina',
                'email' => 'quimarini23ch@gmail.com',
                'job_position' => 'Director General',
                'phone' => '979901975',
                'role' => 'Admin'
            ],

            // Docentes
            [
                'dni' => '41858599',
                'names' => 'Royer Alegria Del Castillo',
                'email' => 'roaldelc.2017@gmail.com',
                'job_position' => 'Docente',
                'phone' => '900568542',
                'role' => 'Docente'
            ],
            [
                'dni' => '46251646',
                'names' => 'Jose Luis Bartolo Ramos',
                'email' => 'joselitobarto@gmail.com',
                'job_position' => 'Docente',
                'phone' => '957176037',
                'role' => 'Docente'
            ],
            [
                'dni' => '40959908',
                'names' => 'Edwar Castro Ruiz',
                'email' => 'educr004@gmail.com',
                'job_position' => 'Docente',
                'phone' => '956703570',
                'role' => 'Docente'
            ],
            [
                'dni' => '71719922',
                'names' => 'German Cotrina Valles',
                'email' => 'germancotrina17@gmail.com',
                'job_position' => 'Docente',
                'phone' => '920307572',
                'role' => 'Docente'
            ],
            [
                'dni' => '42333874',
                'names' => 'Etel Estrella Gomez',
                'email' => 'estrellagomezetel@gmail.com',
                'job_position' => 'Docente',
                'phone' => '955502124',
                'role' => 'Docente'
            ],
            [
                'dni' => '01110179',
                'names' => 'Genry Flores Navarro',
                'email' => 'genry_flores6_@hotmail.com',
                'job_position' => 'Docente',
                'phone' => '925784436',
                'role' => 'Docente'
            ],
            [
                'dni' => '01186455',
                'names' => 'Manuel Adolfo Guerra Melendez',
                'email' => 'manueladolfoguerra22@gmail.com',
                'job_position' => 'Docente',
                'phone' => '982318678',
                'role' => 'Docente'
            ],
            [
                'dni' => '70745784',
                'names' => 'Widman Jimenez Solano',
                'email' => 'wid_ingenier20@hotmail.com',
                'job_position' => 'Docente',
                'phone' => '987759134',
                'role' => 'Docente'
            ],
            [
                'dni' => '70745776',
                'names' => 'Winker Jimenez Solano',
                'email' => 'u201520179@upc.edu.pe',
                'job_position' => 'Docente',
                'phone' => '972554330',
                'role' => 'Docente'
            ],
            [
                'dni' => '73622421',
                'names' => 'Daniel Adolfo Lopez Fernandez',
                'email' => 'lopez.fvc.2018@gmail.com',
                'job_position' => 'Docente',
                'phone' => '969666826',
                'role' => 'Docente'
            ],
            [
                'dni' => '42960630',
                'names' => 'Sara Martel Fabian',
                'email' => 'martelfabian1685@gmail.com',
                'job_position' => 'Docente',
                'phone' => '993973626',
                'role' => 'Docente'
            ],
            [
                'dni' => '25770273',
                'names' => 'Jhon Morales Ponce',
                'email' => 'jhonmoralesponce22@gmail.com',
                'job_position' => 'Docente',
                'phone' => '997236993',
                'role' => 'Docente'
            ],
            [
                'dni' => '42867623',
                'names' => 'Angel Rainey Mosquera Da Silva',
                'email' => 'angelrainey1@gmail.com',
                'job_position' => 'Docente',
                'phone' => '937384295',
                'role' => 'Docente'
            ],
            [
                'dni' => '42038635',
                'names' => 'Limber Mosquera Olortegui',
                'email' => 'limbermosolo.10@gmail.com',
                'job_position' => 'Docente',
                'phone' => '973579809',
                'role' => 'Docente'
            ],
            [
                'dni' => '22428334',
                'names' => 'Marisol Zenina Noblejas Suarez',
                'email' => 'marinoblejas63@hotmail.com',
                'job_position' => 'Docente',
                'phone' => '979286203',
                'role' => 'Docente'
            ],
            [
                'dni' => '45618816',
                'names' => 'Neyson Olivas Ortega',
                'email' => '967592572olivas@gmail.com',
                'job_position' => 'Docente',
                'phone' => '967592572',
                'role' => 'Docente'
            ],
            [
                'dni' => '42789859',
                'names' => 'Orlando Olivas Ortega',
                'email' => 'olivas_forestales@hotmail.com',
                'job_position' => 'Docente',
                'phone' => '968243016',
                'role' => 'Docente'
            ],
            [
                'dni' => '42478645',
                'names' => 'Neill Tito Ortiz Goñaz',
                'email' => 'ntitoortizg@gmail.com',
                'job_position' => 'Docente',
                'phone' => '928207505',
                'role' => 'Docente'
            ],
            [
                'dni' => '41508077',
                'names' => 'Leyber Panduro Alvarado',
                'email' => 'paleyber@gmail.com',
                'job_position' => 'Docente',
                'phone' => '988599481',
                'role' => 'Docente'
            ],
            [
                'dni' => '23018349',
                'names' => 'Mary Panduro Vasquez',
                'email' => 'negrita_09_12@hotmail.com',
                'job_position' => 'Docente',
                'phone' => '997289591',
                'role' => 'Docente'
            ],
            [
                'dni' => '23009889',
                'names' => 'Yessica Panduro Vasquez',
                'email' => 'yessica_panduro@hotmail.com',
                'job_position' => 'Docente',
                'phone' => '931084733',
                'role' => 'Docente'
            ],
            [
                'dni' => '22438844',
                'names' => 'Olimpia Pantoja Arostegui',
                'email' => 'olyprimera@hotmail.com',
                'job_position' => 'Docente',
                'phone' => '962992564',
                'role' => 'Docente'
            ],
            [
                'dni' => '45299539',
                'names' => 'Braulio Alberto Pardo Rivera',
                'email' => 'abrauliopardo22@gmail.com',
                'job_position' => 'Docente',
                'phone' => '928527525',
                'role' => 'Docente'
            ],
            [
                'dni' => '04010081',
                'names' => 'Modesto Paucar Serpa',
                'email' => 'modestopaucarserpa@gmail.com',
                'job_position' => 'Docente',
                'phone' => '942131215',
                'role' => 'Docente'
            ],
            [
                'dni' => '42428407',
                'names' => 'Romer Ponce Ramirez',
                'email' => 'romerponceramirez@gmail.com',
                'job_position' => 'Docente',
                'phone' => '944383367',
                'role' => 'Docente'
            ],
            [
                'dni' => '72269144',
                'names' => 'Elizabeth Principe Principe',
                'email' => 'elizabethprincipeprincipe@gmail.com',
                'job_position' => 'Docente',
                'phone' => '952432074',
                'role' => 'Docente'
            ],
            [
                'dni' => '44015514',
                'names' => 'Veronica Hermelinda Rojas Jara',
                'email' => 'veritojara762@gmail.com',
                'job_position' => 'Docente',
                'phone' => '944242925',
                'role' => 'Docente'
            ],
            [
                'dni' => '41311115',
                'names' => 'Eilber Martin Salazar Gastelo',
                'email' => 'martinsg301@gmail.com',
                'job_position' => 'Docente',
                'phone' => '960619361',
                'role' => 'Docente'
            ],
            [
                'dni' => '47912203',
                'names' => 'Ines Saldaña Culqui',
                'email' => 'inessaldana2608@gmail.com',
                'job_position' => 'Docente',
                'phone' => '953976298',
                'role' => 'Docente'
            ],
            [
                'dni' => '45345455',
                'names' => 'Rusbith Iliana Sanchez Dominguez',
                'email' => 'sirelove20@gmail.com',
                'job_position' => 'Docente',
                'phone' => '976464938',
                'role' => 'Docente'
            ],
            [
                'dni' => '41476770',
                'names' => 'Horlando Sulca Bermudez',
                'email' => 'horlandosulcabermudez@gmail.com',
                'job_position' => 'Docente',
                'phone' => '910201183',
                'role' => 'Docente'
            ],
            [
                'dni' => '46104578',
                'names' => 'Neyri Betsi Vizcarra Rios',
                'email' => 'neyrivizcarra@gmail.com',
                'job_position' => 'Docente',
                'phone' => '914902252',
                'role' => 'Docente'
            ],
            [
                'dni' => '45988617',
                'names' => 'Carlos Frank Cori Evaristo',
                'email' => 'frankcorie@gmail.com',
                'job_position' => 'Docente',
                'phone' => '999777498',
                'role' => 'Docente'
            ],

            // Personal Administrativo adicional del seeder anterior
            [
                'dni' => '72260117',
                'names' => 'Nathaly Katia Pardo Rivera',
                'email' => 'nathyaretzi@gmail.com',
                'job_position' => 'Asistente Administración',
                'phone' => '925616468',
                'role' => 'Administrativo'
            ],
            [
                'dni' => '80469081',
                'names' => 'Fidel Gonzales Castillo',
                'email' => 'figoca.75@gmail.com',
                'job_position' => 'Personal Administrativo Nombrado',
                'phone' => '988063810',
                'role' => 'Administrativo'
            ],
            [
                'dni' => '41308640',
                'names' => 'Nelida Atero Berrospe',
                'email' => 'nelidaatero@gmail.com',
                'job_position' => 'Seguridad y Vigilancia',
                'phone' => '918163856',
                'role' => 'Administrativo'
            ],
            [
                'dni' => '33819807',
                'names' => 'Orfelina Olortegui Rios',
                'email' => 'orfe1118@gmail.com',
                'job_position' => 'Secretaria Nombrada',
                'phone' => '935789879',
                'role' => 'Administrativo'
            ],
            [
                'dni' => '47258037',
                'names' => 'Peter Elias Caldas Sifuentes',
                'email' => 'caldassifuentespeter@gmail.com',
                'job_position' => 'Auxiliar Servicio II',
                'phone' => '972746352',
                'role' => 'Administrativo'
            ],
            [
                'dni' => '76382716',
                'names' => 'Noimi Julita Mendoza Rodriguez',
                'email' => 'iesfranciscovigocaballero@gmail.com',
                'job_position' => 'Secretaria de Dirección General',
                'phone' => '935947402',
                'role' => 'Administrativo'
            ],
            [
                'dni' => '01014470',
                'names' => 'Manuel Leonidas Mosquera Ruiz',
                'email' => 'manueleonidas57@gmail.com',
                'job_position' => 'Administrador del IESTP',
                'phone' => '921573015',
                'role' => 'Administrativo'
            ],
        ];

        // Insertar todos los usuarios
        foreach ($users as $userData) {
            User::create([
                'dni'           => $userData['dni'],
                'names'         => $userData['names'],
                'email'         => strtolower($userData['email']),
                'password'      => Hash::make('password'),
                'job_position'  => $userData['job_position'],
                'ubigeo'        => null,
                'phone'         => $userData['phone'],
                'address'       => null,
                'role'          => $userData['role'],
                'email_verified_at' => now(),
            ]);
        }
    }
}