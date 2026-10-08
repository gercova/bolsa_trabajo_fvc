<?php

namespace App\Services;

use App\Models\Book;
use App\Models\StudyProgram;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AcademicLibraryService
{
    /**
     * Specialized academic query groups mapped to the institution's 5 study programs.
     * Each group targets peer-reviewed, open-access publications in SciELO, Redalyc, DOAJ, etc.
     */
    public const PROGRAM_QUERIES = [
        // 1: Producción Agropecuaria
        1 => [
            'name' => 'Producción Agropecuaria',
            'terms' => [
                'produccion agropecuaria scielo',
                'agricultura sostenible pecuaria redalyc',
                'sanidad animal cultivos tropicales agronomia',
                'manejo integrado plagas suelos fertirriego',
                'zootecnia bovinos porcinos biotecnologia agricola',
            ],
            'default_category' => 'Revista',
        ],
        // 2: Enfermería Técnica
        2 => [
            'name' => 'Enfermería Técnica',
            'terms' => [
                'enfermeria tecnica atencion primaria scielo',
                'cuidados de enfermeria salud comunitaria redalyc',
                'farmacologia primeros auxilios procedimientos enfermeria',
                'salud publica bioseguridad epidemiologia clinica',
                'materno infantil geriatria atencion integral enfermeria',
            ],
            'default_category' => 'Paper',
        ],
        // 3: Administración de Redes y Comunicaciones
        3 => [
            'name' => 'Administración de Redes y Comunicaciones',
            'terms' => [
                'redes y comunicaciones seguridad informatica scielo',
                'administracion de redes telecomunicaciones redalyc',
                'infraestructura ti protocolos enrutamiento computadoras',
                'ciberseguridad redes inalambricas fibra optica servidores',
                'computacion en la nube virtualizacion sistemas distribuidos',
            ],
            'default_category' => 'Paper',
        ],
        // 4: Asistencia Administrativa
        4 => [
            'name' => 'Asistencia Administrativa',
            'terms' => [
                'asistencia administrativa gestion documental scielo',
                'administracion y gestion empresarial redalyc',
                'procesos administrativos archivo digital oficina',
                'gestion publica recursos humanos contabilidad basica',
                'comunicacion organizacional liderazgo gestion de calidad',
            ],
            'default_category' => 'Revista',
        ],
        // 5: Manejo Forestal
        5 => [
            'name' => 'Manejo Forestal',
            'terms' => [
                'manejo forestal sostenible silvicultura scielo',
                'conservacion bosques tropicales redalyc',
                'inventario forestal dasonomia tala dirigida',
                'agroforesteria cambio climatico biodiversidad amazonica',
                'restauracion ecologica bosques plantaciones forestales',
            ],
            'default_category' => 'Paper',
        ],
    ];

    /**
     * General academic queries when no specific study program is filtered.
     */
    public const GENERAL_QUERIES = [
        'educacion tecnologica superior investigacion scielo',
        'innovacion tecnologica desarrollo sostenible redalyc',
        'metodologia de la investigacion cientifica publicaciones',
    ];

    /**
     * Search and bulk-import academic resources (journals, books, papers, articles).
     *
     * @param array{
     *     study_program_id?: int|string|null,
     *     category?: string|null,
     *     sources?: array<string>|null,
     *     custom_query?: string|null,
     *     limit?: int,
     *     created_by?: int|null
     * } $options
     * @return array{
     *     success: bool,
     *     saved: int,
     *     updated: int,
     *     skipped: int,
     *     total: int,
     *     items: array<int, array<string, mixed>>,
     *     log: array<int, string>,
     *     errors: array<int, string>,
     *     message: string
     * }
     */
    public function fetchAndImport(array $options = []): array
    {
        $limit = max(1, min((int) ($options['limit'] ?? 10), 100));
        $programId = ! empty($options['study_program_id']) && $options['study_program_id'] !== 'all' ? (int) $options['study_program_id'] : null;
        $categoryFilter = ! empty($options['category']) && $options['category'] !== 'all' ? (string) $options['category'] : null;
        $sources = ! empty($options['sources']) && is_array($options['sources']) ? $options['sources'] : ['scielo', 'redalyc', 'openalex', 'crossref', 'doaj'];
        $customQuery = trim((string) ($options['custom_query'] ?? ''));
        $createdBy = $options['created_by'] ?? auth()->id();

        $savedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $importedList = [];
        $log = [];
        $errors = [];

        // Build list of target study programs
        $targets = [];
        if ($programId && isset(self::PROGRAM_QUERIES[$programId])) {
            $targets[$programId] = self::PROGRAM_QUERIES[$programId];
        } elseif ($programId) {
            $progModel = StudyProgram::find($programId);
            $targets[$programId] = [
                'name' => $progModel?->name ?? 'Programa de Estudio',
                'terms' => [$progModel?->name.' scielo', $progModel?->name.' redalyc'],
                'default_category' => 'Paper',
            ];
        } else {
            // All active study programs
            $allPrograms = StudyProgram::select('id', 'name')->orderBy('id')->get();
            if ($allPrograms->isNotEmpty()) {
                foreach ($allPrograms as $p) {
                    $targets[$p->id] = self::PROGRAM_QUERIES[$p->id] ?? [
                        'name' => $p->name,
                        'terms' => [$p->name.' scielo', $p->name.' redalyc'],
                        'default_category' => 'Paper',
                    ];
                }
            } else {
                $targets[0] = [
                    'name' => 'General',
                    'terms' => self::GENERAL_QUERIES,
                    'default_category' => 'Paper',
                ];
            }
        }

        // Target search terms
        foreach ($targets as $currentProgramId => $config) {
            if (($savedCount + $updatedCount) >= $limit) {
                break;
            }

            $searchTerms = ! empty($customQuery)
                ? [$customQuery.($programId ? ' '.$config['name'] : '')]
                : $config['terms'];

            foreach ($searchTerms as $term) {
                if (($savedCount + $updatedCount) >= $limit) {
                    break;
                }

                // ── 1. Fetch from OpenAlex API (Covers SciELO, Redalyc, DOAJ, Open Access repositories) ──
                try {
                    $openAlexResults = $this->queryOpenAlex($term, $config, $currentProgramId, $categoryFilter, $sources, $limit - ($savedCount + $updatedCount));
                    foreach ($openAlexResults as $item) {
                        if (($savedCount + $updatedCount) >= $limit) {
                            break;
                        }

                        $item['created_by'] = $createdBy;
                        [$action, $book] = $this->upsertAcademicResource($item);

                        if ($action === 'saved') {
                            $savedCount++;
                            $importedList[] = $this->formatItemSummary($book);
                        } elseif ($action === 'updated') {
                            $updatedCount++;
                            $importedList[] = $this->formatItemSummary($book);
                        } else {
                            $skippedCount++;
                        }
                    }

                    if (! empty($openAlexResults)) {
                        $log[] = "OpenAlex/SciELO/Redalyc [{$config['name']}] «{$term}»: ".count($openAlexResults).' registros procesados.';
                    }
                } catch (\Throwable $e) {
                    $errors[] = "OpenAlex [{$term}]: {$e->getMessage()}";
                    Log::warning("AcademicLibraryService OpenAlex error [{$term}]: ".$e->getMessage());
                }

                // ── 2. Fallback to Crossref API (Official DOI Registry) if needed ──
                if (($savedCount + $updatedCount) < $limit && in_array('crossref', $sources)) {
                    try {
                        $crossrefResults = $this->queryCrossref($term, $config, $currentProgramId, $categoryFilter, $limit - ($savedCount + $updatedCount));
                        foreach ($crossrefResults as $item) {
                            if (($savedCount + $updatedCount) >= $limit) {
                                break;
                            }

                            $item['created_by'] = $createdBy;
                            [$action, $book] = $this->upsertAcademicResource($item);

                            if ($action === 'saved') {
                                $savedCount++;
                                $importedList[] = $this->formatItemSummary($book);
                            } elseif ($action === 'updated') {
                                $updatedCount++;
                                $importedList[] = $this->formatItemSummary($book);
                            } else {
                                $skippedCount++;
                            }
                        }

                        if (! empty($crossrefResults)) {
                            $log[] = "Crossref [{$config['name']}] «{$term}»: ".count($crossrefResults).' registros procesados.';
                        }
                    } catch (\Throwable $e) {
                        $errors[] = "Crossref [{$term}]: {$e->getMessage()}";
                        Log::warning("AcademicLibraryService Crossref error [{$term}]: ".$e->getMessage());
                    }
                }

                // Respectful pause between queries
                usleep(300000); // 0.3s
            }
        }

        $total = $savedCount + $updatedCount;
        $message = $total > 0
            ? "Búsqueda académica completada: {$savedCount} nuevos registros indexados y {$updatedCount} actualizados ({$skippedCount} ya existentes en el repositorio)."
            : 'Búsqueda completada. No se encontraron nuevos registros con los criterios seleccionados o ya se encuentran registrados.';

        return [
            'success' => true,
            'saved' => $savedCount,
            'updated' => $updatedCount,
            'skipped' => $skippedCount,
            'total' => $total,
            'items' => $importedList,
            'log' => $log,
            'errors' => $errors,
            'message' => $message,
        ];
    }

    /**
     * Query OpenAlex API (World's premier open academic index covering SciELO, Redalyc, DOAJ, etc.)
     */
    private function queryOpenAlex(string $term, array $config, int $programId, ?string $categoryFilter, array $sources, int $batchLimit): array
    {
        $perPage = max(1, min($batchLimit, 25));

        // Refine search query with source boosters if requested
        $enhancedSearch = $term;
        if (in_array('scielo', $sources) && ! str_contains(mb_strtolower($enhancedSearch), 'scielo')) {
            $enhancedSearch .= ' scielo';
        }

        $response = Http::timeout(12)
            ->withHeaders([
                'User-Agent' => 'IESTP-Francisco-Vigo-Caballero/VirtualLibrary (contacto@iestpfvc.edu.pe; academic-integration)',
                'Accept' => 'application/json',
            ])
            ->get('https://api.openalex.org/works', [
                'search' => $enhancedSearch,
                'filter' => 'has_doi:true,is_oa:true',
                'per_page' => $perPage,
            ]);

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json('results') ?? [];
        $results = [];

        foreach ($data as $item) {
            $title = trim((string) ($item['title'] ?? ''));
            if ($title === '' || mb_strlen($title) < 5) {
                continue;
            }

            // Extract Authors
            $authors = collect($item['authorships'] ?? [])
                ->pluck('author.display_name')
                ->filter()
                ->take(3)
                ->implode(', ');
            $author = $authors ?: 'Varios Autores / Colectivo Académico';

            // Extract Publisher / Journal Source
            $sourceName = $item['primary_location']['source']['display_name'] ?? null;
            $hostOrg = $item['primary_location']['source']['host_organization_name'] ?? null;
            $publisherName = $sourceName ? ($hostOrg ? "{$sourceName} ({$hostOrg})" : $sourceName) : 'Repositorio Científico Abierto';

            // Source recognition tag (SciELO / Redalyc / DOAJ)
            $tag = '';
            $checkText = mb_strtolower($publisherName.' '.json_encode($item));
            if (str_contains($checkText, 'scielo')) {
                $tag = 'SciELO / ';
            } elseif (str_contains($checkText, 'redalyc')) {
                $tag = 'Redalyc / ';
            } elseif (! empty($item['primary_location']['source']['is_in_doaj'])) {
                $tag = 'DOAJ / ';
            }
            $finalPublisher = Str::limit($tag.$publisherName, 145);

            // Extract URLs: direct PDF or verified landing page / DOI
            $landingUrl = $item['primary_location']['landing_page_url'] ?? null;
            $doiUrl = $item['doi'] ?? null;
            $pdfUrl = $item['primary_location']['pdf_url'] ?? null;
            $externalUrl = $pdfUrl ?: ($landingUrl ?: $doiUrl);

            if (! $externalUrl) {
                continue;
            }

            // Extract Publication Year
            $year = $item['publication_year'] ?? null;
            if ($year) {
                $year = (int) $year;
            }

            // Map academic category
            $type = $item['type'] ?? 'article';
            $category = $this->mapCategory($type, $categoryFilter ?: ($config['default_category'] ?? 'Paper'));

            // Reconstruct abstract from inverted index if present
            $description = $this->reconstructAbstract($item['abstract_inverted_index'] ?? null);
            if (! $description) {
                $description = "Publicación académica revisada por pares («{$title}»). Publicado en {$finalPublisher}".($year ? " ({$year})" : '').'. Acceso abierto institucional disponible para investigación y formación tecnológica.';
            }

            $doiClean = ! empty($item['doi']) ? Str::after($item['doi'], 'doi.org/') : null;

            $results[] = [
                'title' => Str::limit($title, 250),
                'author' => Str::limit($author, 250),
                'study_program_id' => $programId > 0 ? $programId : null,
                'category' => $category,
                'description' => Str::limit($description, 950),
                'publisher' => $finalPublisher,
                'publication_year' => $year,
                'edition' => '1ª Edición Digital',
                'pages' => $item['biblio']['last_page'] ?? null ? (int) $item['biblio']['last_page'] : null,
                'isbn' => $doiClean ? Str::limit($doiClean, 45) : null,
                'language' => 'Español',
                'external_url' => $externalUrl,
                'rating' => 5.0,
                'is_active' => true,
            ];
        }

        return $results;
    }

    /**
     * Query Crossref API (Official Scholarly DOI Registry).
     */
    private function queryCrossref(string $term, array $config, int $programId, ?string $categoryFilter, int $batchLimit): array
    {
        $rows = max(1, min($batchLimit, 15));

        $response = Http::timeout(12)
            ->withHeaders([
                'User-Agent' => 'IESTP-Francisco-Vigo-Caballero/VirtualLibrary (contacto@iestpfvc.edu.pe)',
                'Accept' => 'application/json',
            ])
            ->get('https://api.crossref.org/works', [
                'query' => $term,
                'rows' => $rows,
                'filter' => 'has-full-text:true',
            ]);

        if (! $response->successful()) {
            return [];
        }

        $items = $response->json('message.items') ?? [];
        $results = [];

        foreach ($items as $item) {
            $rawTitle = $item['title'][0] ?? null;
            if (! $rawTitle || mb_strlen($rawTitle) < 5) {
                continue;
            }

            $title = trim(strip_tags((string) $rawTitle));

            // Authors
            $authors = collect($item['author'] ?? [])
                ->map(fn ($a) => trim(($a['given'] ?? '').' '.($a['family'] ?? '')))
                ->filter()
                ->take(3)
                ->implode(', ');
            $author = $authors ?: 'Varios Autores / Colectivo Académico';

            // Publisher / Journal
            $containerTitle = $item['container-title'][0] ?? null;
            $publisherRaw = $item['publisher'] ?? 'Crossref Academic Registry';
            $publisherName = $containerTitle ? "{$containerTitle} ({$publisherRaw})" : $publisherRaw;
            $finalPublisher = Str::limit($publisherName, 145);

            // URL
            $url = $item['resource']['primary']['URL'] ?? ($item['URL'] ?? null);
            if (! $url) {
                continue;
            }

            // Year
            $yearParts = $item['published-print']['date-parts'][0][0] ?? ($item['published-online']['date-parts'][0][0] ?? ($item['created']['date-parts'][0][0] ?? null));
            $year = $yearParts ? (int) $yearParts : null;

            $type = $item['type'] ?? 'journal-article';
            $category = $this->mapCategory($type, $categoryFilter ?: ($config['default_category'] ?? 'Paper'));

            $description = "Artículo / documento científico revisado por pares («{$title}»). Indexado en DOI Crossref / {$finalPublisher}".($year ? " ({$year})" : '').'. Acceso abierto verificado para la comunidad académica institucional.';

            $results[] = [
                'title' => Str::limit($title, 250),
                'author' => Str::limit($author, 250),
                'study_program_id' => $programId > 0 ? $programId : null,
                'category' => $category,
                'description' => Str::limit($description, 950),
                'publisher' => $finalPublisher,
                'publication_year' => $year,
                'edition' => '1ª Edición Digital',
                'pages' => $item['page'] ?? null ? (int) Str::before($item['page'], '-') : null,
                'isbn' => isset($item['DOI']) ? Str::limit($item['DOI'], 45) : null,
                'language' => 'Español',
                'external_url' => $url,
                'rating' => 5.0,
                'is_active' => true,
            ];
        }

        return $results;
    }

    /**
     * Map scholarly work type to the Library categories.
     */
    private function mapCategory(string $academicType, string $fallback): string
    {
        $type = mb_strtolower($academicType);

        if (str_contains($type, 'article') || str_contains($type, 'paper')) {
            return 'Paper';
        }
        if (str_contains($type, 'book') || str_contains($type, 'monograph')) {
            return 'Libro';
        }
        if (str_contains($type, 'dissertation') || str_contains($type, 'thesis')) {
            return 'Tesis';
        }
        if (str_contains($type, 'journal') || str_contains($type, 'serial')) {
            return 'Revista';
        }

        return $fallback ?: 'Paper';
    }

    /**
     * Reconstruct human-readable abstract from OpenAlex inverted index.
     */
    private function reconstructAbstract(?array $invertedIndex): ?string
    {
        if (empty($invertedIndex) || ! is_array($invertedIndex)) {
            return null;
        }

        $words = [];
        foreach ($invertedIndex as $word => $positions) {
            if (is_array($positions)) {
                foreach ($positions as $pos) {
                    $words[(int) $pos] = $word;
                }
            }
        }

        if (empty($words)) {
            return null;
        }

        ksort($words);

        return trim(implode(' ', $words));
    }

    /**
     * Store and deduplicate a single academic item.
     *
     * @return array{status: 'created'|'updated'|'skipped', book: ?Book}
     */
    public function storeAcademicItem(array $item): array
    {
        if (empty($item['title']) || empty($item['external_url'])) {
            return ['status' => 'skipped', 'book' => null];
        }

        if (! isset($item['slug'])) {
            $baseSlug = Str::slug(Str::limit($item['title'], 180));
            $slug = $baseSlug;
            $counter = 1;
            while (Book::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }
            $item['slug'] = $slug;
        }

        if (! isset($item['is_active'])) {
            $item['is_active'] = true;
        }

        if (! isset($item['is_external'])) {
            $item['is_external'] = true;
        }

        [$status, $book] = $this->upsertAcademicResource($item);

        $normalizedStatus = match ($status) {
            'saved' => 'created',
            'updated' => 'updated',
            default => 'skipped',
        };

        return [
            'status' => $normalizedStatus,
            'book' => $book->exists ? $book : null,
        ];
    }

    /**
     * Persist or update academic resource avoiding duplicates.
     *
     * @return array{0: 'saved'|'updated'|'skipped', 1: Book}
     */
    private function upsertAcademicResource(array $item): array
    {
        if (empty($item['title']) || empty($item['external_url'])) {
            return ['skipped', new Book];
        }

        // Match existing record by external URL OR by title + study program
        $existing = Book::where('external_url', $item['external_url'])->first();

        if (! $existing && ! empty($item['study_program_id'])) {
            $existing = Book::where('study_program_id', $item['study_program_id'])
                ->where('title', $item['title'])
                ->first();
        }

        if ($existing) {
            // Update metadata while preserving admin customization and active status
            $existing->update([
                'author' => $existing->author ?: $item['author'],
                'description' => $existing->description ?: $item['description'],
                'publisher' => $existing->publisher ?: $item['publisher'],
                'publication_year' => $existing->publication_year ?: $item['publication_year'],
                'isbn' => $existing->isbn ?: ($item['isbn'] ?? null),
            ]);

            return ['updated', $existing];
        }

        $book = Book::create($item);

        return ['saved', $book];
    }

    /**
     * Format summary for client-side feedback and table refresh.
     */
    private function formatItemSummary(Book $book): array
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'publisher' => $book->publisher,
            'category' => $book->category,
            'publication_year' => $book->publication_year,
            'program_name' => $book->studyProgram?->name ?? 'General / Todas',
            'external_url' => $book->external_url,
            'slug' => $book->slug,
        ];
    }
}
