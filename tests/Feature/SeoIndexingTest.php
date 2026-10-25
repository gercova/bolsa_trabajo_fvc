<?php

namespace Tests\Feature;

use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SeoIndexingTest extends TestCase
{
    /**
     * Test robots.txt file exists, has correct directives and sitemap reference.
     */
    public function test_robots_txt_contains_required_directives_and_sitemap(): void
    {
        $robotsPath = public_path('robots.txt');

        $this->assertFileExists($robotsPath);

        $content = File::get($robotsPath);

        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Disallow: /admin-*', $content);
        $this->assertStringContainsString('Disallow: /login', $content);
        $this->assertStringContainsString('Sitemap: https://iestpfranciscovigocaballero.edu.pe/sitemap.xml', $content);
    }

    /**
     * Test robot.txt route redirects to /robots.txt.
     */
    public function test_robot_txt_redirects_to_robots_txt(): void
    {
        $response = $this->get('/robot.txt');

        $response->assertRedirect('/robots.txt');
    }

    /**
     * Test sitemap.xml route returns valid XML response with production base URL.
     */
    public function test_sitemap_xml_route_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('https://iestpfranciscovigocaballero.edu.pe/', false);
        $response->assertSee('https://iestpfranciscovigocaballero.edu.pe/programas-de-estudios', false);
        $response->assertSee('https://iestpfranciscovigocaballero.edu.pe/servicios/bolsa-de-trabajo', false);
    }

    /**
     * Test sitemap generator builds all official study program URLs.
     */
    public function test_sitemap_builder_includes_all_public_study_programs(): void
    {
        $controller = new SitemapController;
        $xml = $controller->buildSitemapXml('https://iestpfranciscovigocaballero.edu.pe');

        $this->assertStringContainsString('<loc>https://iestpfranciscovigocaballero.edu.pe/</loc>', $xml);
        $this->assertStringContainsString('<loc>https://iestpfranciscovigocaballero.edu.pe/programas-de-estudios/produccion-agropecuaria</loc>', $xml);
        $this->assertStringContainsString('<loc>https://iestpfranciscovigocaballero.edu.pe/programas-de-estudios/enfermeria-tecnica</loc>', $xml);
        $this->assertStringContainsString('<loc>https://iestpfranciscovigocaballero.edu.pe/programas-de-estudios/administracion-de-redes-y-comunicaciones</loc>', $xml);
        $this->assertStringContainsString('<loc>https://iestpfranciscovigocaballero.edu.pe/programas-de-estudios/asistencia-administrativa</loc>', $xml);
        $this->assertStringContainsString('<loc>https://iestpfranciscovigocaballero.edu.pe/programas-de-estudios/manejo-forestal</loc>', $xml);
    }

    /**
     * Test artisan command sitemap:generate updates the public/sitemap.xml file.
     */
    public function test_artisan_sitemap_generate_command(): void
    {
        $this->artisan('sitemap:generate')
            ->expectsOutputToContain('Sitemap generado con éxito')
            ->assertSuccessful();

        $this->assertFileExists(public_path('sitemap.xml'));
    }

    /**
     * Test homepage structured data contains 100% syntactically valid JSON-LD.
     */
    public function test_homepage_renders_valid_json_ld_structured_data(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<script type="application/ld+json">', false);

        $html = $response->getContent();
        preg_match_all('/<script type=["\']application\/ld\+json["\']>(.*?)<\/script>/s', $html, $matches);

        $this->assertNotEmpty($matches[1], 'At least one JSON-LD block must exist on the homepage.');

        foreach ($matches[1] as $idx => $scriptContent) {
            $decoded = json_decode(trim($scriptContent), true);
            $this->assertSame(
                JSON_ERROR_NONE,
                json_last_error(),
                "JSON-LD script #{$idx} contains syntax errors: ".json_last_error_msg()."\nPayload: ".$scriptContent
            );
            $this->assertIsArray($decoded);
            $this->assertArrayHasKey('@context', $decoded);
            $this->assertSame('https://schema.org', $decoded['@context']);
        }
    }

    /**
     * Test Alpine Collapse plugin is loaded before Alpine core script.
     */
    public function test_homepage_loads_alpine_collapse_plugin_before_alpine(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $html = $response->getContent();

        $collapsePos = strpos($html, 'cdn.alpine-collapse.min.js');
        $alpinePos = strpos($html, 'cdn.alpine.min.js');

        $this->assertNotFalse($collapsePos, 'Alpine Collapse script must be included in the HTML.');
        $this->assertNotFalse($alpinePos, 'Alpine core script must be included in the HTML.');
        $this->assertLessThan($alpinePos, $collapsePos, 'Alpine Collapse must be loaded before Alpine core.');
    }
}
