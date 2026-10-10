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
}
