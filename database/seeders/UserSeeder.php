<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador (existente)
        User::create([
            'dni'           => '12345678',
            'names'         => 'Administrador del Sistema',
            'email'         => 'admin@example.com',
            'password'      => Hash::make('password'),
            'job_position'  => 'Administrador',
            'ubigeo'        => '010101',
            'phone'         => '123456789',
            'address'       => 'Av. Principal 123',
            'role'          => 'Admin',
            'email_verified_at' => now(),
        ]);

        // Datos extraídos del Excel (32 registros)
        $usersData = [
            [
                'dni' => '73622421',
                'email' => 'lopez.fvc.2018@gmail.com',
                'names_raw' => 'LÓPEZ FERNÁNDEZ DANIEL ADOLFO',
                'job_position' => 'Docente',
                'function' => 'DOCENTE',
                'phone' => '969666826',
                'address' => 'Barrio San José km3',
            ],
            [
                'dni' => '46614684',
                'email' => 'rivasgutierrezdaeskafacori@gmail.com',
                'names_raw' => 'RIVAS GUTIERREZ ESTEPHANY DEL CARMEN',
                'job_position' => 'DOCENTE',
                'function' => 'DOCENTE',
                'phone' => '965074592',
                'address' => 'JR. LOS CEDROS 002-003A',
            ],
            [
                'dni' => '45299539',
                'email' => 'abrauliopardo22@gmail.com',
                'names_raw' => 'Pardo Rivera Braulio Alberto',
                'job_position' => 'Coordinador del programa de estudios de Enfermería Técnica',
                'function' => 'DOCENTE',
                'phone' => '928527525',
                'address' => 'Jr. Azangaro cdra 6',
            ],
            [
                'dni' => '42418923',
                'email' => 'paredestorresemanuel@gmail.com',
                'names_raw' => 'PAREDES TORRES EMANUEL',
                'job_position' => 'Coordinador de ÁREA',
                'function' => 'DOCENTE',
                'phone' => '945057350',
                'address' => 'Jr Athaualpa 1412',
            ],
            [
                'dni' => '44015514',
                'email' => 'veritojara762@gmail.com',
                'names_raw' => 'Rojas Jara Verónica Hermelinda',
                'job_position' => 'Docente',
                'function' => 'DOCENTE',
                'phone' => '944242925',
                'address' => 'JR. Miguel Grau #475',
            ],
            [
                'dni' => '47507601',
                'email' => '47507601@sanmarcelo.edu.pe',
                'names_raw' => 'Melgarejo venancio SHERLY SOLEDAD',
                'job_position' => 'DOCENTE',
                'function' => 'DOCENTE',
                'phone' => '912417184',
                'address' => 'SANTA SERAFINA A-8',
            ],
            [
                'dni' => '42428407',
                'email' => 'romerponceramirez@gmail.com',
                'names_raw' => 'Ponce Ramírez Romer',
                'job_position' => 'Jefatura de Produccion',
                'function' => 'DOCENTE',
                'phone' => '944383367',
                'address' => 'JR. Hipólito Unanue Cdra 3',
            ],
            [
                'dni' => '46104578',
                'email' => 'neyrivizcarra@gmail.com',
                'names_raw' => 'VIZCARRA RIOS NEYRI BETSI',
                'job_position' => 'SECRETARIO ACADÉMICO',
                'function' => 'DOCENTE',
                'phone' => '914902252',
                'address' => 'PUEBLO SAN JUAN KM 4',
            ],
            [
                'dni' => '72260117',
                'email' => 'nathyaretzi@gmail.com',
                'names_raw' => 'Pardo Rivera Nathaly Katia',
                'job_position' => 'Asistente Administración',
                'function' => 'ADMINISTRATIVO',
                'phone' => '925616468',
                'address' => 'Alto Pampayacu /Uchiza',
            ],
            [
                'dni' => '72269144',
                'email' => 'elizabethprincipeprincipe@gmail.com',
                'names_raw' => 'Principe Principe Elizabeth',
                'job_position' => 'Docente',
                'function' => 'DOCENTE',
                'phone' => '952432074',
                'address' => 'Av.Ricardo Palma CDRA 13',
            ],
            [
                'dni' => '70745784',
                'email' => '70745784w@gmail.com',
                'names_raw' => 'JIMENEZ SOLANO WIDMAN',
                'job_position' => 'DOCENTE',
                'function' => 'DOCENTE',
                'phone' => '987759134',
                'address' => 'Uchiza',
            ],
            [
                'dni' => '80469081',
                'email' => 'figoca.75@gmail.com',
                'names_raw' => 'Gonzales castillo Fidel',
                'job_position' => 'Personal administrativo nombrado',
                'function' => 'ADMINISTRATIVO',
                'phone' => '988063810',
                'address' => 'Avenida Carlos Arevalos lote 12 manzana 25 uchiza',
            ],
            [
                'dni' => '44377927',
                'email' => 'hgmorenomiranda@gmail.com',
                'names_raw' => 'Haneylyn Guisselle Moreno Miranda',
                'job_position' => 'Área de calidad',
                'function' => 'DOCENTE',
                'phone' => '977126478',
                'address' => 'Av. Atahualpa',
            ],
            [
                'dni' => '41308640',
                'email' => 'nelidaatero@gmail.com',
                'names_raw' => 'Atero Berrospe Nelida',
                'job_position' => 'Seguridad y vigilancia',
                'function' => 'ADMINISTRATIVO',
                'phone' => '918163856',
                'address' => 'Av.Ricardo palma s/n',
            ],
            [
                'dni' => '41508077',
                'email' => 'paleyber@gmail.com',
                'names_raw' => 'PANDURO ALVARADO LEYBER',
                'job_position' => 'MAGISTER EN ING. DE SISTEMAS',
                'function' => 'DOCENTE',
                'phone' => '988599481',
                'address' => 'AA. RICARDO PALMA CDRA 8',
            ],
            [
                'dni' => '42789859',
                'email' => 'orlandoolivas813@gmail.com',
                'names_raw' => 'Olivas Ortega Orlando',
                'job_position' => 'Coordinador de programa',
                'function' => 'DOCENTE',
                'phone' => '968243016',
                'address' => 'Av. Carlos Arevalo cuadra 10',
            ],
            [
                'dni' => '33819807',
                'email' => 'orfe1118@gmail.com',
                'names_raw' => 'Olortegui rios orfelina',
                'job_position' => 'Secretaria nombrada',
                'function' => 'ADMINISTRATIVO',
                'phone' => '935789879',
                'address' => 'Valle hermoso S/N uchiza',
            ],
            [
                'dni' => '25770273',
                'email' => 'jhonmoralesponce22@gmail.com',
                'names_raw' => 'MORALES PONCE JHON',
                'job_position' => 'JEFE DE IMAGEN INSTITUCIONAL',
                'function' => 'DOCENTE',
                'phone' => '997 236 993',
                'address' => 'AV. VALLE HERMOSO S/N',
            ],
            [
                'dni' => '41476770',
                'email' => 'horlandosulcabermudez@gmail.com',
                'names_raw' => 'SULCA BERMUDEZ HORLANDO',
                'job_position' => 'Docente',
                'function' => 'DOCENTE',
                'phone' => '910201183',
                'address' => 'Barrio el Bosque Uchiza',
            ],
            [
                'dni' => '42690630',
                'email' => 'martelfabian1685@gmail.com',
                'names_raw' => 'Martel Fabian Sara',
                'job_position' => 'Superior tecnico',
                'function' => 'DOCENTE',
                'phone' => '993973626',
                'address' => 'Jr.besares s/n Monzón',
            ],
            [
                'dni' => '72165750',
                'email' => 'joanyalpess@gmail.com',
                'names_raw' => 'Alpes Sifuentes Joany',
                'job_position' => 'Jefatura de unidad de bienestar e empleabilidad',
                'function' => 'DOCENTE',
                'phone' => '917492694',
                'address' => 'Pasaje naranjillo s/n yanag rosabero',
            ],
            [
                'dni' => '70745776',
                'email' => '70745776w@gmail.com',
                'names_raw' => 'Winker Jimenez Solano',
                'job_position' => 'Psicopedagogía',
                'function' => 'DOCENTE',
                'phone' => '972554330',
                'address' => 'Jr. Atahualpa a/n',
            ],
            [
                'dni' => '47912203',
                'email' => 'inessc08@gmail.com',
                'names_raw' => 'SALDAÑA CULQUI INES',
                'job_position' => 'JEFA DE FORMACIÓN CONTINUA',
                'function' => 'DOCENTE',
                'phone' => '953976298',
                'address' => 'Jr. Atahualpa #5. Uchiza',
            ],
            [
                'dni' => '47258037',
                'email' => 'caldassifuentespeter@gmail.com',
                'names_raw' => 'Caldas Sifuentes Peter Elias',
                'job_position' => 'Auxiliar servicio II',
                'function' => 'ADMINISTRATIVO',
                'phone' => '972746352',
                'address' => 'Caserío San Francisco',
            ],
            [
                'dni' => '41311115',
                'email' => 'martinsg301@gmail.com',
                'names_raw' => 'SALAZAR GASTELO EILBER MARTIN',
                'job_position' => 'JEFE DE LA UNIDAD DE INVESTIGACIÓN',
                'function' => 'DOCENTE',
                'phone' => '960619361',
                'address' => 'Jr. SUCRE 434',
            ],
            [
                'dni' => '45345455',
                'email' => 'sirelove20@gmail.com',
                'names_raw' => 'Sanchez Dominguez Rusbith Iliana',
                'job_position' => 'Docente',
                'function' => 'DOCENTE',
                'phone' => '976464938',
                'address' => 'El porvenir km9',
            ],
            [
                'dni' => '44025884',
                'email' => 'rp9453354@gmail.com',
                'names_raw' => 'PEREZ VASQUEZ RICHARD',
                'job_position' => 'DOCENTE',
                'function' => 'DOCENTE',
                'phone' => '977886659',
                'address' => 'Av. Carlos Arevalo N° 1210 - UCHIZA',
            ],
            [
                'dni' => '40959908',
                'email' => 'educr004@gmail.com',
                'names_raw' => 'Castro Ruiz Edwar',
                'job_position' => 'Docente',
                'function' => 'DOCENTE',
                'phone' => '956703570',
                'address' => 'Tupac - Uchiza',
            ],
            [
                'dni' => '23018349',
                'email' => 'vasquezpanduromary@gmail.com',
                'names_raw' => 'Panduro Vásquez Mary',
                'job_position' => 'Coordinadora',
                'function' => 'DOCENTE',
                'phone' => '997289591',
                'address' => 'Jr. Los Cafetos cdra.03 santa lucia',
            ],
            [
                'dni' => '76382716',
                'email' => 'iesfranciscovigocaballero@gmail.com',
                'names_raw' => 'MENDOZA RODRÍGUEZ NOIMÍ JULITA',
                'job_position' => 'SECRETARIA DE DIRECCIÓN GENERAL',
                'function' => 'ADMINISTRATIVO',
                'phone' => '935947402',
                'address' => 'Caserío San Juan Km-4',
            ],
            [
                'dni' => '41858599',
                'email' => 'roaldelc.2017@gmail.com',
                'names_raw' => 'ALEGRIA DEL CASTILLO ROYER',
                'job_position' => '( SUPERIOR) ING. RECURSOS NATURALES RENOVABLES MENCION FORESTALES',
                'function' => 'DOCENTE',
                'phone' => '900568542',
                'address' => 'JIRON JOSE GALVEZ',
            ],
            [
                'dni' => '01014470',
                'email' => 'manueleonidas57@gmail.com',
                'names_raw' => 'MOSQUERA RUIZ MANUEL LEONIDAS',
                'job_position' => 'Administrador del I.E.S.T.P "Francisco Vigo Caballero"',
                'function' => 'ADMINISTRATIVO',
                'phone' => '921573015',
                'address' => 'Av. Atahualpa N°850',
            ],
        ];

        foreach ($usersData as $data) {
            User::create([
                'dni' => $data['dni'],
                'names' => $this->formatFullName($data['names_raw']),
                'email' => strtolower($data['email']),
                'password' => Hash::make('password'), // o Hash::make($data['dni'])
                'job_position' => $data['job_position'],
                'ubigeo' => null,
                'phone' => $data['phone'],
                'address' => $data['address'],
                'role' => $this->mapRole($data['function']), // devuelve minúsculas
                'email_verified_at' => now(),
            ]);
        }
    }

    /**
     * Convierte "APELLIDO1 APELLIDO2 NOMBRE1 NOMBRE2..." a "Nombres Apellidos"
     */
    private function formatFullName(string $raw): string
    {
        // Separar por espacios y eliminar vacíos
        $parts = array_values(array_filter(explode(' ', trim($raw))));
        if (count($parts) < 2) {
            return $this->mbUcwords(strtolower($raw));
        }

        // Los dos primeros son apellidos, el resto nombres
        $lastNames = array_slice($parts, 0, 2);
        $firstNames = array_slice($parts, 2);

        // Capitalizar cada parte (con soporte UTF-8)
        $lastNames = array_map(fn($p) => $this->mbUcwords(mb_strtolower($p, 'UTF-8')), $lastNames);
        $firstNames = array_map(fn($p) => $this->mbUcwords(mb_strtolower($p, 'UTF-8')), $firstNames);

        // Unir: nombres + apellidos
        return implode(' ', $firstNames) . ' ' . implode(' ', $lastNames);
    }

    /**
     * Capitaliza la primera letra de cada palabra (UTF-8).
     */
    private function mbUcwords(string $string): string
    {
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Mapea la función al rol (en minúsculas para coincidir con el enum).
     */
    private function mapRole(string $function): string
    {
        $func = strtoupper(trim($function));
        if (str_contains($func, 'DOCENTE')) {
            return 'docente';
        }
        if (str_contains($func, 'ADMINISTRATIVO')) {
            return 'administrativo';
        }
        return 'docente'; // valor por defecto
    }
}