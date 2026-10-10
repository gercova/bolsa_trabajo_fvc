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
     * Each group targets peer-reviewed, open-access publications in SciELO, Google Scholar, Redalyc, DOAJ, etc.
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
        $sources = ! empty($options['sources']) && is_array($options['sources'])
            ? $options['sources']
            : ['scielo', 'google_scholar', 'redalyc', 'openalex', 'crossref', 'doaj'];
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
                'terms' => [$progModel?->name.' scielo', $progModel?->name.' redalyc', $progModel?->name.' google scholar'],
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

                // ── 1. Fetch from Google Scholar (Direct PDF / Galley & verified academic indexing) ──
                if (in_array('google_scholar', $sources)) {
                    try {
                        $scholarResults = $this->queryGoogleScholar(
                            $term,
                            $config,
                            $currentProgramId,
                            $categoryFilter,
                            $limit - ($savedCount + $updatedCount)
                        );

                        foreach ($scholarResults as $item) {
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

                        if (! empty($scholarResults)) {
                            $log[] = "Google Scholar [{$config['name']}] «{$term}»: ".count($scholarResults).' registros procesados.';
                        }
                    } catch (\Throwable $e) {
                        $errors[] = "Google Scholar [{$term}]: {$e->getMessage()}";
                        Log::warning("AcademicLibraryService Google Scholar error [{$term}]: ".$e->getMessage());
                    }
                }

                if (($savedCount + $updatedCount) >= $limit) {
                    break;
                }

                // ── 2. Fetch from OpenAlex API (Covers SciELO, Redalyc, DOAJ, Open Access repositories) ──
                if (
                    in_array('scielo', $sources) ||
                    in_array('redalyc', $sources) ||
                    in_array('openalex', $sources) ||
                    in_array('doaj', $sources)
                ) {
                    try {
                        $openAlexResults = $this->queryOpenAlex(
                            $term,
                            $config,
                            $currentProgramId,
                            $categoryFilter,
                            $sources,
                            $limit - ($savedCount + $updatedCount)
                        );

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
                }

                // ── 3. Fallback to Crossref API (Official Scholarly DOI Registry) if needed ──
                if (($savedCount + $updatedCount) < $limit && in_array('crossref', $sources)) {
                    try {
                        $crossrefResults = $this->queryCrossref(
                            $term,
                            $config,
                            $currentProgramId,
                            $categoryFilter,
                            $limit - ($savedCount + $updatedCount)
                        );

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
                usleep(250000); // 0.25s
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
     * Search and parse Google Scholar results for direct document / PDF links.
     *
     * @return array<int, array<string, mixed>>
     */
    public function queryGoogleScholar(string $term, array $config, int $programId, ?string $categoryFilter, int $batchLimit): array
    {
        $maxItems = max(1, min($batchLimit, 15));
        $scholarQuery = $term.' (scielo OR redalyc OR "open access" OR pdf)';

        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                ])
                ->get('https://scholar.google.com/scholar', [
                    'q' => $scholarQuery,
                    'hl' => 'es',
                ]);

            if ($response->successful()) {
                $html = $response->body();
                $parsed = $this->parseGoogleScholarHtml($html, $config, $programId, $categoryFilter, $maxItems);
                if (! empty($parsed)) {
                    return $parsed;
                }
            }
        } catch (\Throwable $e) {
            Log::info("Google Scholar direct request note [{$term}]: ".$e->getMessage());
        }

        // If Google Scholar is rate-limited (HTTP 429) or returns no results, gracefully route query with Scholar boost
        return $this->queryOpenAlex($term.' google scholar', $config, $programId, $categoryFilter, ['scielo', 'redalyc', 'openalex'], $maxItems);
    }

    /**
     * Parse HTML output from Google Scholar search results.
     *
     * @return array<int, array<string, mixed>>
     */
    public function parseGoogleScholarHtml(string $html, array $config, int $programId, ?string $categoryFilter, int $maxItems): array
    {
        $results = [];

        // Match Google Scholar result blocks
        preg_match_all('/<div[^>]*class=["\'][^"\']*gs_r\s+gs_or[^"\']*["\'][^>]*>(.*?)<\/div>\s*(?=<div[^>]*class=["\'][^"\']*gs_r\s+gs_or[^"\']*["\']|$)/is', $html, $matches);

        if (empty($matches[1])) {
            // Alternate regex for standard result items
            preg_match_all('/<div[^>]*class=["\']gs_ri["\'][^>]*>(.*?)<\/div>\s*<\/div>/is', $html, $matches);
        }

        $items = $matches[1] ?? [];

        foreach ($items as $block) {
            if (count($results) >= $maxItems) {
                break;
            }

            // 1. Direct PDF / Galley link on the right (.gs_or_ggsm, .gs_ggs, .gs_fl or [PDF] badge)
            $directDocLink = null;
            if (preg_match('/<div[^>]*class=["\'][^"\']*(?:gs_or_ggsm|gs_ggs|gs_fl)[^"\']*["\'][^>]*>.*?<a[^>]*href=["\']([^"\']+)["\']/is', $block, $pdfMatch)) {
                $directDocLink = html_entity_decode(trim($pdfMatch[1]));
            } elseif (preg_match('/<a[^>]*href=["\']([^"\']+)["\'][^>]*>.*?(?:\[PDF\]|\.pdf\b|\[HTML\])/is', $block, $pdfMatch)) {
                $directDocLink = html_entity_decode(trim($pdfMatch[1]));
            }

            // 2. Article Title and Landing URL (h3.gs_rt a)
            if (! preg_match('/<h3[^>]*class=["\'][^"\']*gs_rt[^"\']*["\'][^>]*>\s*<a[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $block, $titleMatch)) {
                continue;
            }

            $landingUrl = html_entity_decode(trim($titleMatch[1]));
            $title = trim(strip_tags(html_entity_decode($titleMatch[2])));

            if (mb_strlen($title) < 5) {
                continue;
            }

            // 3. Authors, Journal & Year (.gs_a)
            $author = 'Varios Autores / Colectivo Académico';
            $year = null;
            $publisherName = 'Google Scholar / Repositorio Académico';

            if (preg_match('/<div[^>]*class=["\'][^"\']*gs_a[^"\']*["\'][^>]*>(.*?)<\/div>/is', $block, $authorMatch)) {
                $metaText = trim(strip_tags(html_entity_decode($authorMatch[1])));
                if (preg_match('/\b(19\d\d|20\d\d)\b/', $metaText, $yMatch)) {
                    $year = (int) $yMatch[1];
                }

                $parts = explode(' - ', $metaText);
                if (! empty($parts[0])) {
                    $author = Str::limit(trim($parts[0]), 250);
                }
                if (! empty($parts[1])) {
                    $publisherName = Str::limit('Google Scholar / '.trim($parts[1]), 145);
                }
            }

            // 4. Abstract / Snippet (.gs_rs)
            $description = null;
            if (preg_match('/<div[^>]*class=["\'][^"\']*gs_rs[^"\']*["\'][^>]*>(.*?)<\/div>/is', $block, $snippetMatch)) {
                $description = trim(strip_tags(html_entity_decode($snippetMatch[1])));
            }

            if (! $description) {
                $description = "Publicación académica verificada por Google Scholar («{$title}»). Documento indexado para investigación y formación técnica tecnológica.";
            }

            // 5. Direct Document URL resolution (PDF / Galley)
            $candidateUrl = $directDocLink ?: $landingUrl;
            $finalUrl = $this->resolveDirectDocumentLink($candidateUrl);

            $results[] = [
                'title' => Str::limit($title, 250),
                'author' => $author,
                'study_program_id' => $programId > 0 ? $programId : null,
                'category' => $categoryFilter ?: ($config['default_category'] ?? 'Paper'),
                'description' => Str::limit($description, 950),
                'publisher' => $publisherName,
                'publication_year' => $year,
                'edition' => '1ª Edición Digital',
                'pages' => null,
                'isbn' => null,
                'language' => 'Español',
                'external_url' => $finalUrl,
                'rating' => 5.0,
                'is_active' => true,
            ];
        }

        return $results;
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

            // Extract URLs: prioritize direct PDF / Galley from best OA location or any indexed location
            $pdfUrl = $item['best_oa_location']['pdf_url']
                ?? $item['primary_location']['pdf_url']
                ?? null;

            if (! $pdfUrl && ! empty($item['locations']) && is_array($item['locations'])) {
                foreach ($item['locations'] as $loc) {
                    if (! empty($loc['pdf_url'])) {
                        $pdfUrl = $loc['pdf_url'];
                        break;
                    }
                }
            }

            $landingUrl = $item['best_oa_location']['landing_page_url']
                ?? $item['primary_location']['landing_page_url']
                ?? ($item['doi'] ?? null);

            if (! $landingUrl && ! empty($item['locations']) && is_array($item['locations'])) {
                foreach ($item['locations'] as $loc) {
                    if (! empty($loc['landing_page_url'])) {
                        $landingUrl = $loc['landing_page_url'];
                        break;
                    }
                }
            }

            $candidateUrl = $pdfUrl ?: ($landingUrl ?: ($item['doi'] ?? null));

            if (! $candidateUrl) {
                continue;
            }

            // Resolve to direct document / PDF link via scraping and normalization
            $externalUrl = $this->resolveDirectDocumentLink($candidateUrl);

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

            // Extract direct PDF / Galley link from Crossref full-text links if available
            $crossrefPdfUrl = null;
            if (! empty($item['link']) && is_array($item['link'])) {
                foreach ($item['link'] as $lnk) {
                    $ct = strtolower((string) ($lnk['content-type'] ?? ''));
                    $u = (string) ($lnk['URL'] ?? '');
                    if ($u !== '' && (str_contains($ct, 'pdf') || preg_match('/\.pdf($|\?)|\/article\/(?:view|download)\/\d+\/\d+/i', $u))) {
                        $crossrefPdfUrl = $u;
                        break;
                    }
                }
                if (! $crossrefPdfUrl) {
                    foreach ($item['link'] as $lnk) {
                        $u = (string) ($lnk['URL'] ?? '');
                        if ($u !== '' && preg_match('/\.pdf|\/article\/(?:view|download)\/\d+\/\d+|format=pdf|script=sci_pdf/i', $u)) {
                            $crossrefPdfUrl = $u;
                            break;
                        }
                    }
                }
            }

            $landingUrl = $item['resource']['primary']['URL'] ?? ($item['URL'] ?? null);
            $candidateUrl = $crossrefPdfUrl ?: $landingUrl;

            if (! $candidateUrl) {
                continue;
            }

            // Resolve to direct document / PDF link via scraping and normalization
            $externalUrl = $this->resolveDirectDocumentLink($candidateUrl);

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
                'external_url' => $externalUrl,
                'rating' => 5.0,
                'is_active' => true,
            ];
        }

        return $results;
    }

    /**
     * Normalize an OJS document URL to ensure it points to the interactive viewer route.
     * Converts /article/download/ID/GALLEY to /article/view/ID/GALLEY so that it can be
     * directly rendered within the library reader iframe (/biblioteca/leer/{book:slug}).
     */
    public function normalizeOjsUrl(string $url): string
    {
        $url = trim($url);

        // Normalize /article/download/{articleId}/{galleyId} -> /article/view/{articleId}/{galleyId}
        if (preg_match('~(/article/)download/([^/?#]+/[^/?#]+)(?:/[^/?#]+)?~i', $url)) {
            $url = preg_replace('~(/article/)download/([^/?#]+/[^/?#]+)(?:/[^/?#]+)?~i', '$1view/$2', $url);
        }

        return $url;
    }

    /**
     * Resolve a publication URL to its most direct document / PDF view link.
     * Examples:
     * - OJS: converts .../article/view/350 or .../article/download/350/394 to .../article/view/350/394
     * - SciELO: converts sci_arttext to sci_pdf or extracts direct PDF asset
     * - Dialnet: converts /servlet/articulo?codigo=X to /descarga/articulo/X.pdf
     * - Repositories: extracts citation_pdf_url or direct PDF galley
     */
    public function resolveDirectDocumentLink(string $url, ?string $fallback = null): string
    {
        $url = trim($url);
        if ($url === '') {
            return $fallback ?? '';
        }

        // Normalize OJS download URLs to direct viewer URLs (/article/view/ID/GALLEY)
        $url = $this->normalizeOjsUrl($url);

        // Fast check 1: already ends in .pdf or contains direct PDF asset
        if (preg_match('/\.pdf($|\?)/i', $url)) {
            return $url;
        }

        // Fast check 2: OJS Galley already present (e.g. /article/view/350/394)
        if (preg_match('/\/article\/view\/\d+\/\d+/i', $url)) {
            return $url;
        }

        // Fast check 3: Dialnet article landing page -> direct PDF download link
        if (preg_match('/dialnet\.unirioja\.es\/servlet\/articulo\?codigo=(\d+)/i', $url, $dMatch)) {
            return "https://dialnet.unirioja.es/descarga/articulo/{$dMatch[1]}.pdf";
        }

        // Fast check 4: SciELO arttext -> try direct PDF transformation or scraping
        if (str_contains($url, 'scielo.php') && str_contains($url, 'script=sci_arttext')) {
            $pdfCandidate = str_replace('script=sci_arttext', 'script=sci_pdf', $url);
            $scraped = $this->scrapeDirectDocumentLink($url);
            if ($scraped) {
                return $this->normalizeOjsUrl($scraped);
            }

            return $pdfCandidate;
        }

        // Fast check 5: SciELO modern URL without format=pdf
        if (preg_match('#scielo\.br/j/[^/]+/a/[^/?]+#i', $url) && ! str_contains($url, 'format=pdf')) {
            $scraped = $this->scrapeDirectDocumentLink($url);
            if ($scraped) {
                return $this->normalizeOjsUrl($scraped);
            }

            $sep = str_contains($url, '?') ? '&' : '?';

            return $url.$sep.'format=pdf';
        }

        // Active scraping: Fetch HTML and parse meta tags, OJS galley, and PDF anchors
        $scrapedLink = $this->scrapeDirectDocumentLink($url);
        if ($scrapedLink) {
            return $this->normalizeOjsUrl($scrapedLink);
        }

        // OJS Galley Fallback: If scraping failed or was blocked, attempt OJS OAI / Crossref resolution
        $ojsResolved = $this->resolveOjsGalleyUrl($url);
        if ($ojsResolved) {
            return $ojsResolved;
        }

        return $url;
    }

    /**
     * Scrape HTML page to retrieve direct document / PDF link.
     */
    public function scrapeDirectDocumentLink(string $url): ?string
    {
        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,application/pdf;q=0.8,*/*;q=0.7',
                    'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                    'Sec-Ch-Ua' => '"Chromium";v="128", "Not;A=Brand";v="24", "Google Chrome";v="128"',
                    'Sec-Ch-Ua-Mobile' => '?0',
                    'Sec-Ch-Ua-Platform' => '"Windows"',
                    'Sec-Fetch-Dest' => 'document',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-Site' => 'none',
                    'Sec-Fetch-User' => '?1',
                    'Upgrade-Insecure-Requests' => '1',
                ])
                ->withOptions([
                    'allow_redirects' => [
                        'max' => 10,
                        'strict' => false,
                        'referer' => true,
                        'track_redirects' => true,
                    ],
                ])
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            // Effective URI after following redirects
            $effectiveUri = (string) ($response->effectiveUri() ?: $url);

            // If target URL directly returns application/pdf
            $contentType = (string) $response->header('Content-Type');
            if (str_contains(strtolower($contentType), 'application/pdf')) {
                return $effectiveUri;
            }

            $html = $response->body();
            if (empty($html)) {
                return null;
            }

            return $this->extractDirectDocumentLinkFromHtml($html, $effectiveUri);
        } catch (\Throwable $e) {
            Log::info("Scrape note for academic URL [{$url}]: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Attempt to resolve an OJS article landing page to its direct galley view URL (view/ID/GALLEY).
     */
    public function resolveOjsGalleyUrl(string $url): ?string
    {
        if (! preg_match('~^(https?://[^/]+(?:/[^/]+)*)/article/view/(\d+)(?:/)?(?:[?#].*)?$~i', $url, $matches)) {
            return null;
        }

        $journalBase = $matches[1];
        $articleId = $matches[2];

        // 1. Query OAI-PMH endpoint of the OJS repository
        try {
            $parsed = parse_url($journalBase);
            $domain = $parsed['host'] ?? '';
            $oaiUrls = [
                $journalBase.'/oai?verb=GetRecord&metadataPrefix=oai_dc&identifier=oai:'.$domain.':article/'.$articleId,
                $journalBase.'/oai?verb=GetRecord&metadataPrefix=oai_dc&identifier=oai:ojs.'.$domain.':article/'.$articleId,
            ];

            foreach ($oaiUrls as $oaiUrl) {
                $response = Http::timeout(6)
                    ->withHeaders(['User-Agent' => 'IESTP-Francisco-Vigo-Caballero/VirtualLibrary'])
                    ->get($oaiUrl);

                if ($response->successful()) {
                    $xml = $response->body();

                    // Check for direct download/view link in dc:identifier
                    if (preg_match('#<dc:identifier>[^<]*/article/(?:view|download)/'.$articleId.'/(\d+)[^<]*</dc:identifier>#i', $xml, $gMatch)) {
                        return $journalBase.'/article/view/'.$articleId.'/'.$gMatch[1];
                    }

                    // Check for DOI
                    if (preg_match('#<dc:identifier>(10\.\d{4,9}/[^<]+)</dc:identifier>#i', $xml, $doiMatch)) {
                        $doi = trim($doiMatch[1]);
                        $crResponse = Http::timeout(6)
                            ->withHeaders(['User-Agent' => 'IESTP-Francisco-Vigo-Caballero/VirtualLibrary'])
                            ->get('https://api.crossref.org/works/'.urlencode($doi));

                        if ($crResponse->successful()) {
                            $links = $crResponse->json('message.link') ?? [];
                            foreach ($links as $lnk) {
                                $linkUrl = (string) ($lnk['URL'] ?? '');
                                if (preg_match('#/article/(?:download|view)/'.$articleId.'/(\d+)#i', $linkUrl, $linkMatch)) {
                                    return $journalBase.'/article/view/'.$articleId.'/'.$linkMatch[1];
                                }
                            }
                        }
                    }
                    break;
                }
            }
        } catch (\Throwable $e) {
            Log::info("OJS OAI galley resolution note [{$url}]: ".$e->getMessage());
        }

        // 2. Query Crossref directly using the landing URL
        try {
            $crQuery = Http::timeout(6)
                ->withHeaders(['User-Agent' => 'IESTP-Francisco-Vigo-Caballero/VirtualLibrary'])
                ->get('https://api.crossref.org/works', [
                    'query' => $url,
                    'rows' => 5,
                ]);

            if ($crQuery->successful()) {
                $items = $crQuery->json('message.items') ?? [];
                foreach ($items as $item) {
                    $itemUrl = (string) ($item['resource']['primary']['URL'] ?? '');
                    if (str_contains($itemUrl, '/article/view/'.$articleId)) {
                        foreach ($item['link'] ?? [] as $lnk) {
                            $linkUrl = (string) ($lnk['URL'] ?? '');
                            if (preg_match('#/article/(?:download|view)/'.$articleId.'/(\d+)#i', $linkUrl, $linkMatch)) {
                                return $journalBase.'/article/view/'.$articleId.'/'.$linkMatch[1];
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::info("OJS Crossref query resolution note [{$url}]: ".$e->getMessage());
        }

        return null;
    }

    /**
     * Extract direct document / PDF link from HTML content using standard scholarly patterns.
     */
    public function extractDirectDocumentLinkFromHtml(string $html, string $baseUrl): ?string
    {
        // 1. Highwire Press & Academic Meta Tags (citation_pdf_url, bepress, eprints, dc.identifier)
        if (preg_match('/<meta\s+[^>]*(?:name=["\'](?:citation_pdf_url|bepress_citation_pdf_url|eprints\.document_url)["\'][^>]*content=["\']([^"\']+)["\']|content=["\']([^"\']+)["\'][^>]*name=["\'](?:citation_pdf_url|bepress_citation_pdf_url|eprints\.document_url)["\'])/i', $html, $m)) {
            $link = trim($m[1] ?: $m[2]);
            if (! empty($link)) {
                $abs = $this->makeAbsoluteUrl($link, $baseUrl);

                return $this->normalizeOjsUrl($abs);
            }
        }

        // DC.Identifier / dc.identifier ending in .pdf or with article galley pattern
        if (preg_match('/<meta\s+[^>]*(?:name=["\'](?:dc\.identifier|DC\.Identifier)["\'][^>]*content=["\']([^"\']+(?:\.pdf|\/article\/(?:view|download)\/\d+\/\d+)[^"\']*)["\']|content=["\']([^"\']+(?:\.pdf|\/article\/(?:view|download)\/\d+\/\d+)[^"\']*)["\'][^>]*name=["\'](?:dc\.identifier|DC\.Identifier)["\'])/i', $html, $m)) {
            $link = trim($m[1] ?: $m[2]);
            if (! empty($link)) {
                $abs = $this->makeAbsoluteUrl($link, $baseUrl);

                return $this->normalizeOjsUrl($abs);
            }
        }

        // 2. OJS (Open Journal Systems) PDF Galley Links
        // Look specifically for PDF galley link classes (obj_galley_link with pdf)
        if (preg_match('/<a\s+[^>]*class=["\'][^"\']*obj_galley_link[^"\']*\bpdf\b[^"\']*["\'][^>]*href=["\']([^"\']+)["\']/is', $html, $m)) {
            $abs = $this->makeAbsoluteUrl($m[1], $baseUrl);

            return $this->normalizeOjsUrl($abs);
        }

        if (preg_match('/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*class=["\'][^"\']*obj_galley_link[^"\']*\bpdf\b[^"\']*["\']/is', $html, $m)) {
            $abs = $this->makeAbsoluteUrl($m[1], $baseUrl);

            return $this->normalizeOjsUrl($abs);
        }

        // Standard obj_galley_link or galley_link
        if (preg_match('/<a\s+[^>]*class=["\'][^"\']*(?:obj_galley_link|galley_link)[^"\']*["\'][^>]*href=["\']([^"\']+)["\']/is', $html, $m)) {
            $abs = $this->makeAbsoluteUrl($m[1], $baseUrl);

            return $this->normalizeOjsUrl($abs);
        }

        if (preg_match('/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*class=["\'][^"\']*(?:obj_galley_link|galley_link)[^"\']*["\']/is', $html, $m)) {
            $abs = $this->makeAbsoluteUrl($m[1], $baseUrl);

            return $this->normalizeOjsUrl($abs);
        }

        // Any link matching OJS galley route /article/(view|download)/ID/GALLEY_ID
        if (preg_match('/href=["\']([^"\']+\/article\/(?:view|download)\/\d+\/\d+[^"\']*)["\']/i', $html, $m)) {
            $abs = $this->makeAbsoluteUrl($m[1], $baseUrl);

            return $this->normalizeOjsUrl($abs);
        }

        // Legacy OJS 2.x galley route /article/viewFile/ID/GALLEY_ID
        if (preg_match('/href=["\']([^"\']+\/article\/viewFile\/\d+\/\d+[^"\']*)["\']/i', $html, $m)) {
            $abs = $this->makeAbsoluteUrl($m[1], $baseUrl);

            return $this->normalizeOjsUrl($abs);
        }

        // 3. Embedded PDF viewers (iframe, embed, object)
        if (preg_match('/<iframe\s+[^>]*src=["\']([^"\']+(?:\.pdf|\/article\/(?:view|download)\/\d+\/\d+)[^"\']*)["\']/is', $html, $m)) {
            $abs = $this->makeAbsoluteUrl($m[1], $baseUrl);

            return $this->normalizeOjsUrl($abs);
        }

        if (preg_match('/<embed\s+[^>]*src=["\']([^"\']+)["\'][^>]*type=["\']application\/pdf["\']/is', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        if (preg_match('/<object\s+[^>]*data=["\']([^"\']+)["\'][^>]*type=["\']application\/pdf["\']/is', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        // 4. SciELO PDF galley links (script=sci_pdf or /pdf/... or format=pdf)
        if (preg_match('/href=["\']([^"\']*scielo\.php\?[^"\']*script=sci_pdf[^"\']*)["\']/i', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        if (preg_match('/href=["\']([^"\']+\/pdf\/[^"\']+\.pdf)["\']/i', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        if (preg_match('/href=["\']([^"\']+[?&]format=pdf[^"\']*)["\']/i', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        // 5. Dialnet direct download
        if (preg_match('/href=["\']([^"\']*\/descarga\/articulo\/\d+\.pdf)["\']/i', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        // 6. Anchors with href ending in .pdf
        if (preg_match('/<a\s+[^>]*href=["\']([^"\']+\.pdf(?:\?[^"\']*)?)["\']/i', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        // 7. Semantic link scraping: search for anchors specifically offering PDF reading
        if (preg_match_all('/<a\s+[^>]*href=["\']([^"\'#]+)["\'][^>]*>(.*?)<\/a>/is', $html, $aMatches, PREG_SET_ORDER)) {
            foreach ($aMatches as $aMatch) {
                $href = trim($aMatch[1]);
                $text = trim(strip_tags(html_entity_decode($aMatch[2])));
                $rawTag = $aMatch[0];

                if (str_starts_with($href, 'javascript:') || str_starts_with($href, 'mailto:')) {
                    continue;
                }

                if (
                    preg_match('/\b(?:pdf|texto\s+completo|full\s*text|descargar\s+art[ií]culo|leer\s+art[ií]culo|visualizar\s+pdf|bajar\s+pdf|documento\s+completo)\b/i', $text)
                    || preg_match('/class=["\'][^"\']*\b(?:pdf|btn-pdf|download-pdf)\b[^"\']*["\']/i', $rawTag)
                    || preg_match('/title=["\'][^"\']*\bpdf\b[^"\']*["\']/i', $rawTag)
                ) {
                    $abs = $this->makeAbsoluteUrl($href, $baseUrl);

                    return $this->normalizeOjsUrl($abs);
                }
            }
        }

        return null;
    }

    /**
     * Resolve a relative URL to an absolute URL based on the parent document.
     */
    public function makeAbsoluteUrl(string $relativeUrl, string $baseUrl): string
    {
        $relativeUrl = html_entity_decode(trim($relativeUrl, " \t\n\r\0\x0B\"'"));

        if (str_starts_with($relativeUrl, 'http://') || str_starts_with($relativeUrl, 'https://')) {
            return $relativeUrl;
        }

        $parsed = parse_url($baseUrl);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? '';
        $port = isset($parsed['port']) ? ':'.$parsed['port'] : '';
        $origin = "{$scheme}://{$host}{$port}";

        if (str_starts_with($relativeUrl, '//')) {
            return "{$scheme}:{$relativeUrl}";
        }

        if (str_starts_with($relativeUrl, '/')) {
            return "{$origin}{$relativeUrl}";
        }

        // Relative path without leading slash
        $basePath = $parsed['path'] ?? '/';
        $baseDir = rtrim(dirname($basePath), '/');
        $cleanRelative = preg_replace('/^\.\//', '', $relativeUrl);

        return "{$origin}{$baseDir}/{$cleanRelative}";
    }

    /**
     * Compare whether new URL is more direct (PDF/galley) than an existing landing URL.
     */
    public function isMoreDirectDocumentUrl(string $newUrl, ?string $oldUrl): bool
    {
        if (empty($oldUrl) || $newUrl === $oldUrl) {
            return false;
        }

        $oldIsDirect = (bool) preg_match('/\.pdf($|\?)|\/article\/(?:view|download)\/\d+\/\d+|script=sci_pdf|format=pdf|\/descarga\/articulo\/\d+\.pdf/i', $oldUrl);
        $newIsDirect = (bool) preg_match('/\.pdf($|\?)|\/article\/(?:view|download)\/\d+\/\d+|script=sci_pdf|format=pdf|\/descarga\/articulo\/\d+\.pdf/i', $newUrl);

        if ($newIsDirect && ! $oldIsDirect) {
            return true;
        }

        // Prefer /article/view/ID/GALLEY over /article/download/ID/GALLEY for iframe reading
        if (str_contains($oldUrl, '/article/download/') && str_contains($newUrl, '/article/view/')) {
            return true;
        }

        return false;
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

        // Ensure URL is direct document link
        $item['external_url'] = $this->resolveDirectDocumentLink($item['external_url']);

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

        // Match existing record by external URL OR by title + study program OR by title
        $existing = Book::where('external_url', $item['external_url'])->first();

        if (! $existing && ! empty($item['study_program_id'])) {
            $existing = Book::where('study_program_id', $item['study_program_id'])
                ->where('title', $item['title'])
                ->first();
        }

        if (! $existing) {
            $existing = Book::where('title', $item['title'])->first();
        }

        // Match if existing record has base OJS landing URL corresponding to this galley
        if (! $existing && preg_match('#(/article/(?:view|download)/\d+)#i', $item['external_url'], $baseOjsMatch)) {
            $baseOjs = $baseOjsMatch[1];
            $existing = Book::where('external_url', 'like', '%'.$baseOjs)
                ->orWhere('external_url', 'like', '%'.$baseOjs.'/%')
                ->first();
        }

        if ($existing) {
            // Update metadata while preserving admin customization and active status
            $updates = [
                'author' => $existing->author ?: ($item['author'] ?? null),
                'description' => $existing->description ?: ($item['description'] ?? null),
                'publisher' => $existing->publisher ?: ($item['publisher'] ?? null),
                'publication_year' => $existing->publication_year ?: ($item['publication_year'] ?? null),
                'isbn' => $existing->isbn ?: ($item['isbn'] ?? null),
            ];

            // Upgrade external URL if the newly scraped URL is more direct
            if ($this->isMoreDirectDocumentUrl($item['external_url'], $existing->external_url)) {
                $updates['external_url'] = $item['external_url'];
            }

            $existing->update($updates);

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
