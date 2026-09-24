<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookFavorite;
use App\Models\LibraryAccessLog;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LibraryBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure storage folders exist
        Storage::disk('public')->makeDirectory('library/books');
        Storage::disk('public')->makeDirectory('library/covers');

        // Sample PDF path
        $dummyPdfRelPath = 'library/books/sample_book.pdf';
        if (!Storage::disk('public')->exists($dummyPdfRelPath)) {
            // Minimal valid PDF content
            $minimalPdf = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 595 842]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n190\n%%EOF";
            Storage::disk('public')->put($dummyPdfRelPath, $minimalPdf);
        }

        $programs = StudyProgram::pluck('id', 'name')->toArray();

        $progAgro      = $programs['Producción Agropecuaria'] ?? null;
        $progEnfermeria = $programs['Enfermería Técnica'] ?? null;
        $progRedes     = $programs['Administración de Redes y Comunicaciones'] ?? null;
        $progAdmin     = $programs['Asistencia Administrativa'] ?? null;
        $progForestal  = $programs['Manejo Forestal'] ?? null;

        $adminUser = User::whereIn('role', ['Admin', 'Administrador'])->first();

        // 2. Definición de Libros (coincidiendo con las capturas 1 y 2)
        $booksData = [
            [
                'title'            => 'JADAM Agricultura Ecológica: El camino a la agricultura de costo ultra bajo',
                'author'           => 'Youngsang Cho',
                'study_program_id' => $progAgro,
                'category'         => 'Libro',
                'publisher'        => 'JADAM Organic',
                'publication_year' => 2020,
                'edition'          => '2da Edición',
                'pages'            => 380,
                'isbn'             => '978-8988649299',
                'description'      => 'Guía técnica avanzada sobre agricultura ultra económica, biofertilizantes microbianos y control fitosanitario natural.',
                'rating'           => 5.0,
                'views_count'      => 142,
            ],
            [
                'title'            => 'Consejería de la Persona: Fundamentos y Procesos',
                'author'           => 'Pedro Álamo Carrasco',
                'study_program_id' => null, // General / Todas
                'category'         => 'Libro',
                'publisher'        => 'Editorial San Marcos',
                'publication_year' => 2019,
                'edition'          => '1ra Edición',
                'pages'            => 260,
                'isbn'             => '978-612-315-420-1',
                'description'      => 'Aspectos psicosociales y orientación ética en la formación integral del estudiante de educación superior.',
                'rating'           => 5.0,
                'views_count'      => 98,
            ],
            [
                'title'            => 'Costos y Presupuestos: Aplicación para la Gestión Técnica y Productiva',
                'author'           => 'Antonio Burbano',
                'study_program_id' => $progAdmin,
                'category'         => 'Libro',
                'publisher'        => 'McGraw-Hill Interamericana',
                'publication_year' => 2021,
                'edition'          => '2da Edición',
                'pages'            => 310,
                'isbn'             => '978-958-41-0320-5',
                'description'      => 'Cálculo de costos directos, punto de equilibrio y formulación de presupuestos operativos en proyectos productivos.',
                'rating'           => 5.0,
                'views_count'      => 115,
            ],
            [
                'title'            => 'El Juego del Dinero: Por qué los inversores lentos pierden y el dinero rápido gana',
                'author'           => 'Robert T. Kiyosaki',
                'study_program_id' => $progAdmin,
                'category'         => 'Libro',
                'publisher'        => 'Aguilar',
                'publication_year' => 2018,
                'edition'          => 'Edición Revisada',
                'pages'            => 352,
                'isbn'             => '978-607-316-200-8',
                'description'      => 'Estrategias de educación financiera, flujo de caja y gestión patrimonial para emprendedores y técnicos.',
                'rating'           => 5.0,
                'views_count'      => 85,
            ],
            [
                'title'            => 'Manual de Prácticas de Topografía y Geodesia',
                'author'           => 'Jacinto Santa María Peña, Teófilo Sanz Méndez',
                'study_program_id' => $progForestal,
                'category'         => 'Documento',
                'publisher'        => 'Universidad de La Rioja',
                'publication_year' => 2020,
                'edition'          => '1ra Edición',
                'pages'            => 185,
                'isbn'             => '978-84-96487-12-3',
                'description'      => 'Metodologías para levantamientos planimétricos, altimetría, manejo de teodolito, estación total y cartografía forestal.',
                'rating'           => 5.0,
                'views_count'      => 76,
            ],
            [
                'title'            => 'Soporte Nutricional en Patologías con Alteración en el Metabolismo de Hidratos de Carbono',
                'author'           => 'Yaiza García Delgado',
                'study_program_id' => $progEnfermeria,
                'category'         => 'Paper',
                'publisher'        => 'Revista Española de Nutrición Clínica',
                'publication_year' => 2022,
                'edition'          => 'Vol. 14 N° 2',
                'pages'            => 45,
                'isbn'             => 'ISSN 1989-208X',
                'description'      => 'Protocolos clínicos de nutrición enteral y parenteral en pacientes diabéticos y con resistencia a la insulina.',
                'rating'           => 5.0,
                'views_count'      => 120,
            ],
            [
                'title'            => 'Acuariofilia: Peces Ornamentales y su Manejo Técnico',
                'author'           => 'Arcadio Cuéllar Lombar',
                'study_program_id' => $progAgro,
                'category'         => 'Libro',
                'publisher'        => 'AgroVet',
                'publication_year' => 2021,
                'edition'          => '1ra Edición',
                'pages'            => 210,
                'isbn'             => '978-84-12001-44-2',
                'description'      => 'Acuicultura de especies de agua dulce, reproducción artificial, oxigenación y sanidad acuícola.',
                'rating'           => 5.0,
                'views_count'      => 64,
            ],
            [
                'title'            => 'Ataxia Espinocerebelosa: Diagnóstico, Pronóstico y Evaluación',
                'author'           => 'Luis Velázquez Pérez',
                'study_program_id' => $progEnfermeria,
                'category'         => 'Paper',
                'publisher'        => 'Editorial Ciencias Médicas',
                'publication_year' => 2019,
                'edition'          => '1ra Edición',
                'pages'            => 140,
                'isbn'             => '978-959-212-780-3',
                'description'      => 'Cuidados de enfermería en pacientes neurológicos y pautas de rehabilitación motora.',
                'rating'           => 5.0,
                'views_count'      => 58,
            ],
            [
                'title'            => 'Control de Salud del Niño y del Adolescente',
                'author'           => 'Paula Guzmán, María José Pérez',
                'study_program_id' => $progEnfermeria,
                'category'         => 'Libro',
                'publisher'        => 'Editorial Mediterráneo',
                'publication_year' => 2021,
                'edition'          => '3ra Edición',
                'pages'            => 420,
                'isbn'             => '978-956-220-410-0',
                'description'      => 'Guía técnica para enfermería técnica en evaluación de crecimiento y desarrollo (CRED), esquema de vacunación y nutrición infantil.',
                'rating'           => 5.0,
                'views_count'      => 130,
            ],
            [
                'title'            => 'Entomología Agrícola y Forestal: Plagas y Manejo Integrado',
                'author'           => 'Universidad Nacional Agraria La Molina',
                'study_program_id' => $progAgro,
                'category'         => 'Libro',
                'publisher'        => 'UNALM Publicaciones',
                'publication_year' => 2020,
                'edition'          => '2da Edición',
                'pages'            => 290,
                'isbn'             => '978-612-4147-18-9',
                'description'      => 'Identificación morfológica de insectos plaga en la cuenca del Huallaga y métodos de control biológico.',
                'rating'           => 5.0,
                'views_count'      => 92,
            ],
            [
                'title'            => 'Farmacología en Enfermería: Guía de Fármacos y Administración Segura',
                'author'           => 'Silvia Castells Molina, Margarita Hernández Pérez',
                'study_program_id' => $progEnfermeria,
                'category'         => 'Libro',
                'publisher'        => 'Elsevier España',
                'publication_year' => 2021,
                'edition'          => '4ta Edición',
                'pages'            => 540,
                'isbn'             => '978-84-9113-580-0',
                'description'      => 'Mecanismos de acción, cálculo de dosis, dilución de medicamentos y reglas de oro de la administración farmacológica.',
                'rating'           => 5.0,
                'views_count'      => 150,
            ],
            [
                'title'            => 'Fisiología Respiratoria: Lo Esencial en la Práctica Clínica',
                'author'           => 'John B. West',
                'study_program_id' => $progEnfermeria,
                'category'         => 'Libro',
                'publisher'        => 'Wolters Kluwer',
                'publication_year' => 2020,
                'edition'          => '10ma Edición',
                'pages'            => 235,
                'isbn'             => '978-84-17602-50-5',
                'description'      => 'Ventilación mecánica, oxigenoterapia y monitoreo de gases arteriales para profesionales de salud.',
                'rating'           => 5.0,
                'views_count'      => 88,
            ],
            [
                'title'            => 'Administración de Redes y Comunicaciones con Linux y Cisco',
                'author'           => 'Richard Blum, Christine Bresnahan',
                'study_program_id' => $progRedes,
                'category'         => 'Libro',
                'publisher'        => 'Sybex / Wiley',
                'publication_year' => 2022,
                'edition'          => '3ra Edición',
                'pages'            => 480,
                'isbn'             => '978-1-119-75680-4',
                'description'      => 'Enrutamiento IPv4/IPv6, conmutación VLAN, cortafuegos iptables/nftables y servicios de red seguros.',
                'rating'           => 5.0,
                'views_count'      => 105,
            ],
            [
                'title'            => 'Gestión de Recursos Humanos: El Capital Humano de las Organizaciones',
                'author'           => 'Idalberto Chiavenato',
                'study_program_id' => $progAdmin,
                'category'         => 'Libro',
                'publisher'        => 'McGraw-Hill',
                'publication_year' => 2020,
                'edition'          => '10ma Edición',
                'pages'            => 460,
                'isbn'             => '978-607-15-1410-0',
                'description'      => 'Selección por competencias, inducción, clima organizacional y evaluación del desempeño técnico.',
                'rating'           => 5.0,
                'views_count'      => 118,
            ],
            [
                'title'            => 'Interpretación de Mapas y Planos Topográficos Sencillos',
                'author'           => 'Instituto Geográfico Nacional',
                'study_program_id' => $progForestal,
                'category'         => 'Documento',
                'publisher'        => 'IGN Perú',
                'publication_year' => 2021,
                'edition'          => 'Guía Didáctica',
                'pages'            => 95,
                'isbn'             => '978-612-45210-9-1',
                'description'      => 'Escalas numéricas y gráficas, curvas de nivel, coordenadas UTM y georreferenciación satelital.',
                'rating'           => 5.0,
                'views_count'      => 72,
            ],
        ];

        $createdBooks = [];

        foreach ($booksData as $data) {
            $book = Book::firstOrCreate(
                ['title' => $data['title']],
                array_merge($data, [
                    'slug'         => Str::slug($data['title']),
                    'file_path'    => $dummyPdfRelPath,
                    'file_size'    => 394558,
                    'is_active'    => true,
                    'created_by'   => $adminUser?->id,
                ])
            );
            $createdBooks[] = $book;
        }

        // 3. Seed Favorites & Reading Logs for "German Cotrina Valles" (user ID 6 or Docente)
        $germanUser = User::where('email', 'germancotrina17@gmail.com')->first() 
            ?? User::where('role', 'Docente')->first();

        if ($germanUser && count($createdBooks) > 0) {
            // Favorite the first book (JADAM)
            BookFavorite::firstOrCreate([
                'user_id' => $germanUser->id,
                'book_id' => $createdBooks[0]->id,
            ]);

            // Add reading log for JADAM (recently read)
            LibraryAccessLog::firstOrCreate([
                'user_id'     => $germanUser->id,
                'book_id'     => $createdBooks[0]->id,
                'access_type' => 'view',
            ], [
                'ip_address'  => '127.0.0.1',
                'user_agent'  => 'Mozilla/5.0 (X11; Linux x86_64)',
                'created_at'  => now()->subMinutes(15),
            ]);

            // Add a few more recent reading logs for realism
            for ($i = 1; $i < min(4, count($createdBooks)); $i++) {
                LibraryAccessLog::create([
                    'user_id'     => $germanUser->id,
                    'book_id'     => $createdBooks[$i]->id,
                    'access_type' => 'view',
                    'ip_address'  => '127.0.0.1',
                    'user_agent'  => 'Mozilla/5.0 (X11; Linux x86_64)',
                    'created_at'  => now()->subHours($i * 3),
                ]);
            }
        }

        // 4. Seed logs for other students and teachers to populate the Lectores dashboard
        $otherUsers = User::where('id', '!=', $germanUser?->id)->take(8)->get();
        foreach ($otherUsers as $u) {
            $randomBooks = collect($createdBooks)->random(min(3, count($createdBooks)));
            foreach ($randomBooks as $rb) {
                LibraryAccessLog::create([
                    'user_id'     => $u->id,
                    'book_id'     => $rb->id,
                    'access_type' => 'view',
                    'ip_address'  => '127.0.0.1',
                    'user_agent'  => 'Mozilla/5.0 (Browser test)',
                    'created_at'  => now()->subDays(rand(1, 10))->subHours(rand(1, 12)),
                ]);
            }
        }
    }
}
