<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class SitemapController extends Controller
{
    /**
     * Base URL for the official production site.
     */
    public const PRODUCTION_BASE_URL = 'https://iestpfranciscovigocaballero.edu.pe';

    /**
     * Return the sitemap.xml response.
     */
    public function index(): Response
    {
        $staticPath = public_path('sitemap.xml');

        if (File::exists($staticPath)) {
            $content = File::get($staticPath);
        } else {
            $content = $this->buildSitemapXml();
        }

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Build standard XML sitemap for search engines.
     */
    public function buildSitemapXml(?string $baseUrl = null): string
    {
        $baseUrl = rtrim($baseUrl ?: self::PRODUCTION_BASE_URL, '/');
        $today = now()->format('Y-m-d');

        $staticPages = [
            // Inicio
            ['path' => '/', 'changefreq' => 'daily', 'priority' => '1.0'],

            // Admisión y Matrícula
            ['path' => '/admision-y-matricula/examen-de-admision', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['path' => '/admision-y-matricula/cepre-fvc', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['path' => '/admision-y-matricula/matriculas', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/admision-y-matriculas/becas-y-creditos', 'changefreq' => 'monthly', 'priority' => '0.8'],

            // Programas de Estudio
            ['path' => '/programas-de-estudios', 'changefreq' => 'weekly', 'priority' => '0.9'],

            // Servicios
            ['path' => '/servicios/bolsa-de-trabajo', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['path' => '/servicios/enlaces-institucionales', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/validar-certificado', 'changefreq' => 'monthly', 'priority' => '0.7'],

            // Transparencia
            ['path' => '/transparencia/documentos-de-gestion', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/transparencia/estadisticas', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/transparencia/inversion-y-gestion', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/transparencia/licenciamiento', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/transparencia/libro-de-reclamaciones', 'changefreq' => 'monthly', 'priority' => '0.6'],

            // Trámites
            ['path' => '/tramites/mesa-de-partes', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/tramites/tupa', 'changefreq' => 'monthly', 'priority' => '0.7'],

            // Nosotros / Institucional
            ['path' => '/nosotros/quienes-somos', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/nosotros/historia', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/nosotros/organigrama-institucional', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['path' => '/nosotros/plana-jerarquica', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/nosotros/plana-de-docentes', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/nosotros/plana-administrativa', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['path' => '/nosotros/consejo-de-estudiantes', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['path' => '/nosotros/locales', 'changefreq' => 'monthly', 'priority' => '0.6'],
        ];

        // Dynamic study programs
        $programSlugs = $this->getActiveProgramSlugs();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'."\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9'."\n";
        $xml .= '        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n\n";

        foreach ($staticPages as $page) {
            $loc = $page['path'] === '/' ? $baseUrl.'/' : $baseUrl.$page['path'];
            $xml .= "    <url>\n";
            $xml .= "        <loc>{$loc}</loc>\n";
            $xml .= "        <lastmod>{$today}</lastmod>\n";
            $xml .= "        <changefreq>{$page['changefreq']}</changefreq>\n";
            $xml .= "        <priority>{$page['priority']}</priority>\n";
            $xml .= "    </url>\n";

            // If we just wrote /programas-de-estudios, inject the program detail pages
            if ($page['path'] === '/programas-de-estudios') {
                foreach ($programSlugs as $slug) {
                    $programUrl = "{$baseUrl}/programas-de-estudios/{$slug}";
                    $xml .= "    <url>\n";
                    $xml .= "        <loc>{$programUrl}</loc>\n";
                    $xml .= "        <lastmod>{$today}</lastmod>\n";
                    $xml .= "        <changefreq>monthly</changefreq>\n";
                    $xml .= "        <priority>0.8</priority>\n";
                    $xml .= "    </url>\n";
                }
            }
        }

        $xml .= '</urlset>'."\n";

        return $xml;
    }

    /**
     * Get active study programs slugs with clean fallback.
     *
     * @return array<int, string>
     */
    public function getActiveProgramSlugs(): array
    {
        try {
            $slugs = StudyProgram::where('is_active', true)
                ->orderBy('order')
                ->pluck('slug')
                ->filter()
                ->toArray();

            if (! empty($slugs)) {
                return $slugs;
            }
        } catch (\Throwable) {
            // Database might be unavailable during certain builds/environments
        }

        return [
            'produccion-agropecuaria',
            'enfermeria-tecnica',
            'administracion-de-redes-y-comunicaciones',
            'asistencia-administrativa',
            'manejo-forestal',
        ];
    }
}
