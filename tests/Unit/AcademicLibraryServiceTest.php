<?php

namespace Tests\Unit;

use App\Services\AcademicLibraryService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AcademicLibraryServiceTest extends TestCase
{
    protected AcademicLibraryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AcademicLibraryService;
    }

    public function test_normalize_ojs_url_converts_download_to_view(): void
    {
        $downloadUrl = 'https://revistas.ufps.edu.co/index.php/respuestas/article/download/350/394';
        $normalized = $this->service->normalizeOjsUrl($downloadUrl);

        $this->assertEquals('https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394', $normalized);

        $downloadWithFileId = 'https://revistas.ufps.edu.co/index.php/respuestas/article/download/350/394/1234';
        $normalizedWithFileId = $this->service->normalizeOjsUrl($downloadWithFileId);

        $this->assertEquals('https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394', $normalizedWithFileId);

        $alreadyViewUrl = 'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394';
        $this->assertEquals($alreadyViewUrl, $this->service->normalizeOjsUrl($alreadyViewUrl));
    }

    public function test_extracts_and_normalizes_ojs_citation_pdf_url_meta_tag(): void
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta name="citation_title" content="Redes Ópticas Elásticas">
    <meta name="citation_pdf_url" content="https://revistas.ufps.edu.co/index.php/respuestas/article/download/350/394">
</head>
<body>
    <p>Contenido del artículo</p>
</body>
</html>
HTML;

        $baseUrl = 'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350';
        $extracted = $this->service->extractDirectDocumentLinkFromHtml($html, $baseUrl);

        $this->assertEquals('https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394', $extracted);
    }

    public function test_extracts_ojs_galley_link_from_anchor_tags(): void
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<body>
    <div class="item">
        <a class="obj_galley_link pdf" href="/index.php/respuestas/article/view/350/394">
            <span class="sr-only">Descargar</span> PDF
        </a>
    </div>
</body>
</html>
HTML;

        $baseUrl = 'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350';
        $extracted = $this->service->extractDirectDocumentLinkFromHtml($html, $baseUrl);

        $this->assertEquals('https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394', $extracted);
    }

    public function test_extracts_and_normalizes_ojs_download_anchor_to_view_url(): void
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<body>
    <div class="galley">
        <a class="obj_galley_link file" href="/index.php/respuestas/article/download/350/394">Descargar PDF</a>
    </div>
</body>
</html>
HTML;

        $baseUrl = 'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350';
        $extracted = $this->service->extractDirectDocumentLinkFromHtml($html, $baseUrl);

        $this->assertEquals('https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394', $extracted);
    }

    public function test_extracts_semantic_pdf_text_anchors(): void
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<body>
    <div class="actions">
        <a href="/articulos/documento-123.pdf" class="btn btn-primary">
            <i class="icon-download"></i> Texto Completo (PDF)
        </a>
    </div>
