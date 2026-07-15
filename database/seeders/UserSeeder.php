<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuario administrador (fijo)
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

        // 2. Datos del nuevo Excel (34 registros)
        $newExcelData = [
            ['dni' => '1014494',   'names_raw' => 'GANOZA MEDINA TEODORICO',        'type' => 'DIRECTOR GENERAL', 'phone' => '979901975',  'email' => 'quimarini23ch@gmail.com'],
            ['dni' => '41858599',  'names_raw' => 'ALEGRIA DEL CASTILLO ROYER',      'type' => 'DOCENTE',          'phone' => '900568542',  'email' => 'roaldelc.2017@gmail.com'],
            ['dni' => '46251646',  'names_raw' => 'BARTOLO RAMOS JOSE LUIS',         'type' => 'DOCENTE',          'phone' => '957176037',  'email' => 'joselitobarto@gmail.com'],
            ['dni' => '40959908',  'names_raw' => 'CASTRO RUIZ EDWAR',               'type' => 'DOCENTE',          'phone' => '956703570',  'email' => 'educr004@gmail.com'],
            ['dni' => '71719922',  'names_raw' => 'COTRINA VALLES GERMAN',           'type' => 'DOCENTE',          'phone' => '920307572',  'email' => 'germancotrina17@gmail.com'],
            ['dni' => '42333874',  'names_raw' => 'ESTRELLA GOMEZ ETEL',             'type' => 'DOCENTE',          'phone' => '955502124',  'email' => 'estrellagomezetel@gmail.com'],
            ['dni' => '01110179',  'names_raw' => 'FLORES NAVARRO GENRY',            'type' => 'DOCENTE',          'phone' => '925784436',  'email' => 'genry_flores6_@hotmail.com'],
            ['dni' => '01186455',  'names_raw' => 'GUERRA MELENDEZ MANUEL ADOLFO',   'type' => 'DOCENTE',          'phone' => '982318678',  'email' => 'manueladolfoguerra22@gmail.com'],
            ['dni' => '70745784',  'names_raw' => 'JIMENEZ SOLANO WIDMAN',           'type' => 'DOCENTE',          'phone' => '987759134',  'email' => 'wid_ingenier20@hotmail.com'],
            ['dni' => '70745776',  'names_raw' => 'JIMENEZ SOLANO WINKER',           'type' => 'DOCENTE',          'phone' => '972554330',  'email' => 'u201520179@upc.edu.pe'],
            ['dni' => '73622421',  'names_raw' => 'LOPEZ FERNANDEZ DANIEL ADOLFO',   'type' => 'DOCENTE',          'phone' => '969666826',  'email' => 'lopez.fvc.2018@gmail.com'],
            ['dni' => '42960630',  'names_raw' => 'MARTEL FABIAN SARA',              'type' => 'DOCENTE',          'phone' => '993973626',  'email' => 'martelfabian1685@gmail.com'],
            ['dni' => '25770273',  'names_raw' => 'MORALES PONCE JHON',              'type' => 'DOCENTE',          'phone' => '997236993',  'email' => 'jhonmoralesponce22@gmail.com'],
            ['dni' => '42867623',  'names_raw' => 'MOSQUERA DA SILVA ANGEL RAINEY',  'type' => 'DOCENTE',          'phone' => '937384295',  'email' => 'angelrainey1@gmail.com'],
            ['dni' => '42038635',  'names_raw' => 'MOSQUERA OLORTEGUI LIMBER',       'type' => 'DOCENTE',          'phone' => '973579809',  'email' => 'limbermosolo.10@gmail.com'],
            ['dni' => '22428334',  'names_raw' => 'NOBLEJAS SUÁREZ MARISOL ZENINA',  'type' => 'DOCENTE',          'phone' => '979286203',  'email' => 'marinoblejas63@hotmail.com'],
            ['dni' => '45618816',  'names_raw' => 'OLIVAS ORTEGA NEYSON',            'type' => 'DOCENTE',          'phone' => '967592572',  'email' => '967592572olivas@gmail.com'],
            ['dni' => '42789859',  'names_raw' => 'OLIVAS ORTEGA ORLANDO',           'type' => 'DOCENTE',          'phone' => '968243016',  'email' => 'olivas_forestales@hotmail.com'],
            ['dni' => '42478645',  'names_raw' => 'ORTIZ GOÑAZ NEILL TITO',          'type' => 'DOCENTE',          'phone' => '928207505',  'email' => 'ntitoortizg@gmail.com'],
            ['dni' => '41508077',  'names_raw' => 'PANDURO ALVARADO LEYBER',         'type' => 'DOCENTE',          'phone' => '988599481',  'email' => 'paleyber@gmail.com'],
            ['dni' => '23018349',  'names_raw' => 'PANDURO VASQUEZ MARY',            'type' => 'DOCENTE',          'phone' => '997289591',  'email' => 'negrita_09_12@hotmail.com'],
            ['dni' => '23009889',  'names_raw' => 'PANDURO VASQUEZ YESSICA',         'type' => 'DOCENTE',          'phone' => '931084733',  'email' => 'yessica_panduro@hotmail.com'],
            ['dni' => '22438844',  'names_raw' => 'PANTOJA AROSTEGUI OLIMPIA',       'type' => 'DOCENTE',          'phone' => '962992564',  'email' => 'olyprimera@hotmail.com'],
            ['dni' => '45299539',  'names_raw' => 'PARDO RIVERA BRAULIO ALBERTO',    'type' => 'DOCENTE',          'phone' => '928527525',  'email' => 'abrauliopardo22@gmail.com'],
            ['dni' => '04010081',  'names_raw' => 'PAUCAR SERPA MODESTO',            'type' => 'DOCENTE',          'phone' => '942131215',  'email' => 'modestopaucarserpa@gmail.com'],
            ['dni' => '42428407',  'names_raw' => 'PONCE RAMIREZ ROMER',             'type' => 'DOCENTE',          'phone' => '944383367',  'email' => 'romerponceramirez@gmail.com'],
            ['dni' => '72269144',  'names_raw' => 'PRINCIPE PRINCIPE ELIZABETH',     'type' => 'DOCENTE',          'phone' => '952432074',  'email' => 'elizabethprincipeprincipe@gmail.com'],
            ['dni' => '44015514',  'names_raw' => 'ROJAS JARA VERONICA HERMELINDA',  'type' => 'DOCENTE',          'phone' => '944242925',  'email' => 'veritojara762@gmail.com'],
            ['dni' => '41311115',  'names_raw' => 'SALAZAR GASTELO EILBER MARTIN',   'type' => 'DOCENTE',          'phone' => '960619361',  'email' => 'martinsg301@gmail.com'],
            ['dni' => '47912203',  'names_raw' => 'SALDAÑA CULQUI INES',             'type' => 'DOCENTE',          'phone' => '953976298',  'email' => 'inessaldana2608@gmail.com'],
            ['dni' => '45345455',  'names_raw' => 'SANCHEZ DOMINGUEZ RUSBITH ILIANA','type' => 'DOCENTE',          'phone' => '976464938',  'email' => 'sirelove20@gmail.com'],
            ['dni' => '41476770',  'names_raw' => 'SULCA BERMUDEZ HORLANDO',         'type' => 'DOCENTE',          'phone' => '910201183',  'email' => 'horlandosulcabermudez@gmail.com'],
            ['dni' => '46104578',  'names_raw' => 'VIZCARRA RIOS NEYRI BETSI',       'type' => 'DOCENTE',          'phone' => '914902252',  'email' => 'neyrivizcarra@gmail.com'],
            ['dni' => '45988617',  'names_raw' => 'CORI EVARISTO CARLOS FRANK',      'type' => 'DOCENTE',          'phone' => '999777498',  'email' => 'frankcorie@gmail.com'],
        ];

        // 3. Datos del UserSeeder anterior (32 registros, excluyendo el admin)
        $oldSeederData = [
            ['dni' => '73622421', 'names_raw' => 'LÓPEZ FERNÁNDEZ DANIEL ADOLFO',       'job_position' => 'Docente',                'phone' => '969666826', 'email' => 'lopez.fvc.2018@gmail.com',          'address' => 'Barrio San José km3', 'role' => 'docente'],
            ['dni' => '46614684', 'names_raw' => 'RIVAS GUTIERREZ ESTEPHANY DEL CARMEN','job_position' => 'DOCENTE',                 'phone' => '965074592', 'email' => 'rivasgutierrezdaeskafacori@gmail.com', 'address' => 'JR. LOS CEDROS 002-003A', 'role' => 'docente'],
            ['dni' => '45299539', 'names_raw' => 'Pardo Rivera Braulio Alberto',        'job_position' => 'Coordinador del programa de estudios de Enfermería Técnica', 'phone' => '928527525', 'email' => 'abrauliopardo22@gmail.com', 'address' => 'Jr. Azangaro cdra 6', 'role' => 'docente'],
            ['dni' => '42418923', 'names_raw' => 'PAREDES TORRES EMANUEL',              'job_position' => 'Coordinador de ÁREA',    'phone' => '945057350', 'email' => 'paredestorresemanuel@gmail.com', 'address' => 'Jr Athaualpa 1412', 'role' => 'docente'],
            ['dni' => '44015514', 'names_raw' => 'Rojas Jara Verónica Hermelinda',      'job_position' => 'Docente',                'phone' => '944242925', 'email' => 'veritojara762@gmail.com',          'address' => 'JR. Miguel Grau #475', 'role' => 'docente'],
            ['dni' => '47507601', 'names_raw' => 'Melgarejo venancio SHERLY SOLEDAD',   'job_position' => 'DOCENTE',                 'phone' => '912417184', 'email' => '47507601@sanmarcelo.edu.pe',      'address' => 'SANTA SERAFINA A-8', 'role' => 'docente'],
            ['dni' => '42428407', 'names_raw' => 'Ponce Ramírez Romer',                'job_position' => 'Jefatura de Produccion', 'phone' => '944383367', 'email' => 'romerponceramirez@gmail.com',    'address' => 'JR. Hipólito Unanue Cdra 3', 'role' => 'docente'],
            ['dni' => '46104578', 'names_raw' => 'VIZCARRA RIOS NEYRI BETSI',           'job_position' => 'SECRETARIO ACADÉMICO',   'phone' => '914902252', 'email' => 'neyrivizcarra@gmail.com',        'address' => 'PUEBLO SAN JUAN KM 4', 'role' => 'docente'],
            ['dni' => '72260117', 'names_raw' => 'Pardo Rivera Nathaly Katia',         'job_position' => 'Asistente Administración','phone' => '925616468', 'email' => 'nathyaretzi@gmail.com',            'address' => 'Alto Pampayacu /Uchiza', 'role' => 'administrativo'],
            ['dni' => '72269144', 'names_raw' => 'Principe Principe Elizabeth',        'job_position' => 'Docente',                'phone' => '952432074', 'email' => 'elizabethprincipeprincipe@gmail.com','address' => 'Av.Ricardo Palma CDRA 13', 'role' => 'docente'],
            ['dni' => '70745784', 'names_raw' => 'JIMENEZ SOLANO WIDMAN',              'job_position' => 'DOCENTE',                 'phone' => '987759134', 'email' => '70745784w@gmail.com',             'address' => 'Uchiza', 'role' => 'docente'],
            ['dni' => '80469081', 'names_raw' => 'Gonzales castillo Fidel',            'job_position' => 'Personal administrativo nombrado', 'phone' => '988063810', 'email' => 'figoca.75@gmail.com', 'address' => 'Avenida Carlos Arevalos lote 12 manzana 25 uchiza', 'role' => 'administrativo'],
            ['dni' => '44377927', 'names_raw' => 'Haneylyn Guisselle Moreno Miranda',  'job_position' => 'Área de calidad',        'phone' => '977126478', 'email' => 'hgmorenomiranda@gmail.com',      'address' => 'Av. Atahualpa', 'role' => 'docente'],
            ['dni' => '41308640', 'names_raw' => 'Atero Berrospe Nelida',              'job_position' => 'Seguridad y vigilancia', 'phone' => '918163856', 'email' => 'nelidaatero@gmail.com',          'address' => 'Av.Ricardo palma s/n', 'role' => 'administrativo'],
            ['dni' => '41508077', 'names_raw' => 'PANDURO ALVARADO LEYBER',            'job_position' => 'MAGISTER EN ING. DE SISTEMAS', 'phone' => '988599481', 'email' => 'paleyber@gmail.com', 'address' => 'AA. RICARDO PALMA CDRA 8', 'role' => 'docente'],
            ['dni' => '42789859', 'names_raw' => 'Olivas Ortega Orlando',              'job_position' => 'Coordinador de programa','phone' => '968243016', 'email' => 'orlandoolivas813@gmail.com',      'address' => 'Av. Carlos Arevalo cuadra 10', 'role' => 'docente'],
            ['dni' => '33819807', 'names_raw' => 'Olortegui rios orfelina',            'job_position' => 'Secretaria nombrada',    'phone' => '935789879', 'email' => 'orfe1118@gmail.com',              'address' => 'Valle hermoso S/N uchiza', 'role' => 'administrativo'],
            ['dni' => '25770273', 'names_raw' => 'MORALES PONCE JHON',                'job_position' => 'JEFE DE IMAGEN INSTITUCIONAL', 'phone' => '997 236 993', 'email' => 'jhonmoralesponce22@gmail.com', 'address' => 'AV. VALLE HERMOSO S/N', 'role' => 'docente'],
            ['dni' => '41476770', 'names_raw' => 'SULCA BERMUDEZ HORLANDO',            'job_position' => 'Docente',                'phone' => '910201183', 'email' => 'horlandosulcabermudez@gmail.com','address' => 'Barrio el Bosque Uchiza', 'role' => 'docente'],
            ['dni' => '72165750', 'names_raw' => 'Alpes Sifuentes Joany',             'job_position' => 'Jefatura de unidad de bienestar e empleabilidad', 'phone' => '917492694', 'email' => 'joanyalpess@gmail.com', 'address' => 'Pasaje naranjillo s/n yanag rosabero', 'role' => 'docente'],
            ['dni' => '70745776', 'names_raw' => 'Winker Jimenez Solano',             'job_position' => 'Psicopedagogía',         'phone' => '972554330', 'email' => '70745776w@gmail.com',             'address' => 'Jr. Atahualpa a/n', 'role' => 'docente'],
            ['dni' => '47912203', 'names_raw' => 'SALDAÑA CULQUI INES',               'job_position' => 'JEFA DE FORMACIÓN CONTINUA', 'phone' => '953976298', 'email' => 'inessc08@gmail.com', 'address' => 'Jr. Atahualpa #5. Uchiza', 'role' => 'docente'],
            ['dni' => '47258037', 'names_raw' => 'Caldas Sifuentes Peter Elias',      'job_position' => 'Auxiliar servicio II',  'phone' => '972746352', 'email' => 'caldassifuentespeter@gmail.com', 'address' => 'Caserío San Francisco', 'role' => 'administrativo'],
            ['dni' => '41311115', 'names_raw' => 'SALAZAR GASTELO EILBER MARTIN',     'job_position' => 'JEFE DE LA UNIDAD DE INVESTIGACIÓN', 'phone' => '960619361', 'email' => 'martinsg301@gmail.com', 'address' => 'Jr. SUCRE 434', 'role' => 'docente'],
            ['dni' => '45345455', 'names_raw' => 'Sanchez Dominguez Rusbith Iliana',  'job_position' => 'Docente',                'phone' => '976464938', 'email' => 'sirelove20@gmail.com',            'address' => 'El porvenir km9', 'role' => 'docente'],
            ['dni' => '44025884', 'names_raw' => 'PEREZ VASQUEZ RICHARD',             'job_position' => 'DOCENTE',                 'phone' => '977886659', 'email' => 'rp9453354@gmail.com',             'address' => 'Av. Carlos Arevalo N° 1210 - UCHIZA', 'role' => 'docente'],
            ['dni' => '40959908', 'names_raw' => 'Castro Ruiz Edwar',                 'job_position' => 'Docente',                'phone' => '956703570', 'email' => 'educr004@gmail.com',              'address' => 'Tupac - Uchiza', 'role' => 'docente'],
            ['dni' => '23018349', 'names_raw' => 'Panduro Vásquez Mary',              'job_position' => 'Coordinadora',           'phone' => '997289591', 'email' => 'vasquezpanduromary@gmail.com',    'address' => 'Jr. Los Cafetos cdra.03 santa lucia', 'role' => 'docente'],
            ['dni' => '76382716', 'names_raw' => 'MENDOZA RODRÍGUEZ NOIMÍ JULITA',    'job_position' => 'SECRETARIA DE DIRECCIÓN GENERAL', 'phone' => '935947402', 'email' => 'iesfranciscovigocaballero@gmail.com', 'address' => 'Caserío San Juan Km-4', 'role' => 'administrativo'],
            ['dni' => '41858599', 'names_raw' => 'ALEGRIA DEL CASTILLO ROYER',        'job_position' => '( SUPERIOR) ING. RECURSOS NATURALES RENOVABLES MENCION FORESTALES', 'phone' => '900568542', 'email' => 'roaldelc.2017@gmail.com', 'address' => 'JIRON JOSE GALVEZ', 'role' => 'docente'],
            ['dni' => '01014470', 'names_raw' => 'MOSQUERA RUIZ MANUEL LEONIDAS',     'job_position' => 'Administrador del I.E.S.T.P "Francisco Vigo Caballero"', 'phone' => '921573015', 'email' => 'manueleonidas57@gmail.com', 'address' => 'Av. Atahualpa N°850', 'role' => 'administrativo'],
        ];

        // 4. Combinar: primero los del nuevo Excel, luego los del viejo que no estén en el nuevo
        $newDnis = array_column($newExcelData, 'dni');
        $combined = [];

        // Agregar nuevos del Excel
        foreach ($newExcelData as $item) {
            $combined[] = [
                'dni'          => $item['dni'],
                'names'        => $this->formatFullName($item['names_raw']),
                'email'        => strtolower($item['email']),
                'password'     => Hash::make('password'), // o Hash::make($item['dni'])
                'job_position' => $item['type'] === 'DIRECTOR GENERAL' ? 'Director General' : 'Docente',
                'ubigeo'       => null,
                'phone'        => $item['phone'],
                'address'      => null,
                'role'         => $item['type'] === 'DIRECTOR GENERAL' ? 'Admin' : 'Docente',
                'email_verified_at' => now(),
            ];
        }

        // Agregar los del viejo que no estén en el nuevo
        foreach ($oldSeederData as $old) {
            if (!in_array($old['dni'], $newDnis)) {
                $combined[] = [
                    'dni'          => $old['dni'],
                    'names'        => $this->formatFullName($old['names_raw']),
                    'email'        => strtolower($old['email']),
                    'password'     => Hash::make('password'), // mantener la misma contraseña
                    'job_position' => $old['job_position'],
                    'ubigeo'       => null, // no se tenía en el viejo, pero lo dejamos null
                    'phone'        => $old['phone'],
                    'address'      => $old['address'] ?? null,
                    'role'         => $old['role'] ?? 'docente', // conservar el rol del viejo
                    'email_verified_at' => now(),
                ];
            }
        }

        // 5. Insertar todos los usuarios combinados
        foreach ($combined as $userData) {
            User::create($userData);
        }
    }

    /**
     * Convierte "APELLIDO1 APELLIDO2 NOMBRE1 NOMBRE2..." a "Nombres Apellidos"
     */
    private function formatFullName(string $raw): string
    {
        $parts = array_values(array_filter(explode(' ', trim($raw))));
        if (count($parts) < 2) {
            return $this->mbUcwords(strtolower($raw));
        }

        $lastNames = array_slice($parts, 0, 2);
        $firstNames = array_slice($parts, 2);

        $lastNames = array_map(fn($p) => $this->mbUcwords(mb_strtolower($p, 'UTF-8')), $lastNames);
        $firstNames = array_map(fn($p) => $this->mbUcwords(mb_strtolower($p, 'UTF-8')), $firstNames);

        return implode(' ', $firstNames) . ' ' . implode(' ', $lastNames);
    }

    /**
     * Capitaliza la primera letra de cada palabra (UTF-8).
     */
    private function mbUcwords(string $string): string
    {
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }
}