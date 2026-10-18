<?php

namespace App\Services;

use App\Models\Book;
use App\Models\StudyProgram;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

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
     * Browser-like headers used for scraping and page scanning.
     *
     * @var array<string, string>
     */
    public const SCRAPE_HEADERS = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,application/pdf;q=0.8,*/*;q=0.7',
        'Accept-Language' => 'es-PE,es-ES;q=0.9,es;q=0.8,en;q=0.7',
        'Sec-Ch-Ua' => '"Chromium";v="128", "Not;A=Brand";v="24", "Google Chrome";v="128"',
        'Sec-Ch-Ua-Mobile' => '?0',
        'Sec-Ch-Ua-Platform' => '"Windows"',
        'Sec-Fetch-Dest' => 'document',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'none',
        'Sec-Fetch-User' => '?1',
        'Upgrade-Insecure-Requests' => '1',
    ];

    /**
     * Standard academic API headers.
     *
     * @var array<string, string>
     */
    public const API_HEADERS = [
        'User-Agent' => 'IESTP-Francisco-Vigo-Caballero/VirtualLibrary (contacto@iestpfvc.edu.pe; academic-integration)',
        'Accept' => 'application/json',
    ];

    /**
     * Patterns that identify non-article PDF files (author guidelines, templates, policies, etc.)
     * to prevent the scraper from saving site instructions instead of the actual book/paper.
     */
    private const EXCLUDED_PDF_PATTERNS = [
        'instrucciones',
        'guia-de-autores',
        'guia_autores',
        'author-guidelines',
        'normas-editoriales',
        'normas_autores',
        'plantilla',
        'template',
        'copyright',
        'cesion-de-derechos',
        'codigo-de-etica',
        'ethics',
        'declaracion-de-originalidad',
        'convocatoria',
        'call-for-papers',
        'manual-ojs',
        'license.txt',
    ];

    /**
     * In-memory cache to avoid scraping the same URL twice in a single batch run.
     *
     * @var array<string, string>
     */
    private array $resolvedUrlCache = [];

    // ══════════════════════════════════════════════════════════════
    // MAIN ENTRY POINT
    // ══════════════════════════════════════════════════════════════

    /**
     * Search and bulk-import academic resources (journals, books, papers, theses, articles).
     *
     * @param array{
     *     study_program_id?: int|string|null,
     *     category?: string|null,
     *     sources?: array<string>|null,
     *     custom_query?: string|null,
     *     limit?: int,
     *     created_by?: int|null,
     *     strict_pdf_only?: bool
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
        $strictPdfOnly = (bool) ($options['strict_pdf_only'] ?? true);

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

                            if ($strictPdfOnly && ! $this->isDirectDocumentOrPdfUrl((string) ($item['external_url'] ?? ''), true)) {
                                $skippedCount++;

                                continue;
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

                // ── 2. Fetch from DOAJ API (Directory of Open Access Journals with direct fulltext links) ──
                if (in_array('doaj', $sources)) {
                    try {
                        $doajResults = $this->queryDoaj(
                            $term,
                            $config,
                            $currentProgramId,
                            $categoryFilter,
                            $limit - ($savedCount + $updatedCount)
                        );

                        foreach ($doajResults as $item) {
                            if (($savedCount + $updatedCount) >= $limit) {
                                break;
                            }

                            if ($strictPdfOnly && ! $this->isDirectDocumentOrPdfUrl((string) ($item['external_url'] ?? ''), true)) {
                                $skippedCount++;

                                continue;
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

                        if (! empty($doajResults)) {
                            $log[] = "DOAJ [{$config['name']}] «{$term}»: ".count($doajResults).' registros procesados.';
                        }
                    } catch (\Throwable $e) {
                        $errors[] = "DOAJ [{$term}]: {$e->getMessage()}";
                        Log::warning("AcademicLibraryService DOAJ error [{$term}]: ".$e->getMessage());
                    }
                }

                if (($savedCount + $updatedCount) >= $limit) {
                    break;
                }

                // ── 3. Fetch from OpenAlex API (Covers SciELO, Redalyc, Scopus OA, Dialnet, University Repositories & Books) ──
                if (
                    in_array('scielo', $sources) ||
                    in_array('redalyc', $sources) ||
                    in_array('openalex', $sources) ||
                    in_array('scopus', $sources)
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

                            if ($strictPdfOnly && ! $this->isDirectDocumentOrPdfUrl((string) ($item['external_url'] ?? ''), true)) {
                                $skippedCount++;

                                continue;
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

                if (($savedCount + $updatedCount) >= $limit) {
                    break;
                }

                // ── 4. Fallback to Crossref API (Official Scholarly DOI Registry + Unpaywall OA PDF) ──
                if (in_array('crossref', $sources)) {
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

                            if ($strictPdfOnly && ! $this->isDirectDocumentOrPdfUrl((string) ($item['external_url'] ?? ''), true)) {
                                $skippedCount++;

                                continue;
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
                usleep(200000); // 0.20s
            }
        }

        $total = $savedCount + $updatedCount;
        $message = $total > 0
            ? "Búsqueda académica completada: {$savedCount} nuevos documentos PDF directos indexados y {$updatedCount} actualizados ({$skippedCount} omitidos o ya existentes en el repositorio)."
            : 'Búsqueda completada. No se encontraron nuevos documentos con PDF directo bajo los criterios seleccionados o ya se encuentran registrados.';

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

    // ══════════════════════════════════════════════════════════════
    // SOURCE 1: GOOGLE SCHOLAR (DIRECT PDF SCANNING & SCRAPING)
    // ══════════════════════════════════════════════════════════════

    /**
     * Search and parse Google Scholar results for direct document / PDF links.
     *
     * @param  array<string, mixed>  $config
     * @return array<int, array<string, mixed>>
     */
    public function queryGoogleScholar(string $term, array $config, int $programId, ?string $categoryFilter, int $batchLimit): array
    {
        $maxItems = max(1, min($batchLimit, 15));

        $categoryModifier = match ($categoryFilter) {
            'Libro' => ' (libro OR manual OR "open access" OR pdf)',
            'Revista' => ' (revista OR journal OR scielo OR redalyc OR pdf)',
            'Tesis' => ' (tesis OR repositorio OR dissertation OR pdf)',
            'Documento' => ' (informe OR manual OR guia OR pdf)',
            default => ' (scielo OR redalyc OR repositorio OR "open access" OR pdf)',
        };

        $scholarQuery = $term.$categoryModifier;

        try {
            $response = Http::timeout(8)
                ->withHeaders(self::SCRAPE_HEADERS)
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
     * Parse HTML output from Google Scholar search results using Symfony DomCrawler (with Regex fallback).
     *
     * @param  array<string, mixed>  $config
     * @return array<int, array<string, mixed>>
     */
    public function parseGoogleScholarHtml(string $html, array $config, int $programId, ?string $categoryFilter, int $maxItems): array
    {
        $results = [];

        try {
            $crawler = new Crawler($html);
            $blocks = $crawler->filter('div.gs_r.gs_or');
            if ($blocks->count() === 0) {
                $blocks = $crawler->filter('div[class*="gs_r"]');
            }

            if ($blocks->count() > 0) {
                $blocks->each(function (Crawler $block) use (&$results, $maxItems, $config, $programId, $categoryFilter): void {
                    if (count($results) >= $maxItems) {
                        return;
                    }

                    try {
                        // 1. Direct PDF / Galley link on the right (.gs_or_ggsm, .gs_ggs)
                        $directDocLink = null;
                        $badgeAnchors = $block->filter('div.gs_or_ggsm a, div.gs_ggs a');
                        foreach ($badgeAnchors as $badgeAnchor) {
                            $href = trim($badgeAnchor->getAttribute('href') ?? '');
                            if ($href !== '' && ! str_starts_with($href, 'javascript:') && $href !== '#') {
                                $directDocLink = html_entity_decode($href);
                                break;
                            }
                        }

                        // 2. Article Title and Landing URL (h3.gs_rt a)
                        $titleNode = $block->filter('h3.gs_rt a');
                        $landingUrl = '';
                        $title = '';

                        if ($titleNode->count() > 0) {
                            $landingUrl = html_entity_decode(trim((string) $titleNode->first()->attr('href')));
                            $title = trim(strip_tags(html_entity_decode($titleNode->first()->text())));
                        } else {
                            $h3Node = $block->filter('h3.gs_rt');
                            if ($h3Node->count() > 0) {
                                $title = trim(strip_tags(html_entity_decode($h3Node->first()->text())));
                            }
                        }

                        $title = trim((string) preg_replace('/^\[(?:PDF|LIBRO|BOOK|HTML|CITA)\]\s*/i', '', $title));
                        if (mb_strlen($title) < 5) {
                            return;
                        }

                        // Determine candidate link: direct badge link has highest priority
                        $candidateUrl = ($directDocLink && ! str_starts_with($directDocLink, 'javascript:'))
                            ? $directDocLink
                            : $landingUrl;

                        if ($candidateUrl === '' || str_starts_with($candidateUrl, 'javascript:') || $candidateUrl === '#') {
                            return;
                        }

                        // 3. Authors, Journal & Year (.gs_a)
                        $author = 'Varios Autores / Colectivo Académico';
                        $year = null;
                        $publisherName = 'Google Scholar / Repositorio Académico';

                        $metaNode = $block->filter('div.gs_a');
                        if ($metaNode->count() > 0) {
                            $metaText = trim(strip_tags(html_entity_decode($metaNode->first()->text())));
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
                        $snippetNode = $block->filter('div.gs_rs');
                        if ($snippetNode->count() > 0) {
                            $description = trim(strip_tags(html_entity_decode($snippetNode->first()->text())));
                        }

                        if (! $description) {
                            $description = "Publicación académica verificada por Google Scholar («{$title}»). Documento indexado para investigación y formación técnica tecnológica.";
                        }

                        // 5. Resolve to direct document / PDF link; if badge link fails, also try scraping landingUrl
                        $finalUrl = $this->resolveDirectDocumentLink($candidateUrl);
                        if (! $this->isDirectDocumentOrPdfUrl($finalUrl, false) && $landingUrl !== '' && $landingUrl !== $candidateUrl) {
                            $fromLanding = $this->resolveDirectDocumentLink($landingUrl);
                            if ($this->isDirectDocumentOrPdfUrl($fromLanding, false)) {
                                $finalUrl = $fromLanding;
                            }
                        }

                        if ($finalUrl === '' || str_starts_with($finalUrl, 'javascript:') || $finalUrl === '#') {
                            return;
                        }

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
                            'external_url' => Str::limit($finalUrl, 495),
                            'rating' => 5.0,
                            'is_active' => true,
                        ];
                    } catch (\Throwable $e) {
                        Log::info('Google Scholar block parse error: '.$e->getMessage());
                    }
                });

                if (! empty($results)) {
                    return $results;
                }
            }
        } catch (\Throwable $e) {
            Log::info('Google Scholar DomCrawler parse error: '.$e->getMessage());
        }

        return $this->parseGoogleScholarHtmlLegacy($html, $config, $programId, $categoryFilter, $maxItems);
    }

    /**
     * Legacy regex-based fallback parser for Google Scholar HTML.
     *
     * @param  array<string, mixed>  $config
     * @return array<int, array<string, mixed>>
     */
    private function parseGoogleScholarHtmlLegacy(string $html, array $config, int $programId, ?string $categoryFilter, int $maxItems): array
    {
        $results = [];

        preg_match_all('/<div[^>]*class=["\'][^"\']*gs_r\s+gs_or[^"\']*["\'][^>]*>(.*?)<\/div>\s*(?=<div[^>]*class=["\'][^"\']*gs_r\s+gs_or[^"\']*["\']|$)/is', $html, $matches);

        if (empty($matches[1])) {
            preg_match_all('/<div[^>]*class=["\']gs_ri["\'][^>]*>(.*?)<\/div>\s*<\/div>/is', $html, $matches);
        }

        $items = $matches[1] ?? [];

        foreach ($items as $block) {
            if (count($results) >= $maxItems) {
                break;
            }

            $directDocLink = null;
            if (preg_match('/<div[^>]*class=["\'][^"\']*(?:gs_or_ggsm|gs_ggs)[^"\']*["\'][^>]*>.*?<a[^>]*href=["\'](https?:\/\/[^"\']+)["\']/is', $block, $pdfMatch)) {
                $directDocLink = html_entity_decode(trim($pdfMatch[1]));
            } elseif (preg_match('/<a[^>]*href=["\'](https?:\/\/[^"\']+)["\'][^>]*>.*?(?:\[PDF\]|\.pdf\b)/is', $block, $pdfMatch)) {
                $directDocLink = html_entity_decode(trim($pdfMatch[1]));
            }

            if (! preg_match('/<h3[^>]*class=["\'][^"\']*gs_rt[^"\']*["\'][^>]*>\s*<a[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $block, $titleMatch)) {
                continue;
            }

            $landingUrl = html_entity_decode(trim($titleMatch[1]));
            $title = trim(strip_tags(html_entity_decode($titleMatch[2])));

            if (mb_strlen($title) < 5) {
                continue;
            }

            $candidateUrl = ($directDocLink && ! str_starts_with($directDocLink, 'javascript:')) ? $directDocLink : $landingUrl;
            if ($candidateUrl === '' || str_starts_with($candidateUrl, 'javascript:') || $candidateUrl === '#') {
                continue;
            }

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

            $description = null;
            if (preg_match('/<div[^>]*class=["\'][^"\']*gs_rs[^"\']*["\'][^>]*>(.*?)<\/div>/is', $block, $snippetMatch)) {
                $description = trim(strip_tags(html_entity_decode($snippetMatch[1])));
            }

            if (! $description) {
                $description = "Publicación académica verificada por Google Scholar («{$title}»). Documento indexado para investigación y formación técnica tecnológica.";
            }

            $finalUrl = $this->resolveDirectDocumentLink($candidateUrl);
            if ($finalUrl === '' || str_starts_with($finalUrl, 'javascript:') || $finalUrl === '#') {
                continue;
            }

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
                'external_url' => Str::limit($finalUrl, 495),
                'rating' => 5.0,
                'is_active' => true,
            ];
        }

        return $results;
    }

    // ══════════════════════════════════════════════════════════════
    // SOURCE 2: DOAJ API (DIRECTORY OF OPEN ACCESS JOURNALS)
    // ══════════════════════════════════════════════════════════════

    /**
     * Query DOAJ API (Directory of Open Access Journals) - free, verified public open-access index.
     *
     * @param  array<string, mixed>  $config
     * @return array<int, array<string, mixed>>
     */
    public function queryDoaj(string $term, array $config, int $programId, ?string $categoryFilter, int $batchLimit): array
    {
        $pageSize = max(1, min($batchLimit, 15));
        $cleanTerm = trim((string) preg_replace('/\b(scielo|redalyc|google\s+scholar)\b/i', '', $term));
        if ($cleanTerm === '') {
            $cleanTerm = $config['name'] ?? 'investigacion tecnologica';
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders(self::API_HEADERS)
                ->get('https://doaj.org/api/search/articles/'.rawurlencode($cleanTerm), [
                    'pageSize' => $pageSize,
                ]);

            if (! $response->successful()) {
                return [];
            }

            $results = [];
            $items = $response->json('results') ?? [];

            foreach ($items as $item) {
                $bib = $item['bibjson'] ?? [];
                $title = trim((string) ($bib['title'] ?? ''));
                if ($title === '' || mb_strlen($title) < 5) {
                    continue;
                }

                $authors = collect($bib['author'] ?? [])
                    ->map(fn ($a) => is_array($a) ? trim($a['name'] ?? '') : (string) $a)
                    ->filter()
                    ->take(3)
                    ->implode(', ');
                $author = $authors ?: 'Varios Autores / Colectivo Académico';

                $journalTitle = $bib['journal']['title'] ?? ($bib['journal']['publisher'] ?? 'DOAJ Open Access');
                $publisherName = Str::limit('DOAJ / '.$journalTitle, 145);

                // Prioritize links explicitly marked as PDF over generic fulltext links
                $candidateUrl = null;
                $links = (array) ($bib['link'] ?? []);
                foreach ($links as $link) {
                    $cType = strtolower((string) ($link['content_type'] ?? ''));
                    $u = trim((string) ($link['url'] ?? ''));
                    if ($u !== '' && (str_contains($cType, 'pdf') || preg_match('/\.pdf($|\?)|script=sci_pdf|\/pdf\//i', $u))) {
                        $candidateUrl = $u;
                        break;
                    }
                }

                if (! $candidateUrl) {
                    foreach ($links as $link) {
                        $type = strtolower((string) ($link['type'] ?? ''));
                        $u = trim((string) ($link['url'] ?? ''));
                        if ($u !== '' && $type === 'fulltext') {
                            $candidateUrl = $u;
                            break;
                        }
                    }
                }

                $doi = null;
                foreach ((array) ($bib['identifier'] ?? []) as $idObj) {
                    if (($idObj['type'] ?? '') === 'doi') {
                        $doi = $idObj['id'] ?? null;
                        break;
                    }
                }

                if ($doi && (! $candidateUrl || ! $this->isDirectDocumentOrPdfUrl($candidateUrl, false))) {
                    $unpaywallPdf = $this->resolveDoiToOpenAccessPdf($doi);
                    if ($unpaywallPdf) {
                        $candidateUrl = $unpaywallPdf;
                    }
                }

                if (! $candidateUrl) {
                    continue;
                }

                $externalUrl = $this->resolveDirectDocumentLink($candidateUrl);
                if ($externalUrl === '' || str_starts_with($externalUrl, 'javascript:')) {
                    continue;
                }

                $abstract = trim((string) ($bib['abstract'] ?? ''));
                $year = ! empty($bib['year']) ? (int) $bib['year'] : null;

                if ($abstract === '') {
                    $abstract = "Artículo científico revisado por pares («{$title}»). Publicado en {$publisherName}".($year ? " ({$year})" : '').'. Acceso abierto verificado por DOAJ para investigación y educación tecnológica.';
                }

                $category = $this->mapCategory('article', $categoryFilter ?: ($config['default_category'] ?? 'Paper'));

                $results[] = [
                    'title' => Str::limit($title, 250),
                    'author' => Str::limit($author, 250),
                    'study_program_id' => $programId > 0 ? $programId : null,
                    'category' => $category,
                    'description' => Str::limit($abstract, 950),
                    'publisher' => $publisherName,
                    'publication_year' => $year,
                    'edition' => '1ª Edición Digital',
                    'pages' => ! empty($bib['end_page']) && ! empty($bib['start_page']) ? max(1, (int) $bib['end_page'] - (int) $bib['start_page'] + 1) : null,
                    'isbn' => $doi ? Str::limit($doi, 45) : null,
                    'language' => 'Español',
                    'external_url' => Str::limit($externalUrl, 495),
                    'rating' => 5.0,
                    'is_active' => true,
                ];
            }

            return $results;
        } catch (\Throwable $e) {
            Log::info("DOAJ query note [{$term}]: ".$e->getMessage());

            return [];
        }
    }

    // ══════════════════════════════════════════════════════════════
    // SOURCE 3: OPENALEX API (SCIELO, REDALYC, SCOPUS OA, BOOKS & THESES)
    // ══════════════════════════════════════════════════════════════

    /**
     * Query OpenAlex API (Covers SciELO, Redalyc, DOAJ, Scopus OA journals, Dialnet, and University DSpace Repositories).
     *
     * @param  array<string, mixed>  $config
     * @param  array<string>  $sources
     * @return array<int, array<string, mixed>>
     */
    private function queryOpenAlex(string $term, array $config, int $programId, ?string $categoryFilter, array $sources, int $batchLimit): array
    {
        $perPage = max(1, min($batchLimit, 25));

        $filters = ['is_oa:true'];
        $enhancedSearch = $term;

        if ($categoryFilter === 'Libro') {
            $filters[] = 'type:book';
            $enhancedSearch .= ' libro OR book OR manual';
        } elseif ($categoryFilter === 'Revista') {
            $filters[] = 'type:article';
            $enhancedSearch .= ' revista OR journal';
        } elseif ($categoryFilter === 'Tesis') {
            $filters[] = 'type:dissertation';
            $enhancedSearch .= ' tesis';
        }

        if (in_array('scielo', $sources) && ! str_contains(mb_strtolower($enhancedSearch), 'scielo')) {
            $enhancedSearch .= ' scielo';
        }

        $response = Http::timeout(12)
            ->withHeaders(self::API_HEADERS)
            ->get('https://api.openalex.org/works', [
                'search' => $enhancedSearch,
                'filter' => implode(',', $filters),
                'per_page' => $perPage,
            ]);

        if (! $response->successful() && $categoryFilter === 'Libro') {
            $response = Http::timeout(12)
                ->withHeaders(self::API_HEADERS)
                ->get('https://api.openalex.org/works', [
                    'search' => $enhancedSearch,
                    'filter' => 'is_oa:true',
                    'per_page' => $perPage,
                ]);
        }

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

            $authors = collect($item['authorships'] ?? [])
                ->pluck('author.display_name')
                ->filter()
                ->take(3)
                ->implode(', ');
            $author = $authors ?: 'Varios Autores / Colectivo Académico';

            $sourceName = $item['primary_location']['source']['display_name'] ?? null;
            $hostOrg = $item['primary_location']['source']['host_organization_name'] ?? null;
            $publisherName = $sourceName ? ($hostOrg ? "{$sourceName} ({$hostOrg})" : $sourceName) : 'Repositorio Científico Abierto';

            $tag = '';
            $checkText = mb_strtolower($publisherName.' '.json_encode($item));
            if (str_contains($checkText, 'scielo')) {
                $tag = 'SciELO / ';
            } elseif (str_contains($checkText, 'redalyc')) {
                $tag = 'Redalyc / ';
            } elseif (str_contains($checkText, 'elsevier') || str_contains($checkText, 'scopus')) {
                $tag = 'Scopus - Elsevier OA / ';
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

            // If we don't have a direct PDF yet, query Unpaywall with DOI
            $doiClean = ! empty($item['doi']) ? Str::after($item['doi'], 'doi.org/') : null;
            if (! $pdfUrl && $doiClean) {
                $unpaywallPdf = $this->resolveDoiToOpenAccessPdf($doiClean);
                if ($unpaywallPdf) {
                    $candidateUrl = $unpaywallPdf;
                }
            }

            $externalUrl = $this->resolveDirectDocumentLink($candidateUrl);
            if ($externalUrl === '' || str_starts_with($externalUrl, 'javascript:')) {
                continue;
            }

            $year = ! empty($item['publication_year']) ? (int) $item['publication_year'] : null;
            $type = $item['type'] ?? 'article';
            $category = $this->mapCategory($type, $categoryFilter ?: ($config['default_category'] ?? 'Paper'));

            $description = $this->reconstructAbstract($item['abstract_inverted_index'] ?? null);
            if (! $description) {
                $description = "Publicación académica revisada por pares («{$title}»). Publicado en {$finalPublisher}".($year ? " ({$year})" : '').'. Acceso abierto institucional disponible para investigación y formación tecnológica.';
            }

            $results[] = [
                'title' => Str::limit($title, 250),
                'author' => Str::limit($author, 250),
                'study_program_id' => $programId > 0 ? $programId : null,
                'category' => $category,
                'description' => Str::limit($description, 950),
                'publisher' => $finalPublisher,
                'publication_year' => $year,
                'edition' => '1ª Edición Digital',
                'pages' => ! empty($item['biblio']['last_page']) ? (int) $item['biblio']['last_page'] : null,
                'isbn' => $doiClean ? Str::limit($doiClean, 45) : null,
                'language' => 'Español',
                'external_url' => Str::limit($externalUrl, 495),
                'rating' => 5.0,
                'is_active' => true,
            ];
        }

        return $results;
    }

    // ══════════════════════════════════════════════════════════════
    // SOURCE 4: CROSSREF API (OFFICIAL SCHOLARLY DOI REGISTRY)
    // ══════════════════════════════════════════════════════════════

    /**
     * Query Crossref API (Official Scholarly DOI Registry).
     * Extracts direct full-text PDF links and uses Unpaywall for verified OA resolution.
     *
     * @param  array<string, mixed>  $config
     * @return array<int, array<string, mixed>>
     */
    private function queryCrossref(string $term, array $config, int $programId, ?string $categoryFilter, int $batchLimit): array
    {
        $rows = max(1, min($batchLimit, 15));
        $response = Http::timeout(12)
            ->withHeaders(self::API_HEADERS)
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

            $authors = collect($item['author'] ?? [])
                ->map(fn ($a) => trim(($a['given'] ?? '').' '.($a['family'] ?? '')))
                ->filter()
                ->take(3)
                ->implode(', ');
            $author = $authors ?: 'Varios Autores / Colectivo Académico';

            $containerTitle = $item['container-title'][0] ?? null;
            $publisherRaw = $item['publisher'] ?? 'Crossref';
            $publisherName = $containerTitle ? "{$containerTitle} ({$publisherRaw})" : $publisherRaw;
            $finalPublisher = Str::limit($publisherName, 145);

            // Extract ONLY genuine PDF / Galley links from Crossref (fixed bug that grabbed XML/HTML links)
            $crossrefPdfUrl = null;
            if (! empty($item['link']) && is_array($item['link'])) {
                foreach ($item['link'] as $lnk) {
                    $ct = strtolower((string) ($lnk['content-type'] ?? ''));
                    $u = (string) ($lnk['URL'] ?? '');
                    if ($u !== '' && (str_contains($ct, 'pdf') || preg_match('/\.pdf($|\?)|\/article\/(?:view|download)\/\d+\/\d+|format=pdf|script=sci_pdf/i', $u))) {
                        $crossrefPdfUrl = $u;
                        break;
                    }
                }
            }

            $doi = $item['DOI'] ?? null;
            $landingUrl = $item['resource']['primary']['URL'] ?? ($item['URL'] ?? null);
            $candidateUrl = $crossrefPdfUrl ?: $landingUrl;

            // Query Unpaywall if no direct PDF was found in Crossref links
            if (! $crossrefPdfUrl && $doi) {
                $unpaywallPdf = $this->resolveDoiToOpenAccessPdf($doi);
                if ($unpaywallPdf) {
                    $candidateUrl = $unpaywallPdf;
                }
            }

            if (! $candidateUrl) {
                continue;
            }

            $externalUrl = $this->resolveDirectDocumentLink($candidateUrl);
            if ($externalUrl === '' || str_starts_with($externalUrl, 'javascript:')) {
                continue;
            }

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
                'pages' => ! empty($item['page']) ? (int) Str::before($item['page'], '-') : null,
                'isbn' => $doi ? Str::limit($doi, 45) : null,
                'language' => 'Español',
                'external_url' => Str::limit($externalUrl, 495),
                'rating' => 5.0,
                'is_active' => true,
            ];
        }

        return $results;
    }

    // ══════════════════════════════════════════════════════════════
    // DOI → OPEN-ACCESS PDF RESOLUTION (UNPAYWALL)
    // ══════════════════════════════════════════════════════════════

    /**
     * Use the Unpaywall API to resolve a DOI to its best open-access PDF URL.
     *
     * @param  string  $doi  Raw DOI e.g. "10.1234/foo" or full URL "https://doi.org/10.1234/foo"
     */
    public function resolveDoiToOpenAccessPdf(string $doi): ?string
    {
        if (empty($doi)) {
            return null;
        }

        $doi = (string) preg_replace('#^https?://(?:dx\.)?doi\.org/#i', '', trim($doi));
        $doi = trim($doi, '/');

        if ($doi === '' || ! str_contains($doi, '/')) {
            return null;
        }

        try {
            $response = Http::timeout(6)
                ->withHeaders(self::API_HEADERS)
                ->get('https://api.unpaywall.org/v2/'.rawurlencode($doi), [
                    'email' => 'contacto@iestpfvc.edu.pe',
                ]);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            $bestOa = $data['best_oa_location'] ?? null;
            if ($bestOa && ! empty($bestOa['url_for_pdf'])) {
                return $this->normalizeOjsUrl((string) $bestOa['url_for_pdf']);
            }

            foreach (($data['oa_locations'] ?? []) as $loc) {
                if (! empty($loc['url_for_pdf'])) {
                    return $this->normalizeOjsUrl((string) $loc['url_for_pdf']);
                }
            }
        } catch (\Throwable $e) {
            Log::info("Unpaywall DOI resolution note [{$doi}]: ".$e->getMessage());
        }

        return null;
    }

    // ══════════════════════════════════════════════════════════════
    // OJS & REPOSITORY URL NORMALIZATION
    // ══════════════════════════════════════════════════════════════

    /**
     * Normalize an OJS document URL to ensure it points to the interactive viewer route.
     */
    public function normalizeOjsUrl(string $url): string
    {
        $url = trim($url);

        if (preg_match('~(/article/)download/([^/?#]+/[^/?#]+)(?:/[^/?#]+)?~i', $url)) {
            $url = (string) preg_replace('~(/article/)download/([^/?#]+/[^/?#]+)(?:/[^/?#]+)?~i', '$1view/$2', $url);
        }

        return $url;
    }

    // ══════════════════════════════════════════════════════════════
    // DIRECT DOCUMENT LINK RESOLUTION PIPELINE
    // ══════════════════════════════════════════════════════════════

    /**
     * Resolve a publication URL to its most direct document / PDF view link.
     */
    public function resolveDirectDocumentLink(string $url, ?string $fallback = null): string
    {
        $url = trim($url);
        if ($url === '' || str_starts_with($url, 'javascript:')) {
            return $fallback ?? '';
        }

        if (isset($this->resolvedUrlCache[$url])) {
            return $this->resolvedUrlCache[$url];
        }

        // Unwrap PDF.js or generic viewer URLs that embed the PDF path in ?file= or ?url=
        if (preg_match('/[?&](?:file|pdf|doc|url)=(https?%3A%2F%2F[^&#]+|https?:\/\/[^&#]+|%2F[^&#]+\.pdf)/i', $url, $viewerMatch)) {
            $url = $this->makeAbsoluteUrl(rawurldecode($viewerMatch[1]), $url);
        }

        // Normalize OJS download URLs to direct viewer URLs (/article/view/ID/GALLEY)
        $url = $this->normalizeOjsUrl($url);

        // Fast check 1: already ends in .pdf or contains direct PDF asset
        if (preg_match('/\.pdf($|\?)/i', $url) && ! $this->isExcludedPdfUrl($url)) {
            return $this->resolvedUrlCache[$url] = $url;
        }

        // Fast check 2: OJS Galley already present (e.g. /article/view/350/394)
        if (preg_match('/\/article\/view\/\d+\/\d+/i', $url)) {
            return $this->resolvedUrlCache[$url] = $url;
        }

        // Fast check 3: Dialnet article & thesis landing pages -> direct PDF download link
        if (preg_match('/dialnet\.unirioja\.es\/servlet\/articulo\?codigo=(\d+)/i', $url, $dMatch)) {
            return $this->resolvedUrlCache[$url] = "https://dialnet.unirioja.es/descarga/articulo/{$dMatch[1]}.pdf";
        }
        if (preg_match('/dialnet\.unirioja\.es\/servlet\/tesis\?codigo=(\d+)/i', $url, $tMatch)) {
            return $this->resolvedUrlCache[$url] = "https://dialnet.unirioja.es/descarga/tesis/{$tMatch[1]}.pdf";
        }

        // Fast check 4: Redalyc article landing pages -> direct PDF link
        if (preg_match('/redalyc\.org\/articulo\.oa\?id=(\d{7,})/i', $url, $rMatch)) {
            $oaId = $rMatch[1];
            $journalPrefix = substr($oaId, 0, -3);

            return $this->resolvedUrlCache[$url] = "https://www.redalyc.org/pdf/{$journalPrefix}/{$oaId}.pdf";
        }
        if (preg_match('/redalyc\.org\/journal\/(\d+)\/(\d+)(?:\/html\/?)?$/i', $url, $rjMatch)) {
            return $this->resolvedUrlCache[$url] = "https://www.redalyc.org/journal/{$rjMatch[1]}/{$rjMatch[2]}/{$rjMatch[2]}.pdf";
        }

        // Fast check 5: SciELO arttext -> try direct PDF scraping or sci_pdf transformation
        if (str_contains($url, 'scielo.php') && str_contains($url, 'script=sci_arttext')) {
            $pdfCandidate = str_replace('script=sci_arttext', 'script=sci_pdf', $url);
            $scraped = $this->scrapeDirectDocumentLink($url);
            if ($scraped && ! str_starts_with($scraped, 'javascript:')) {
                return $this->resolvedUrlCache[$url] = $this->normalizeOjsUrl($scraped);
            }

            return $this->resolvedUrlCache[$url] = $pdfCandidate;
        }

        // Fast check 6: SciELO modern URL without format=pdf
        if (preg_match('#scielo\.[a-z.]+/j/[^/]+/a/[^/?]+#i', $url) && ! str_contains($url, 'format=pdf')) {
            $scraped = $this->scrapeDirectDocumentLink($url);
            if ($scraped && ! str_starts_with($scraped, 'javascript:')) {
                return $this->resolvedUrlCache[$url] = $this->normalizeOjsUrl($scraped);
            }

            $sep = str_contains($url, '?') ? '&' : '?';

            return $this->resolvedUrlCache[$url] = $url.$sep.'format=pdf';
        }

        // Fast check 7: arXiv abstract URL -> convert to direct PDF URL
        if (preg_match('#arxiv\.org/abs/([\w.]+)#i', $url, $arxivMatch)) {
            return $this->resolvedUrlCache[$url] = 'https://arxiv.org/pdf/'.$arxivMatch[1].'.pdf';
        }

        // Fast check 8: PubMed Central PMC article -> scrape or convert to direct PMC PDF
        if (preg_match('#(?:pubmedcentral|ncbi\.nlm\.nih\.gov/pmc/articles)/PMC(\d+)#i', $url, $pmcMatch)) {
            $pmcId = $pmcMatch[1];
            $directPmc = "https://www.ncbi.nlm.nih.gov/pmc/articles/PMC{$pmcId}/pdf/";
            $scraped = $this->scrapeDirectDocumentLink($url);
            if ($scraped && ! str_starts_with($scraped, 'javascript:')) {
                return $this->resolvedUrlCache[$url] = $this->normalizeOjsUrl($scraped);
            }

            return $this->resolvedUrlCache[$url] = $directPmc;
        }

        // Fast check 9: DOI URL -> query Unpaywall for direct open-access PDF
        if (preg_match('#^https?://(?:dx\.)?doi\.org/(10\.\d{4,9}/.+)$#i', $url, $doiMatch)) {
            $resolvedPdf = $this->resolveDoiToOpenAccessPdf($doiMatch[1]);
            if ($resolvedPdf) {
                return $this->resolvedUrlCache[$url] = $resolvedPdf;
            }
        }

        // Active DOM scraping: Fetch HTML and parse meta tags, OJS galley, bitstreams, and PDF anchors
        $scrapedLink = $this->scrapeDirectDocumentLink($url);
        if ($scrapedLink && ! str_starts_with($scrapedLink, 'javascript:')) {
            return $this->resolvedUrlCache[$url] = $this->normalizeOjsUrl($scrapedLink);
        }

        // OJS Galley Fallback: If scraping failed or was blocked, attempt OJS OAI / Crossref resolution
        $ojsResolved = $this->resolveOjsGalleyUrl($url);
        if ($ojsResolved && ! str_starts_with($ojsResolved, 'javascript:')) {
            return $this->resolvedUrlCache[$url] = $ojsResolved;
        }

        return $this->resolvedUrlCache[$url] = ($fallback ?? $url);
    }

    // ══════════════════════════════════════════════════════════════
    // HTML SCRAPING & PAGE SCANNING
    // ══════════════════════════════════════════════════════════════

    /**
     * Scrape HTML page to retrieve direct document / PDF link.
     * Follows redirects, inspects Content-Type & magic bytes (%PDF-), and parses DOM.
     */
    public function scrapeDirectDocumentLink(string $url): ?string
    {
        if (empty($url) || str_starts_with($url, 'javascript:')) {
            return null;
        }

        try {
            $response = Http::timeout(8)
                ->withHeaders(self::SCRAPE_HEADERS)
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

            $effectiveUri = (string) ($response->effectiveUri() ?: $url);
            $contentType = strtolower((string) $response->header('Content-Type'));
            $disposition = strtolower((string) $response->header('Content-Disposition'));

            if (
                str_contains($contentType, 'application/pdf') ||
                (str_contains($disposition, '.pdf') && ! str_contains($contentType, 'text/html')) ||
                preg_match('/\.pdf($|\?)/i', $effectiveUri)
            ) {
                return $effectiveUri;
            }

            $html = $response->body();
            if (empty($html)) {
                return null;
            }

            if (str_starts_with($html, '%PDF-')) {
                return $effectiveUri;
            }

            // Primary: DomCrawler DOM extraction
            $extracted = $this->extractWithDomCrawler($html, $effectiveUri);
            if ($extracted && ! str_starts_with($extracted, 'javascript:')) {
                return $extracted;
            }

            // Secondary: Regex fallback
            return $this->extractDirectDocumentLinkFromHtml($html, $effectiveUri);
        } catch (\Throwable $e) {
            Log::info("Scrape note for academic URL [{$url}]: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Extract the most direct document link using Symfony DomCrawler.
     * Fixed: removed 'citation_full_html_url' and added exclusion of author guidelines / policy PDFs.
     */
    public function extractWithDomCrawler(string $html, string $baseUrl): ?string
    {
        try {
            $crawler = new Crawler($html, $baseUrl);

            // ── Strategy 1: Highwire / Google Scholar / Academic PDF meta tags ONLY ──
            $metaTags = [
                'citation_pdf_url',
                'bepress_citation_pdf_url',
                'eprints.document_url',
            ];

            foreach ($metaTags as $metaName) {
                try {
                    $metaNode = $crawler->filterXPath("//meta[@name='{$metaName}' or @name='".strtoupper($metaName)."']");
                    if ($metaNode->count() > 0) {
                        $content = trim((string) ($metaNode->attr('content') ?? ''));
                        if ($content !== '' && ! str_starts_with($content, 'javascript:')) {
                            $abs = $this->makeAbsoluteUrl($content, $baseUrl);
                            if (! $this->isExcludedPdfUrl($abs)) {
                                return $this->normalizeOjsUrl($abs);
                            }
                        }
                    }
                } catch (\Throwable) {
                    // continue
                }
            }

            // ── Strategy 2: Dublin Core meta tags with PDF, bitstream, or galley pattern ──
            try {
                $dcNodes = $crawler->filterXPath("//meta[@name='DC.Identifier' or @name='dc.identifier' or @name='DC.relation' or @name='dc.relation']");
                foreach ($dcNodes as $node) {
                    $content = trim((string) $node->getAttribute('content'));
                    if (preg_match('/\.pdf($|\?)|\/article\/(?:view|download)\/\d+\/\d+|\/bitstream\/handle\/|\/bitstreams\/[a-f0-9\-]+\/download/i', $content)) {
                        $abs = $this->makeAbsoluteUrl($content, $baseUrl);
                        if (! $this->isExcludedPdfUrl($abs)) {
                            return $this->normalizeOjsUrl($abs);
                        }
                    }
                }
            } catch (\Throwable) {
                // skip
            }

            // ── Strategy 3: JSON-LD structured data (ScholarlyArticle / DigitalDocument) ──
            try {
                $jsonLdNodes = $crawler->filter('script[type="application/ld+json"]');
                foreach ($jsonLdNodes as $node) {
                    $data = json_decode($node->textContent, true);
                    if (! is_array($data)) {
                        continue;
                    }

                    $itemsToCheck = isset($data['@graph']) && is_array($data['@graph']) ? $data['@graph'] : [$data];
                    foreach ($itemsToCheck as $d) {
                        if (! is_array($d)) {
                            continue;
                        }

                        $pdfUrl = $d['contentUrl'] ?? ($d['downloadUrl'] ?? null);
                        if ($pdfUrl && preg_match('/\.pdf($|\?)/i', (string) $pdfUrl)) {
                            $abs = $this->makeAbsoluteUrl((string) $pdfUrl, $baseUrl);
                            if (! $this->isExcludedPdfUrl($abs)) {
                                return $abs;
                            }
                        }

                        $distributions = array_merge(
                            (array) ($d['encoding'] ?? []),
                            (array) ($d['distribution'] ?? []),
                            (array) ($d['hasPart'] ?? [])
                        );
                        foreach ($distributions as $enc) {
                            $u = is_array($enc) ? ($enc['contentUrl'] ?? ($enc['url'] ?? ($enc['downloadUrl'] ?? ''))) : (string) $enc;
                            if ($u && preg_match('/\.pdf($|\?)/i', $u)) {
                                $abs = $this->makeAbsoluteUrl($u, $baseUrl);
                                if (! $this->isExcludedPdfUrl($abs)) {
                                    return $abs;
                                }
                            }
                        }
                    }
                }
            } catch (\Throwable) {
                // skip
            }

            // ── Strategy 4: OJS Galley links (specific classes) ──
            try {
                $ojsPdfLinks = $crawler->filter('a.obj_galley_link.pdf, a.obj_galley_link[title*="PDF"], a.galley-link.pdf, a.obj_galley_link, a.galley_link');
                foreach ($ojsPdfLinks as $linkNode) {
                    $href = trim($linkNode->getAttribute('href') ?? '');
                    if ($href !== '' && ! str_starts_with($href, 'javascript:') && $href !== '#') {
                        return $this->normalizeOjsUrl($this->makeAbsoluteUrl($href, $baseUrl));
                    }
                }
            } catch (\Throwable) {
                // skip
            }

            // ── Strategy 5: Any anchor matching OJS galley route /article/(view|download|viewFile)/ID/GALLEY ──
            try {
                $allAnchors = $crawler->filter('a[href]');
                foreach ($allAnchors as $a) {
                    $href = (string) $a->getAttribute('href');
                    if (preg_match('/\/article\/(?:view|download|viewFile)\/\d+\/\d+/i', $href)) {
                        return $this->normalizeOjsUrl($this->makeAbsoluteUrl($href, $baseUrl));
                    }
                }
            } catch (\Throwable) {
                // skip
            }

            // ── Strategy 6: DSpace 5/6/7/8 & Institutional Repository Bitstream PDFs ──
            try {
                $bitstreamLinks = $crawler->filter('a[href*="/bitstream/"][href*=".pdf"], a[href*="/bitstreams/"][href*="/download"], a.view-open[href*="/bitstream/"]');
                foreach ($bitstreamLinks as $bNode) {
                    $href = trim($bNode->getAttribute('href') ?? '');
                    if ($href !== '' && ! preg_match('/license\.txt|\.(?:jpg|png|txt)$/i', $href)) {
                        return $this->makeAbsoluteUrl($href, $baseUrl);
                    }
                }
            } catch (\Throwable) {
                // skip
            }

            // ── Strategy 7: SciELO & Redalyc & Dialnet specific anchors ──
            try {
                $repoLinks = $crawler->filter('a[href*="scielo.php"][href*="sci_pdf"], a[href*="/pdf/"][href$=".pdf"], a[href*="format=pdf"], a[href*="/descarga/articulo/"][href$=".pdf"], a[href*="/descarga/tesis/"][href$=".pdf"]');
                foreach ($repoLinks as $rNode) {
                    $href = trim($rNode->getAttribute('href') ?? '');
                    if ($href !== '') {
                        $abs = $this->makeAbsoluteUrl($href, $baseUrl);
                        if (! $this->isExcludedPdfUrl($abs)) {
                            return $abs;
                        }
                    }
                }
            } catch (\Throwable) {
                // skip
            }

            // ── Strategy 8: Embedded PDF viewers (iframe, embed, object) ──
            try {
                $embedNode = $crawler->filter('iframe[src*=".pdf"], embed[src*=".pdf"], object[data*=".pdf"], iframe[src*="/article/view/"]');
                if ($embedNode->count() > 0) {
                    $src = $embedNode->first()->attr('src') ?? $embedNode->first()->attr('data');
                    if ($src) {
                        return $this->normalizeOjsUrl($this->makeAbsoluteUrl($src, $baseUrl));
                    }
                }
            } catch (\Throwable) {
                // skip
            }

            // ── Strategy 9: Anchors ending in .pdf (excluding author guidelines & templates) ──
            try {
                $pdfAnchors = $crawler->filter('a[href$=".pdf"], a[href*=".pdf?"], a[href*=".pdf#"]');
                foreach ($pdfAnchors as $a) {
                    $href = trim($a->getAttribute('href') ?? '');
                    $text = trim($a->textContent ?? '');
                    if ($href !== '' && ! str_starts_with($href, 'javascript:')) {
                        $abs = $this->makeAbsoluteUrl($href, $baseUrl);
                        if (! $this->isExcludedPdfUrl($abs) && ! $this->isExcludedPdfUrl($text)) {
                            return $abs;
                        }
                    }
                }
            } catch (\Throwable) {
                // skip
            }

            // ── Strategy 10: Semantic anchor text scanning ──
            try {
                $pdfKeywords = [
                    'pdf', 'texto completo', 'full text', 'full-text', 'descargar artículo',
                    'descargar libro', 'leer artículo', 'visualizar pdf', 'bajar pdf', 'documento completo',
                    'download pdf', 'ver pdf', 'acceder al pdf', 'leer en pdf',
                ];

                $anchors = $crawler->filter('a[href]');
                foreach ($anchors as $a) {
                    $href = trim((string) $a->getAttribute('href'));
                    if ($href === '' || str_starts_with($href, 'javascript:') || str_starts_with($href, 'mailto:') || str_starts_with($href, '#')) {
                        continue;
                    }

                    $text = mb_strtolower(trim($a->textContent));
                    $classes = mb_strtolower((string) $a->getAttribute('class'));
                    $title = mb_strtolower((string) $a->getAttribute('title'));

                    if ($this->isExcludedPdfUrl($href) || $this->isExcludedPdfUrl($text)) {
                        continue;
                    }

                    foreach ($pdfKeywords as $kw) {
                        if (
                            str_contains($text, $kw) ||
                            str_contains($classes, 'pdf') || str_contains($classes, 'btn-pdf') || str_contains($classes, 'download-pdf') ||
                            str_contains($title, 'pdf')
                        ) {
                            return $this->normalizeOjsUrl($this->makeAbsoluteUrl($href, $baseUrl));
                        }
                    }
                }
            } catch (\Throwable) {
                // skip
            }
        } catch (\Throwable $e) {
            Log::info("DomCrawler extraction note [{$baseUrl}]: ".$e->getMessage());
        }

        return null;
    }

    /**
     * Extract direct document / PDF link from HTML content using standard scholarly patterns (regex fallback).
     */
    public function extractDirectDocumentLinkFromHtml(string $html, string $baseUrl): ?string
    {
        $crawlerResult = $this->extractWithDomCrawler($html, $baseUrl);
        if ($crawlerResult && ! str_starts_with($crawlerResult, 'javascript:')) {
            return $crawlerResult;
        }

        // 1. Highwire Press & Academic Meta Tags (citation_pdf_url, bepress, eprints)
        if (preg_match('/<meta\s+[^>]*(?:name=["\'](?:citation_pdf_url|bepress_citation_pdf_url|eprints\.document_url)["\'][^>]*content=["\']([^"\']+)["\']|content=["\']([^"\']+)["\'][^>]*name=["\'](?:citation_pdf_url|bepress_citation_pdf_url|eprints\.document_url)["\'])/i', $html, $m)) {
            $link = trim($m[1] ?: $m[2]);
            if (! empty($link) && ! str_starts_with($link, 'javascript:')) {
                $abs = $this->makeAbsoluteUrl($link, $baseUrl);

                return $this->normalizeOjsUrl($abs);
            }
        }

        // 2. OJS Galley routes
        if (preg_match('/href=["\']([^"\']+\/article\/(?:view|download|viewFile)\/\d+\/\d+[^"\']*)["\']/i', $html, $m)) {
            $abs = $this->makeAbsoluteUrl($m[1], $baseUrl);

            return $this->normalizeOjsUrl($abs);
        }

        // 3. DSpace Institutional Repositories Bitstream links
        if (preg_match('/href=["\']([^"\']+(?:\/bitstream\/handle\/[^"\']+\.pdf|\/bitstreams\/[a-f0-9\-]+\/download)[^"\']*)["\']/i', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        // 4. SciELO & Dialnet direct PDF links
        if (preg_match('/href=["\']([^"\']*scielo\.php\?[^"\']*script=sci_pdf[^"\']*)["\']/i', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        if (preg_match('/href=["\']([^"\']*\/descarga\/(?:articulo|tesis)\/\d+\.pdf)["\']/i', $html, $m)) {
            return $this->makeAbsoluteUrl($m[1], $baseUrl);
        }

        // 5. Anchors ending in .pdf (filtered against excluded patterns)
        if (preg_match_all('/<a\s+[^>]*href=["\']([^"\']+\.pdf(?:\?[^"\']*)?)["\'][^>]*>(.*?)<\/a>/is', $html, $pdfMatches, PREG_SET_ORDER)) {
            foreach ($pdfMatches as $pm) {
                $abs = $this->makeAbsoluteUrl($pm[1], $baseUrl);
                if (! $this->isExcludedPdfUrl($abs) && ! $this->isExcludedPdfUrl(strip_tags($pm[2]))) {
                    return $abs;
                }
            }
        }

        return null;
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

                    if (preg_match('#<dc:identifier>[^<]*/article/(?:view|download)/'.$articleId.'/(\d+)[^<]*</dc:identifier>#i', $xml, $gMatch)) {
                        return $journalBase.'/article/view/'.$articleId.'/'.$gMatch[1];
                    }

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

        return null;
    }

    // ══════════════════════════════════════════════════════════════
    // STRICT PDF VERIFICATION & URL UTILITIES
    // ══════════════════════════════════════════════════════════════

    /**
     * Check if a URL or anchor text belongs to an excluded non-article PDF (author guidelines, templates, etc.).
     */
    private function isExcludedPdfUrl(string $textOrUrl): bool
    {
        $lower = mb_strtolower($textOrUrl);
        foreach (self::EXCLUDED_PDF_PATTERNS as $pattern) {
            if (str_contains($lower, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether a URL points to a direct PDF / document stream rather than a full HTML web page.
     */
    public function isDirectDocumentOrPdfUrl(string $url, bool $allowNetworkProbe = false): bool
    {
        $url = trim($url);
        if ($url === '' || str_starts_with($url, 'javascript:') || $this->isExcludedPdfUrl($url)) {
            return false;
        }

        // Known direct PDF / Document viewer patterns
        if (preg_match('/\.pdf($|\?|#)/i', $url)) {
            return true;
        }
        if (preg_match('/\/article\/(?:view|download|viewFile)\/\d+\/\d+/i', $url)) {
            return true;
        }
        if (preg_match('/\/bitstream\/handle\/|\/bitstreams\/[a-f0-9\-]+\/download|\/jspui\/bitstream\/|\/xmlui\/bitstream\//i', $url)) {
            return true;
        }
        if (preg_match('/script=sci_pdf|[?&]format=pdf|\/descarga\/(?:articulo|tesis)\/\d+\.pdf|ncbi\.nlm\.nih\.gov\/pmc\/articles\/PMC\d+\/pdf/i', $url)) {
            return true;
        }

        // Reject known HTML landing page patterns (e.g. /article/view/350 without galley ID, /handle/123/456 without bitstream)
        if (preg_match('~/article/view/\d+/?$~i', $url) || preg_match('~/handle/\d+/\d+/?$~i', $url) || str_contains($url, 'script=sci_arttext')) {
            return false;
        }

        if (! $allowNetworkProbe) {
            return false;
        }

        // Lightweight Range probe (first 512 bytes) to verify if the URL streams a binary %PDF- file
        try {
            $response = Http::timeout(5)
                ->withHeaders(array_merge(self::SCRAPE_HEADERS, ['Range' => 'bytes=0-512']))
                ->withOptions(['allow_redirects' => ['max' => 5]])
                ->get($url);

            if ($response->successful() || $response->status() === 206) {
                $ct = strtolower((string) $response->header('Content-Type'));
                $disp = strtolower((string) $response->header('Content-Disposition'));
                $body = $response->body();

                return str_contains($ct, 'application/pdf')
                    || (str_contains($disp, '.pdf') && ! str_contains($ct, 'text/html'))
                    || str_starts_with($body, '%PDF-');
            }
        } catch (\Throwable) {
            return false;
        }

        return false;
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

        $basePath = $parsed['path'] ?? '/';
        $baseDir = rtrim(dirname($basePath), '/');
        $cleanRelative = (string) preg_replace('/^\.\//', '', $relativeUrl);

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

        $oldIsDirect = $this->isDirectDocumentOrPdfUrl($oldUrl, false);
        $newIsDirect = $this->isDirectDocumentOrPdfUrl($newUrl, false);

        if ($newIsDirect && ! $oldIsDirect) {
            return true;
        }

        if (
            preg_match('/\/article\/view\/\d+\/\d+/i', $newUrl) &&
            preg_match('/\/article\/download\/\d+\/\d+/i', $oldUrl)
        ) {
            return true;
        }

        return false;
    }

    // ══════════════════════════════════════════════════════════════
    // HELPERS & PERSISTENCE
    // ══════════════════════════════════════════════════════════════

    /**
     * Map scholarly work type to the Library categories.
     */
    private function mapCategory(string $academicType, string $fallback): string
    {
        $type = mb_strtolower($academicType);

        if (str_contains($type, 'book') || str_contains($type, 'monograph') || str_contains($type, 'chapter')) {
            return 'Libro';
        }

        if (str_contains($type, 'dissertation') || str_contains($type, 'thesis')) {
            return 'Tesis';
        }

        if (str_contains($type, 'journal') || str_contains($type, 'serial') || str_contains($type, 'periodical')) {
            return 'Revista';
        }

        if (str_contains($type, 'article') || str_contains($type, 'paper') || str_contains($type, 'journalarticle') || str_contains($type, 'journal_article')) {
            return 'Paper';
        }

        if (str_contains($type, 'report') || str_contains($type, 'technical') || str_contains($type, 'document')) {
            return 'Documento';
        }

        return $fallback ?: 'Paper';
    }

    /**
     * Reconstruct human-readable abstract from OpenAlex inverted index.
     *
     * @param  array<string, array<int>>|null  $invertedIndex
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
     * Save or update an academic item with deduplication and direct document resolution.
     *
     * @param  array<string, mixed>  $item
     * @return array{status: 'created'|'updated'|'skipped', book: ?Book}
     */
    public function storeAcademicItem(array $item): array
    {
        if (empty($item['title']) || empty($item['external_url'])) {
            return ['status' => 'skipped', 'book' => null];
        }

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
     * Upsert academic publication and upgrade to direct document URL if available.
     *
     * @param  array<string, mixed>  $item
     * @return array{0: 'saved'|'updated'|'skipped', 1: Book}
     */
    private function upsertAcademicResource(array $item): array
    {
        if (empty($item['title']) || empty($item['external_url'])) {
            return ['skipped', new Book];
        }

        if (str_starts_with($item['external_url'], 'javascript:') || $item['external_url'] === '#') {
            return ['skipped', new Book];
        }

        $cleanTitle = trim((string) preg_replace('/\s+/', ' ', $item['title']));
        $item['title'] = Str::limit($cleanTitle, 250);
        $item['external_url'] = Str::limit($item['external_url'], 495);

        $existing = Book::where('external_url', $item['external_url'])->first();

        if (! $existing) {
            $existing = Book::where('title', $item['title'])->first();
        }

        if (! $existing && preg_match('#(/article/(?:view|download)/\d+)#i', $item['external_url'], $baseOjsMatch)) {
            $baseOjs = $baseOjsMatch[1];
            $existing = Book::where('external_url', 'like', '%'.$baseOjs)
                ->orWhere('external_url', 'like', '%'.$baseOjs.'/%')
                ->first();
        }

        if ($existing) {
            $updates = [
                'author' => $existing->author ?: ($item['author'] ?? null),
                'description' => $existing->description ?: ($item['description'] ?? null),
                'publisher' => $existing->publisher ?: ($item['publisher'] ?? null),
                'publication_year' => $existing->publication_year ?: ($item['publication_year'] ?? null),
                'isbn' => $existing->isbn ?: ($item['isbn'] ?? null),
            ];

            if ($this->isMoreDirectDocumentUrl($item['external_url'], $existing->external_url)) {
                $updates['external_url'] = $item['external_url'];
            }

            $existing->update($updates);

            return ['updated', $existing];
        }

        $baseSlug = Str::slug(Str::limit($item['title'], 180));
        $slug = $baseSlug;
        $counter = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        $bookData = [
            'title' => $item['title'],
            'slug' => $slug,
            'author' => Str::limit($item['author'] ?? 'Varios Autores', 250),
            'study_program_id' => $item['study_program_id'] ?? null,
            'category' => $item['category'] ?? 'Paper',
            'description' => Str::limit($item['description'] ?? null, 950),
            'publisher' => Str::limit($item['publisher'] ?? 'Repositorio Científico Abierto', 145),
            'publication_year' => ! empty($item['publication_year']) ? (int) $item['publication_year'] : null,
            'edition' => '1ª Edición Digital',
            'pages' => $item['pages'] ?? null,
            'isbn' => isset($item['isbn']) ? Str::limit($item['isbn'], 45) : null,
            'language' => $item['language'] ?? 'Español',
            'external_url' => $item['external_url'],
            'rating' => 5.0,
            'is_active' => true,
            'created_by' => $item['created_by'] ?? auth()->id(),
        ];

        $book = Book::create($bookData);

        return ['saved', $book];
    }

    /**
     * Format summary for client-side feedback and table refresh.
     *
     * @return array<string, mixed>
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