</body>
</html>
HTML;

        $baseUrl = 'https://revistas.universidad.edu.pe/revista/article/view/123';
        $extracted = $this->service->extractDirectDocumentLinkFromHtml($html, $baseUrl);

        $this->assertEquals('https://revistas.universidad.edu.pe/articulos/documento-123.pdf', $extracted);
    }

    public function test_extracts_scielo_pdf_links(): void
    {
        $html = <<<'HTML'
<html>
<body>
    <a href="scielo.php?script=sci_pdf&pid=S1132-12962024000100005&lng=es&nrm=iso&tlng=es">PDF Español</a>
</body>
</html>
HTML;

        $baseUrl = 'https://scielo.isciii.es/scielo.php?script=sci_arttext&pid=S1132-12962024000100005';
        $extracted = $this->service->extractDirectDocumentLinkFromHtml($html, $baseUrl);

        $this->assertStringContainsString('script=sci_pdf', $extracted);
        $this->assertStringContainsString('https://scielo.isciii.es/', $extracted);
    }

    public function test_resolve_direct_document_link_with_dialnet(): void
    {
        $landing = 'https://dialnet.unirioja.es/servlet/articulo?codigo=5364584';
        $resolved = $this->service->resolveDirectDocumentLink($landing);

        $this->assertEquals('https://dialnet.unirioja.es/descarga/articulo/5364584.pdf', $resolved);
    }

    public function test_make_absolute_url_handles_relative_and_root_paths(): void
    {
        $base = 'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350';

        $this->assertEquals(
            'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394',
            $this->service->makeAbsoluteUrl('/index.php/respuestas/article/view/350/394', $base)
        );

        $this->assertEquals(
            'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394',
            $this->service->makeAbsoluteUrl('350/394', $base)
        );

        $this->assertEquals(
            'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394',
            $this->service->makeAbsoluteUrl('./350/394', $base)
        );
    }

    public function test_is_more_direct_document_url_comparison(): void
    {
        $landingUrl = 'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350';
        $galleyViewUrl = 'https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394';
        $galleyDownloadUrl = 'https://revistas.ufps.edu.co/index.php/respuestas/article/download/350/394';
        $pdfUrl = 'https://example.com/document.pdf';

        // Galley view is more direct than landing page
        $this->assertTrue($this->service->isMoreDirectDocumentUrl($galleyViewUrl, $landingUrl));

        // PDF is more direct than landing page
        $this->assertTrue($this->service->isMoreDirectDocumentUrl($pdfUrl, $landingUrl));

        // Galley view is preferred over galley download for iframe reader
        $this->assertTrue($this->service->isMoreDirectDocumentUrl($galleyViewUrl, $galleyDownloadUrl));

        // Same URL is not more direct
        $this->assertFalse($this->service->isMoreDirectDocumentUrl($galleyViewUrl, $galleyViewUrl));
    }

    public function test_parses_google_scholar_html_and_extracts_galley(): void
    {
        $html = <<<'HTML'
<div class="gs_r gs_or gs_scl">
  <div class="gs_ggs gs_fl">
    <div class="gs_ggsd">
      <div class="gs_or_ggsm">
        <a href="https://revistas.ufps.edu.co/index.php/respuestas/article/download/350/394">
          <span class="gs_ctg2">[PDF]</span> ufps.edu.co
        </a>
      </div>
    </div>
  </div>
  <div class="gs_ri">
    <h3 class="gs_rt">
      <a href="https://revistas.ufps.edu.co/index.php/respuestas/article/view/350">Redes Ópticas Elásticas de Nueva Generación</a>
    </h3>
    <div class="gs_a">J Granada, A Cárdenas - Respuestas, 2024 - revistas.ufps.edu.co</div>
    <div class="gs_rs">Investigación sobre comunicaciones por fibra óptica y asignación dinámica de espectro.</div>
  </div>
</div>
HTML;

        $config = ['default_category' => 'Paper'];
        $results = $this->service->parseGoogleScholarHtml($html, $config, 3, 'Paper', 5);

        $this->assertCount(1, $results);
        $this->assertEquals('Redes Ópticas Elásticas de Nueva Generación', $results[0]['title']);
        // Must be normalized to view/350/394
        $this->assertEquals('https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394', $results[0]['external_url']);
    }

    public function test_resolve_ojs_galley_url_via_oai_pmh_and_crossref(): void
    {
        $oaiXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/">
  <GetRecord>
    <record>
      <metadata>
        <oai_dc:dc xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/" xmlns:dc="http://purl.org/dc/elements/1.1/">
          <dc:title>Redes Ópticas</dc:title>
          <dc:identifier>10.22463/0122820X.350</dc:identifier>
        </oai_dc:dc>
      </metadata>
    </record>
  </GetRecord>
</OAI-PMH>
XML;

        $crossrefJson = [
            'status' => 'ok',
            'message' => [
                'link' => [
                    [
                        'URL' => 'https://revistas.ufps.edu.co/index.php/respuestas/article/download/350/394',
                        'content-type' => 'application/pdf',
                    ],
                ],
            ],
        ];

        Http::fake([
            'https://revistas.ufps.edu.co/index.php/respuestas/oai*' => Http::response($oaiXml, 200),
            'https://api.crossref.org/works/*' => Http::response($crossrefJson, 200),
        ]);

        $resolved = $this->service->resolveOjsGalleyUrl('https://revistas.ufps.edu.co/index.php/respuestas/article/view/350');

        $this->assertEquals('https://revistas.ufps.edu.co/index.php/respuestas/article/view/350/394', $resolved);
    }

    public function test_parse_google_scholar_html_ignores_javascript_collapsible_and_extracts_real_pdf(): void
    {
        $html = <<<'HTML'
<div class="gs_r gs_or gs_scl">
  <div class="gs_ggs gs_fl">
    <div class="gs_ggsd">
      <a class="gs_or_ggsm_exp" href="javascript:void(0)" aria-expanded="false" role="button">
        <span class="gs_ctg2">[PDF]</span>
      </a>
      <div class="gs_or_ggsm">
        <a href="https://repositorio.unal.edu.co/bitstream/handle/unal/12345/articulo_investigacion.pdf">
          <span class="gs_ctg2">[PDF]</span> unal.edu.co
        </a>
      </div>
    </div>
  </div>
  <div class="gs_ri">
    <h3 class="gs_rt">
      <a href="https://repositorio.unal.edu.co/handle/unal/12345">Sistemas de Distribución Eléctrica Inteligentes</a>
    </h3>
    <div class="gs_a">M Gómez, R Pérez - Revista Energía, 2023 - unal.edu.co</div>
    <div class="gs_rs">Análisis de estabilidad en redes de distribución con penetración fotovoltaica.</div>
  </div>
</div>
HTML;

        $results = $this->service->parseGoogleScholarHtml($html, ['default_category' => 'Paper'], 1, 'Paper', 5);

        $this->assertCount(1, $results);
        $this->assertEquals('Sistemas de Distribución Eléctrica Inteligentes', $results[0]['title']);
        $this->assertEquals(
            'https://repositorio.unal.edu.co/bitstream/handle/unal/12345/articulo_investigacion.pdf',
            $results[0]['external_url']
        );
        $this->assertStringNotContainsString('javascript:', $results[0]['external_url']);
    }

    public function test_dom_crawler_extracts_json_ld_distribution_pdf(): void
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ScholarlyArticle",
        "headline": "Estudio de Suelos Agrícolas",
        "distribution": [
            {
                "@type": "DataDownload",
                "encodingFormat": "application/pdf",
                "contentUrl": "https://agronomia.org/descargas/suelos-2024.pdf"
            }
        ]
    }
    </script>
