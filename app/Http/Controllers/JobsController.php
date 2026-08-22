<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobValidate;
use App\Models\JobOffer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class JobsController extends Controller
{
    // ─── Keyword groups mapped to the 5 study programs ─────────────────────
    private const SEARCH_QUERIES = [
        'Redes y Comunicaciones' => [
            'tecnico redes comunicaciones',
            'soporte tecnico redes',
            'administrador redes',
            'tecnico informatico',
        ],
        'Asistencia Administrativa' => [
            'asistente administrativo',
            'asistencia administrativa tecnica',
            'auxiliar administrativo',
            'secretaria asistente',
        ],
        'Enfermería Técnica' => [
            'tecnico enfermeria',
            'auxiliar enfermeria',
            'enfermero tecnico',
        ],
        'Manejo Forestal' => [
            'tecnico forestal',
            'manejo forestal',
            'ingeniero forestal tecnico',
        ],
        'Producción Agropecuaria' => [
            'tecnico agropecuario',
            'produccion agricola tecnico',
            'tecnico pecuario',
            'agricultor tecnico',
        ],
    ];

    // Terms that indicate the job is NOT in Peru (to filter out)
    private const EXCLUDE_COUNTRIES = [
        'colombia', 'argentina', 'chile', 'mexico', 'españa', 'spain',
        'ecuador', 'bolivia', 'venezuela', 'paraguay', 'uruguay',
        'estados unidos', 'united states', 'usa', 'canada',
        'brasil', 'brazil',
    ];

    // Valid Peruvian department/city identifiers for location validation
    private const PERU_LOCATIONS = [
        'lima', 'arequipa', 'cusco', 'trujillo', 'chiclayo', 'piura',
        'iquitos', 'cajamarca', 'chimbote', 'huancayo', 'ica',
        'tacna', 'pucallpa', 'sullana', 'juliaca', 'ayacucho',
        'puno', 'loreto', 'moquegua', 'tumbes', 'madre de dios',
        'san martin', 'amazonas', 'ancash', 'apurimac', 'huanuco',
        'huancavelica', 'junin', 'la libertad', 'lambayeque', 'paseo',
        'piura', 'ucayali', 'callao', 'tocache', 'uchiza', 'tarapoto',
        'tingo maria', 'moyobamba', 'peru', 'perú',
    ];

    // ─── Source brand names used for upsert matching ────────────────────────
    private const SOURCE_COMPUTRABAJO = 'Computrabajo Perú';
    private const SOURCE_BUMERAN      = 'Bumeran Perú';

    /**
     * List all job offers with search, filters, and pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $source = $request->input('source');

        $query = JobOffer::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            if ($status === 'active' || $status === '1') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive' || $status === '0') {
                $query->where('is_active', false);
            }
        }

        if (!empty($source)) {
            $query->where('source', 'like', "%{$source}%");
        }

        // Statistics Summary
        $totalJobs    = JobOffer::count();
        $activeJobs   = JobOffer::where('is_active', true)->count();
        $inactiveJobs = JobOffer::where('is_active', false)->count();
        $internalJobs = JobOffer::where('source', 'like', '%Interna%')->count();

        // Paginated results
        $jobs = $query->orderBy('created_at', 'desc')
                      ->paginate(10)
                      ->withQueryString();

        return view('admin.jobs.index', compact(
            'jobs',
            'search',
            'status',
            'source',
            'totalJobs',
            'activeJobs',
            'inactiveJobs',
            'internalJobs'
        ));
    }

    /**
     * List internal calls only.
     */
    public function internalCalls(Request $request): View
    {
        $search = $request->input('search');

        $query = JobOffer::where('source', 'like', '%Interna%');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $totalJobs    = JobOffer::count();
        $activeJobs   = JobOffer::where('is_active', true)->count();
        $inactiveJobs = JobOffer::where('is_active', false)->count();
        $internalJobs = JobOffer::where('source', 'like', '%Interna%')->count();

        $jobs = $query->orderBy('created_at', 'desc')
                      ->paginate(10)
                      ->withQueryString();

        return view('admin.jobs.index', compact(
            'jobs',
            'search',
            'totalJobs',
            'activeJobs',
            'inactiveJobs',
            'internalJobs'
        ));
    }

    /**
     * Show form for creating a new job offer.
     */
    public function create(): View
    {
        return view('admin.jobs.create');
    }

    /**
     * Store a newly created job offer.
     */
    public function store(JobValidate $request): JsonResponse|RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;
            $validated['url']       = !empty($validated['url']) ? $validated['url'] : '#';
            $validated['source']    = !empty($validated['source']) ? $validated['source'] : 'Bolsa Institucional';

            $job = JobOffer::create($validated);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Oferta laboral creada con éxito.',
                    'redirect' => route('admin.works.index'),
                    'data'     => $job,
                ], 201);
            }

            return redirect()->route('admin.works.index')
                             ->with('success', 'Oferta laboral creada con éxito.');
        } catch (\Exception $e) {
            Log::error('Error creando oferta laboral: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la oferta: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al guardar la oferta laboral.');
        }
    }

    /**
     * Show form for editing an existing job offer.
     */
    public function edit(JobOffer $offer): View
    {
        return view('admin.jobs.edit', compact('offer'));
    }

    /**
     * Update an existing job offer.
     */
    public function update(JobValidate $request, JobOffer $offer): JsonResponse|RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;
            $validated['url']       = !empty($validated['url']) ? $validated['url'] : '#';
            $validated['source']    = !empty($validated['source']) ? $validated['source'] : 'Bolsa Institucional';

            $offer->update($validated);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Oferta laboral actualizada correctamente.',
                    'redirect' => route('admin.works.index'),
                    'data'     => $offer->fresh(),
                ], 200);
            }

            return redirect()->route('admin.works.index')
                             ->with('success', 'Oferta laboral actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error actualizando oferta laboral: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la oferta: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al actualizar la oferta laboral.');
        }
    }

    /**
     * Toggle active / inactive status.
     */
    public function toggleStatus(JobOffer $offer): JsonResponse|RedirectResponse
    {
        try {
            $offer->update(['is_active' => !$offer->is_active]);

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success'   => true,
                    'is_active' => $offer->is_active,
                    'message'   => 'Estado de la oferta actualizado correctamente.',
                ]);
            }

            return back()->with('success', 'Estado de la oferta actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error alternando estado de oferta: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cambiar el estado.',
                ], 500);
            }

            return back()->with('error', 'No se pudo cambiar el estado de la oferta.');
        }
    }

    /**
     * Delete job offer.
     */
    public function destroy(JobOffer $offer): JsonResponse|RedirectResponse
    {
        try {
            $offer->delete();

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'La oferta ha sido eliminada correctamente.',
                ], 200);
            }

            return redirect()->route('admin.works.index')
                             ->with('success', 'La oferta ha sido eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error eliminando oferta laboral: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el registro.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar la oferta laboral.');
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  AUTO-FETCH: Scrape Peruvian job portals and save to JobOffer model
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Main entry point called via AJAX from the admin index view.
     * Scrapes Computrabajo PE and Bumeran PE, saves results to DB.
     */
    public function fetchJobs(Request $request): JsonResponse
    {
        set_time_limit(120);

        $savedCount   = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $errors       = [];
        $log          = [];

        foreach (self::SEARCH_QUERIES as $program => $terms) {
            foreach ($terms as $term) {
                // ── Computrabajo Perú ──────────────────────────────────────
                try {
                    $results = $this->scrapeComputrabajo($term, $program);
                    foreach ($results as $item) {
                        [$action] = $this->upsertJobOffer($item);
                        $action === 'saved'   ? $savedCount++   :
                        ($action === 'updated' ? $updatedCount++ : $skippedCount++);
                    }
                    if (!empty($results)) {
                        $log[] = "Computrabajo [{$program}] «{$term}»: " . count($results) . " registros.";
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Computrabajo [{$term}]: {$e->getMessage()}";
                    Log::warning("fetchJobs Computrabajo error [{$term}]: " . $e->getMessage());
                }

                // ── Bumeran Perú ───────────────────────────────────────────
                try {
                    $results = $this->scrapeBumeran($term, $program);
                    foreach ($results as $item) {
                        [$action] = $this->upsertJobOffer($item);
                        $action === 'saved'   ? $savedCount++   :
                        ($action === 'updated' ? $updatedCount++ : $skippedCount++);
                    }
                    if (!empty($results)) {
                        $log[] = "Bumeran [{$program}] «{$term}»: " . count($results) . " registros.";
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Bumeran [{$term}]: {$e->getMessage()}";
                    Log::warning("fetchJobs Bumeran error [{$term}]: " . $e->getMessage());
                }

                // Small pause to be respectful to servers
                usleep(600000); // 0.6 s
            }
        }

        $total = $savedCount + $updatedCount;

        return response()->json([
            'success'       => true,
            'saved'         => $savedCount,
            'updated'       => $updatedCount,
            'skipped'       => $skippedCount,
            'total'         => $total,
            'log'           => $log,
            'errors'        => $errors,
            'message'       => $total > 0
                ? "Búsqueda completada: {$savedCount} nuevas y {$updatedCount} actualizadas ({$skippedCount} sin cambios)."
                : 'Búsqueda completada. No se encontraron nuevas ofertas en este momento.',
        ]);
    }

    // ─── Computrabajo.com.pe scraper ────────────────────────────────────────

    private function scrapeComputrabajo(string $term, string $program): array
    {
        $slug = $this->toUrlSlug($term);
        $url  = "https://www.computrabajo.com.pe/trabajo-de-{$slug}";

        $html = $this->httpGet($url);
        if (!$html) {
            return [];
        }

        $results = [];

        // 1. Try JSON-LD structured data first (most reliable)
        $jsonLdJobs = $this->extractJsonLdJobs($html);
        foreach ($jsonLdJobs as $job) {
            if ($this->isValidPeruJob($job['location'] ?? '', $job['title'] ?? '')) {
                $results[] = array_merge($job, ['source' => self::SOURCE_COMPUTRABAJO, 'program' => $program]);
            }
        }

        // 2. Fallback: parse HTML article cards
        if (empty($results)) {
            $crawler = new Crawler($html);
            $crawler->filter('article.box_offer, div.box_offer, div[data-testid="job-card"]')->each(function (Crawler $node) use (&$results, $program, $url) {
                try {
                    $title    = $this->crawlerText($node, 'h2 a, h3 a, .title_offer a, [data-testid="job-title"]');
                    $company  = $this->crawlerText($node, '.fs16, .company-name, [data-testid="company-name"], p.fs16');
                    $location = $this->crawlerText($node, '.fs13.fc_base2, .location, [data-testid="location"]');
                    $href     = $node->filter('h2 a, h3 a, .title_offer a')->count()
                        ? $node->filter('h2 a, h3 a, .title_offer a')->attr('href')
                        : null;
                    $jobUrl   = $href ? (str_starts_with($href, 'http') ? $href : 'https://www.computrabajo.com.pe' . $href) : null;

                    if ($title && $company && $jobUrl && $this->isValidPeruJob($location, $title)) {
                        $results[] = [
                            'title'       => trim($title),
                            'company'     => trim($company),
                            'location'    => $this->normalizeLocation($location),
                            'description' => $this->buildDescription($title, $company, $program, 'Computrabajo Perú'),
                            'url'         => $jobUrl,
                            'source'      => self::SOURCE_COMPUTRABAJO,
                            'program'     => $program,
                        ];
                    }
                } catch (\Throwable) {
                }
            });
        }

        return $results;
    }

    // ─── Bumeran.com.pe scraper ─────────────────────────────────────────────

    private function scrapeBumeran(string $term, string $program): array
    {
        $slug = rawurlencode($term);
        $url  = "https://www.bumeran.com.pe/empleos-busqueda-{$slug}.html?pais=peru";

        $html = $this->httpGet($url);
        if (!$html) {
            return [];
        }

        $results = [];

        // 1. Try JSON-LD first
        $jsonLdJobs = $this->extractJsonLdJobs($html);
        foreach ($jsonLdJobs as $job) {
            if ($this->isValidPeruJob($job['location'] ?? '', $job['title'] ?? '')) {
                $results[] = array_merge($job, ['source' => self::SOURCE_BUMERAN, 'program' => $program]);
            }
        }

        // 2. Fallback: parse HTML listing cards
        if (empty($results)) {
            $crawler = new Crawler($html);
            $crawler->filter('article, div.aviso, .styles__JobCard-sc, [class*="JobCard"]')->each(function (Crawler $node) use (&$results, $program) {
                try {
                    $title    = $this->crawlerText($node, 'h2, h3, [class*="title"], [class*="Title"]');
                    $company  = $this->crawlerText($node, '[class*="company"], [class*="Company"], [class*="empresa"]');
                    $location = $this->crawlerText($node, '[class*="location"], [class*="Location"], [class*="ubicacion"]');
                    $href     = $node->filter('a')->count() ? $node->filter('a')->first()->attr('href') : null;
                    $jobUrl   = $href ? (str_starts_with($href, 'http') ? $href : 'https://www.bumeran.com.pe' . $href) : null;

                    if ($title && strlen($title) > 3 && $jobUrl && $this->isValidPeruJob($location, $title)) {
                        $results[] = [
                            'title'       => trim($title),
                            'company'     => trim($company ?: 'Empresa Confidencial'),
                            'location'    => $this->normalizeLocation($location),
                            'description' => $this->buildDescription($title, $company ?: 'Empresa Confidencial', $program, 'Bumeran Perú'),
                            'url'         => $jobUrl,
                            'source'      => self::SOURCE_BUMERAN,
                            'program'     => $program,
                        ];
                    }
                } catch (\Throwable) {
                }
            });
        }

        return $results;
    }

    // ─── JSON-LD structured data extractor ──────────────────────────────────

    private function extractJsonLdJobs(string $html): array
    {
        $jobs = [];

        preg_match_all('/<script[^>]+type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches);

        foreach ($matches[1] as $raw) {
            try {
                $data = json_decode(trim($raw), true, 512, JSON_THROW_ON_ERROR);

                // Handle single job or array
                $items = isset($data['@type']) ? [$data] : ($data['@graph'] ?? []);

                foreach ($items as $item) {
                    if (($item['@type'] ?? '') !== 'JobPosting') {
                        continue;
                    }

                    $title    = $item['title'] ?? '';
                    $company  = $item['hiringOrganization']['name'] ?? ($item['hiringOrganization'] ?? '');
                    $location = $item['jobLocation']['address']['addressLocality'] ?? ($item['jobLocation']['address']['addressRegion'] ?? '');
                    $country  = strtolower($item['jobLocation']['address']['addressCountry'] ?? '');
                    $desc     = strip_tags($item['description'] ?? '');
                    $url      = $item['url'] ?? ($item['sameAs'] ?? '');

                    // Exclude if explicitly tagged as non-Peru country
                    if ($country && !in_array($country, ['pe', 'peru', 'perú', ''])) {
                        continue;
                    }

                    if (!$title || !$url) {
                        continue;
                    }

                    $jobs[] = [
                        'title'       => trim($title),
                        'company'     => is_string($company) ? trim($company) : 'Empresa Confidencial',
                        'location'    => $this->normalizeLocation($location),
                        'description' => $desc ? mb_substr(trim($desc), 0, 900) : '',
                        'url'         => $url,
                    ];
                }
            } catch (\Throwable) {
                // skip malformed JSON-LD
            }
        }

        return $jobs;
    }

    // ─── DB upsert ──────────────────────────────────────────────────────────

    /**
     * @return array{0: 'saved'|'updated'|'skipped'}
     */
    private function upsertJobOffer(array $item): array
    {
        if (empty($item['url']) || empty($item['title'])) {
            return ['skipped'];
        }

        $existing = JobOffer::where('url', $item['url'])->first();

        $payload = [
            'title'       => mb_substr($item['title'], 0, 255),
            'company'     => mb_substr($item['company'] ?? 'Empresa Confidencial', 0, 255),
            'location'    => mb_substr($item['location'] ?? 'Perú', 0, 255),
            'description' => !empty($item['description'])
                ? $item['description']
                : $this->buildDescription($item['title'], $item['company'] ?? '', $item['program'] ?? '', $item['source'] ?? ''),
            'url'         => $item['url'],
            'source'      => $item['source'] ?? 'Bolsa Institucional',
            'is_active'   => true,
        ];

        if ($existing) {
            // Only update dynamic fields, keep is_active as it was unless re-enabled
            $existing->update([
                'title'       => $payload['title'],
                'company'     => $payload['company'],
                'location'    => $payload['location'],
                'description' => $payload['description'],
                'source'      => $payload['source'],
                'is_active'   => $existing->is_active, // preserve admin's decision
            ]);
            return ['updated'];
        }

        JobOffer::create($payload);
        return ['saved'];
    }

    // ─── HTTP helper with browser-like headers ───────────────────────────────

    private function httpGet(string $url): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                    'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'es-PE,es;q=0.9,en;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Connection'      => 'keep-alive',
                    'Upgrade-Insecure-Requests' => '1',
                    'Referer'         => 'https://www.google.com/',
                    'Cache-Control'   => 'no-cache',
                ])
                ->get($url);

            if ($response->successful()) {
                return $response->body();
            }
        } catch (\Throwable $e) {
            Log::debug("httpGet failed [{$url}]: " . $e->getMessage());
        }

        return null;
    }

    // ─── Utility helpers ────────────────────────────────────────────────────

    private function toUrlSlug(string $text): string
    {
        $text = mb_strtolower($text);
        $text = str_replace(
            ['á','é','í','ó','ú','ñ','ü',' '],
            ['a','e','i','o','u','n','u','-'],
            $text
        );
        return preg_replace('/[^a-z0-9\-]/', '', $text);
    }

    private function isValidPeruJob(string $location, string $title): bool
    {
        $locationLower = mb_strtolower($location);
        $titleLower    = mb_strtolower($title);

        // Reject if explicitly a foreign country is in title or location
        foreach (self::EXCLUDE_COUNTRIES as $country) {
            if (str_contains($locationLower, $country) || str_contains($titleLower, $country)) {
                return false;
            }
        }

        // Accept if empty location (unknown = assume Peru since we're on .pe domain)
        if (empty(trim($location))) {
            return true;
        }

        // Accept if any known Peruvian location term found
        foreach (self::PERU_LOCATIONS as $peruvian) {
            if (str_contains($locationLower, $peruvian)) {
                return true;
            }
        }

        // Reject if location text looks like a foreign city we haven't listed
        // but is not Peru. Default accept to avoid over-filtering.
        return true;
    }

    private function normalizeLocation(string $location): string
    {
        $loc = trim($location);
        if (empty($loc)) {
            return 'Perú';
        }
        // Remove country suffix if already present (e.g. "Lima, Perú" → "Lima, Perú")
        $lower = mb_strtolower($loc);
        if (!str_contains($lower, 'perú') && !str_contains($lower, 'peru')) {
            $loc .= ', Perú';
        }
        return $loc;
    }

    private function crawlerText(Crawler $node, string $selector): string
    {
        try {
            $filtered = $node->filter($selector);
            return $filtered->count() ? trim($filtered->first()->text('')) : '';
        } catch (\Throwable) {
            return '';
        }
    }

    private function buildDescription(string $title, string $company, string $program, string $source): string
    {
        return "Puesto: {$title}\nEmpresa: {$company}\nÁrea académica relacionada: {$program}\nFuente: {$source}\n\nVisita el enlace de la oferta para conocer los detalles completos, requisitos y modalidad de postulación.";
    }
}