</head>
<body>
    <h1>Estudio de Suelos Agrícolas</h1>
</body>
</html>
HTML;

        $baseUrl = 'https://agronomia.org/articulos/suelos-2024';
        $extracted = $this->service->extractDirectDocumentLinkFromHtml($html, $baseUrl);

        $this->assertEquals('https://agronomia.org/descargas/suelos-2024.pdf', $extracted);
    }

    public function test_dom_crawler_extracts_dspace_bitstream_pdf(): void
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<body>
    <div class="item-page-field-wrapper">
        <a class="btn btn-primary" href="/bitstream/handle/123456789/789/tesis_maestria_final.pdf?sequence=1&isAllowed=y">
            Descargar Documento PDF (4.2 MB)
        </a>
    </div>
</body>
</html>
HTML;

        $baseUrl = 'https://repositorio.institucional.edu.pe/handle/123456789/789';
        $extracted = $this->service->extractDirectDocumentLinkFromHtml($html, $baseUrl);

        $this->assertEquals('https://repositorio.institucional.edu.pe/bitstream/handle/123456789/789/tesis_maestria_final.pdf?sequence=1&isAllowed=y', $extracted);
    }

    public function test_resolve_doi_to_open_access_pdf_via_unpaywall(): void
    {
        $unpaywallResponse = [
            'doi' => '10.1016/j.agwat.2023.108250',
            'is_oa' => true,
            'best_oa_location' => [
                'url_for_pdf' => 'https://www.sciencedirect.com/science/article/pii/S037837742300101X/pdfft?isDTMRedir=true&download=true',
                'url' => 'https://doi.org/10.1016/j.agwat.2023.108250',
            ],
        ];

        Http::fake([
            'https://api.unpaywall.org/v2/10.1016*' => Http::response($unpaywallResponse, 200),
        ]);

        $resolved = $this->service->resolveDoiToOpenAccessPdf('10.1016/j.agwat.2023.108250');

        $this->assertEquals(
            'https://www.sciencedirect.com/science/article/pii/S037837742300101X/pdfft?isDTMRedir=true&download=true',
            $resolved
        );
    }

    public function test_query_doaj_returns_structured_records(): void
    {
        $doajResponse = [
            'total' => 1,
            'results' => [
                [
                    'bibjson' => [
                        'title' => 'Gestión Sostenible del Agua en Cuencas Amazónicas',
                        'author' => [
                            ['name' => 'Ana Morales'],
                            ['name' => 'Carlos Silva'],
                        ],
                        'year' => '2024',
                        'journal' => [
                            'title' => 'Revista Peruana de Recursos Hídricos',
                            'publisher' => 'FVC Press',
                        ],
                        'abstract' => 'Investigación exhaustiva sobre la conservación y monitoreo hídrico.',
                        'link' => [
                            [
                                'type' => 'fulltext',
                                'content_type' => 'PDF',
                                'url' => 'https://revistas.peru.org/recursos/articulo-2024.pdf',
                            ],
                        ],
                        'identifier' => [
                            ['type' => 'doi', 'id' => '10.5555/rprh.2024.1'],
                        ],
                    ],
                ],
            ],
        ];

        Http::fake([
            'https://doaj.org/api/search/articles/*' => Http::response($doajResponse, 200),
        ]);

        $results = $this->service->queryDoaj('cuencas amazonicas', ['default_category' => 'Paper'], 1, 'Paper', 5);

        $this->assertCount(1, $results);
        $this->assertEquals('Gestión Sostenible del Agua en Cuencas Amazónicas', $results[0]['title']);
        $this->assertEquals('Ana Morales, Carlos Silva', $results[0]['author']);
        $this->assertEquals('https://revistas.peru.org/recursos/articulo-2024.pdf', $results[0]['external_url']);
        $this->assertEquals('2024', $results[0]['publication_year']);
    }

    public function test_query_europe_pmc_returns_peer_reviewed_records(): void
    {
        $pmcResponse = [
            'resultList' => [
                'result' => [
                    [
                        'id' => 'PMC13631969',
                        'pmcid' => 'PMC13631969',
                        'title' => 'Cuidados Clínicos en Enfermería y Atención Primaria',
                        'authorString' => 'Dra. Elena Ramos, Lic. Juan Castro',
                        'journalTitle' => 'Revista Latinoamericana de Enfermería',
                        'pubType' => 'journal article',
                        'isOpenAccess' => 'Y',
                        'hasPDF' => 'Y',
                        'firstPublicationDate' => '2025-06-15',
                        'abstractText' => 'Investigación sobre procedimientos de bioseguridad y atención hospitalaria.',
                        'doi' => '10.1590/rlae.2025.1001',
                    ],
                ],
            ],
        ];

        Http::fake([
            'https://www.ebi.ac.uk/europepmc/webservices/rest/search*' => Http::response($pmcResponse, 200),
        ]);

        $results = $this->service->queryEuropePmc('enfermeria clinica', ['default_category' => 'Paper'], 2, 'Paper', 5);

        $this->assertCount(1, $results);
        $this->assertEquals('Cuidados Clínicos en Enfermería y Atención Primaria', $results[0]['title']);
        $this->assertEquals('Dra. Elena Ramos, Lic. Juan Castro', $results[0]['author']);
        $this->assertEquals('https://europepmc.org/backend/ptpmcrender.fcgi?accid=PMC13631969&blobtype=pdf', $results[0]['external_url']);
        $this->assertEquals(2025, $results[0]['publication_year']);
        $this->assertEquals('Revista', $results[0]['category']);
    }

    public function test_query_open_library_returns_digitized_books_with_ia_pdf(): void
    {
        $olResponse = [
            'docs' => [
                [
                    'title' => 'Manual Completo de Redes de Computadoras y Telecomunicaciones',
                    'author_name' => ['Andrew S. Tanenbaum', 'David J. Wetherall'],
                    'publisher' => ['Prentice Hall Latinoamericana'],
                    'first_publish_year' => 2021,
                    'number_of_pages_median' => 960,
                    'isbn' => ['9786073208597'],
                    'ia' => ['redesdecomputadoras01tane'],
                    'key' => '/works/OL12345W',
                ],
            ],
        ];

        Http::fake([
            'https://openlibrary.org/search.json*' => Http::response($olResponse, 200),
        ]);

        $results = $this->service->queryOpenLibrary('redes computadoras', ['default_category' => 'Libro'], 3, 'Libro', 5);

        $this->assertCount(1, $results);
        $this->assertEquals('Manual Completo de Redes de Computadoras y Telecomunicaciones', $results[0]['title']);
        $this->assertEquals('Andrew S. Tanenbaum, David J. Wetherall', $results[0]['author']);
        $this->assertEquals('https://archive.org/download/redesdecomputadoras01tane/redesdecomputadoras01tane.pdf', $results[0]['external_url']);
        $this->assertEquals('Libro', $results[0]['category']);
        $this->assertEquals(2021, $results[0]['publication_year']);
        $this->assertEquals(960, $results[0]['pages']);
    }

    public function test_sanitize_search_term_cleans_lucene_special_characters(): void
    {
        $raw = 'redes: [cisco] OR {routing}^2 ~test* \ protocols /';
        $cleaned = $this->service->sanitizeSearchTerm($raw);

        $this->assertEquals('redes cisco OR routing 2 test protocols', $cleaned);
        $this->assertStringNotContainsString('[', $cleaned);
        $this->assertStringNotContainsString(']', $cleaned);
        $this->assertStringNotContainsString('{', $cleaned);
        $this->assertStringNotContainsString('}', $cleaned);
        $this->assertStringNotContainsString('^', $cleaned);
        $this->assertStringNotContainsString('~', $cleaned);
        $this->assertStringNotContainsString('*', $cleaned);
        $this->assertStringNotContainsString(':', $cleaned);
    }

    public function test_resolve_direct_document_link_with_europe_pmc_and_internet_archive(): void
    {
        // Europe PMC pattern
        $epmcUrl = 'https://europepmc.org/article/PMC/13631969';
        $this->assertEquals(
            'https://europepmc.org/backend/ptpmcrender.fcgi?accid=PMC13631969&blobtype=pdf',
            $this->service->resolveDirectDocumentLink($epmcUrl)
        );

        // Internet Archive pattern
        $iaUrl = 'https://archive.org/details/manual_forestal_2024';
        $this->assertEquals(
            'https://archive.org/download/manual_forestal_2024/manual_forestal_2024.pdf',
            $this->service->resolveDirectDocumentLink($iaUrl)
        );
    }

    public function test_resolve_doi_multi_tier_fallback_to_crossref_when_unpaywall_fails(): void
    {
        $doi = '10.1007/s00267-024-01999-x';

        // Unpaywall fails with 404
        // Crossref succeeds with a genuine PDF link
        Http::fake([
            'https://api.unpaywall.org/v2/*' => Http::response(['message' => 'Not Found'], 404),
            'https://api.crossref.org/works/*' => Http::response([
                'status' => 'ok',
                'message' => [
                    'link' => [
                        [
                            'URL' => 'https://link.springer.com/content/pdf/10.1007/s00267-024-01999-x.pdf',
                            'content-type' => 'application/pdf',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $resolved = $this->service->resolveDoiToOpenAccessPdf($doi);

        $this->assertEquals('https://link.springer.com/content/pdf/10.1007/s00267-024-01999-x.pdf', $resolved);
    }
}
