<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Str;

class VirtualDocumentGeneratorService
{
    private float $pageWidth = 595.28;  // A4 width in pt

    private float $pageHeight = 841.89; // A4 height in pt

    /**
     * Generate a complete, valid PDF 1.4 document containing the institutional virtual copy.
     */
    public function generate(Book $book): string
    {
        $pages = [];

        // ══════════════════════════════════════════════════════════════════════
        // PÁGINA 1: PORTADA Y FICHA ACADÉMICA CERTIFICADA
        // ══════════════════════════════════════════════════════════════════════
        $p1 = $this->buildCoverPage($book);
        $pages[] = $p1;

        // ══════════════════════════════════════════════════════════════════════
        // PÁGINA 2: RESUMEN / ABSTRACT EXTENDIDO Y CITACIÓN ACADÉMICA
        // ══════════════════════════════════════════════════════════════════════
        $description = trim((string) ($book->description ?: 'No se dispone de un resumen extendido para este recurso. Consulte la fuente oficial para más detalles.'));
        $p2 = $this->buildAbstractPage($book, $description);
        $pages[] = $p2;

        return $this->compilePdf($pages);
    }

    /**
     * Construye el flujo de contenido de la página 1.
     */
    private function buildCoverPage(Book $book): string
    {
        $stream = '';

        // 1. Franja Superior Institucional (Dark Slate + Acento Teal)
        $stream .= $this->color(0.06, 0.09, 0.16); // #0f172a
        $stream .= $this->rect(0, 755, $this->pageWidth, 87, 'F');
        $stream .= $this->color(0.05, 0.58, 0.53); // #0d9488
        $stream .= $this->rect(0, 750, $this->pageWidth, 5, 'F');

        // Textos del Encabezado Institucional
        $stream .= $this->color(1, 1, 1);
        $stream .= $this->text('INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO', 45, 812, 'F2', 10);
        $stream .= $this->text('FRANCISCO VIGO CABALLERO', 45, 794, 'F2', 15);
        $stream .= $this->color(0.60, 0.85, 0.82);
        $stream .= $this->text('BIBLIOTECA VIRTUAL INSTITUCIONAL · REPOSITORIO DE INVESTIGACIÓN', 45, 776, 'F1', 8.5);

        // 2. Insignia de Certificación y Copia Virtual
        $badgeText = mb_strtoupper($book->category ?: 'RECURSO ACADÉMICO').' · COPIA DIGITAL DE ACCESO ABIERTO';
        $stream .= $this->color(0.92, 0.96, 0.96);
        $stream .= $this->rect(45, 715, $this->pageWidth - 90, 24, 'F');
        $stream .= $this->color(0.05, 0.58, 0.53);
        $stream .= $this->rect(45, 715, 4, 24, 'F');
        $stream .= $this->text($badgeText, 56, 723, 'F2', 9);

        // 3. Título del Documento
        $y = 680;
        $stream .= $this->color(0.09, 0.12, 0.18);
        $title = $book->title ?: 'Documento Académico Institucional';
        $titleLines = $this->wrapText($title, 500, 6.8);
        foreach (array_slice($titleLines, 0, 4) as $line) {
            $stream .= $this->text($line, 45, $y, 'F2', 13.5);
            $y -= 18;
        }

        // 4. Autores y Programa
        $y -= 6;
        $authorText = 'Autor(es): '.($book->author ?: 'Varios Autores / No especificado');
        $authorLines = $this->wrapText($authorText, 505, 5.0);
        $stream .= $this->color(0.25, 0.30, 0.38);
        foreach (array_slice($authorLines, 0, 2) as $line) {
            $stream .= $this->text($line, 45, $y, 'F2', 10);
            $y -= 14;
        }

        $progName = $book->studyProgram?->name ?: 'Programa General / Multidisciplinario';
        $stream .= $this->color(0.05, 0.58, 0.53);
        $stream .= $this->text('Programa de Estudio: '.$progName, 45, $y, 'F1', 9.5);
        $y -= 22;

        // 5. Tabla / Tarjeta de Metadatos Oficiales
        $cardHeight = 115;
        $stream .= $this->color(0.97, 0.98, 0.99);
        $stream .= $this->rect(45, $y - $cardHeight, $this->pageWidth - 90, $cardHeight, 'F');
        $stream .= $this->color(0.85, 0.88, 0.92);
        $stream .= $this->rect(45, $y - $cardHeight, $this->pageWidth - 90, $cardHeight, 'S');

        $metaY = $y - 20;
        $stream .= $this->color(0.35, 0.40, 0.48);
        $stream .= $this->text('Editorial / Repositorio:', 58, $metaY, 'F2', 9);
        $stream .= $this->color(0.12, 0.16, 0.22);
        $stream .= $this->text(Str::limit($book->publisher ?: 'Repositorio Científico Abierto', 65), 185, $metaY, 'F1', 9);

        $metaY -= 17;
        $stream .= $this->color(0.35, 0.40, 0.48);
        $stream .= $this->text('Año de Publicación:', 58, $metaY, 'F2', 9);
        $stream .= $this->color(0.12, 0.16, 0.22);
        $stream .= $this->text((string) ($book->publication_year ?: 'Reciente'), 185, $metaY, 'F1', 9);

        $metaY -= 17;
        $stream .= $this->color(0.35, 0.40, 0.48);
        $stream .= $this->text('Identificador / DOI:', 58, $metaY, 'F2', 9);
        $stream .= $this->color(0.12, 0.16, 0.22);
        $targetUrl = $book->external_url ?: 'https://biblioteca.iestpfvc.edu.pe';
        $stream .= $this->text(Str::limit($targetUrl, 60), 185, $metaY, 'F1', 8.5);

        $metaY -= 17;
        $stream .= $this->color(0.35, 0.40, 0.48);
        $stream .= $this->text('Tipo de Acceso:', 58, $metaY, 'F2', 9);
        $stream .= $this->color(0.05, 0.58, 0.53);
        $stream .= $this->text('Acceso Abierto Institucional (Open Access · Sin Barreras de Pago)', 185, $metaY, 'F2', 9);

        $metaY -= 17;
        $stream .= $this->color(0.35, 0.40, 0.48);
        $stream .= $this->text('Certificación Digital:', 58, $metaY, 'F2', 9);
        $stream .= $this->color(0.12, 0.16, 0.22);
        $stream .= $this->text('Generada automáticamente para lectura y consulta in-app (IESTP-FVC)', 185, $metaY, 'F1', 8.5);

        $y -= ($cardHeight + 20);

        // 6. Caja de Certificación y Marco Legal
        $noticeHeight = 72;
        $stream .= $this->color(0.95, 0.97, 1.00);
        $stream .= $this->rect(45, $y - $noticeHeight, $this->pageWidth - 90, $noticeHeight, 'F');
        $stream .= $this->color(0.20, 0.45, 0.85);
        $stream .= $this->rect(45, $y - $noticeHeight, 4, $noticeHeight, 'F');

        $stream .= $this->color(0.12, 0.28, 0.65);
        $stream .= $this->text('DECLARACIÓN DE PRESERVACIÓN Y ACCESO DIGITAL PEDAGÓGICO', 58, $y - 18, 'F2', 9);
        $noticeText = 'Este documento constituye una copia virtual institucional certificada emitida por el Sistema de Biblioteca del IESTP Francisco Vigo Caballero con fines de investigación, docencia y aprendizaje (D.L. 822 y Ley N° 31250). Garantiza la lectura in-app ante políticas externas de restricción de incrustación de marcos (Cross-Origin Frame Protection).';
        $noticeLines = $this->wrapText($noticeText, 480, 4.8);
        $ny = $y - 32;
        $stream .= $this->color(0.20, 0.30, 0.45);
        foreach (array_slice($noticeLines, 0, 3) as $nl) {
            $stream .= $this->text($nl, 58, $ny, 'F1', 8);
            $ny -= 12;
        }

        $y -= ($noticeHeight + 25);

        // 7. Código QR Vectorial Nítido para Acceder al Enlace Oficial
        $qrSize = 70;
        $qrX = 45;
        $qrY = $y - $qrSize;
        $stream .= $this->renderVectorQrCode($targetUrl, $qrX, $qrY, $qrSize);

        // Explicación junto al QR
        $stream .= $this->color(0.10, 0.15, 0.22);
        $stream .= $this->text('CÓDIGO QR DE VERIFICACIÓN OFICIAL', 130, $y - 18, 'F2', 9.5);
        $stream .= $this->color(0.35, 0.40, 0.48);
        $stream .= $this->text('Escanea este código con tu dispositivo móvil para acceder', 130, $y - 32, 'F1', 8.5);
        $stream .= $this->text('directamente a la publicación original en el repositorio oficial.', 130, $y - 44, 'F1', 8.5);
        $stream .= $this->color(0.05, 0.58, 0.53);
        $stream .= $this->text(Str::limit($targetUrl, 68), 130, $y - 58, 'F1', 7.5);

        $y -= ($qrSize + 25);

        // 8. Cita Bibliográfica Sugerida (Norma APA 7ma Edición)
        $stream .= $this->color(0.10, 0.15, 0.22);
        $stream .= $this->text('CITA BIBLIOGRÁFICA RECOMENDADA (APA 7ª EDICIÓN):', 45, $y, 'F2', 9);
        $y -= 14;
        $firstAuthor = explode(',', $book->author ?: 'Autor Institucional')[0];
        $year = $book->publication_year ?: date('Y');
        $apa = "{$firstAuthor} ({$year}). {$book->title}. {$book->publisher}. {$targetUrl}";
        $apaLines = $this->wrapText($apa, 500, 5.0);
        $stream .= $this->color(0.30, 0.35, 0.45);
        foreach (array_slice($apaLines, 0, 3) as $al) {
            $stream .= $this->text($al, 45, $y, 'F3', 8.5);
            $y -= 12;
        }

        // Pie de Página 1
        $stream .= $this->buildFooter(1, 2);

        return $stream;
    }

    /**
     * Construye el flujo de contenido de la página 2 (Resumen / Abstract y detalles).
     */
    private function buildAbstractPage(Book $book, string $description): string
    {
        $stream = '';

        // Running Header
        $stream .= $this->color(0.06, 0.09, 0.16);
        $stream .= $this->rect(0, 795, $this->pageWidth, 47, 'F');
        $stream .= $this->color(0.05, 0.58, 0.53);
        $stream .= $this->rect(0, 790, $this->pageWidth, 5, 'F');

        $stream .= $this->color(1, 1, 1);
        $stream .= $this->text('IESTP FRANCISCO VIGO CABALLERO · BIBLIOTECA VIRTUAL', 45, 815, 'F2', 10);
        $stream .= $this->color(0.60, 0.85, 0.82);
        $stream .= $this->text('COPIA VIRTUAL INSTITUCIONAL · EXPEDIENTE ACADÉMICO N° '.$book->id, 45, 802, 'F1', 8);

        // Título de Sección
        $y = 755;
        $stream .= $this->color(0.05, 0.58, 0.53);
        $stream .= $this->rect(45, $y - 3, 4, 18, 'F');
        $stream .= $this->color(0.09, 0.12, 0.18);
        $stream .= $this->text('RESUMEN / ABSTRACT DEL RECURSO CIENTÍFICO', 55, $y + 1, 'F2', 12);
        $y -= 22;

        // Párrafos del Resumen
        $stream .= $this->color(0.20, 0.24, 0.30);
        $cleanDesc = strip_tags(html_entity_decode($description));
        $paragraphs = preg_split("/\n+/", $cleanDesc) ?: [$cleanDesc];

        foreach ($paragraphs as $para) {
            $para = trim($para);
            if ($para === '') {
                continue;
            }

            $lines = $this->wrapText($para, 505, 5.2);
            foreach ($lines as $line) {
                if ($y < 120) {
                    break 2; // Mantener dentro del espacio de la página
                }
                $stream .= $this->text($line, 45, $y, 'F1', 9.5);
                $y -= 14;
            }
            $y -= 8; // Espacio entre párrafos
        }

        // Sección de Notas de Consulta
        if ($y > 220) {
            $y -= 10;
            $stream .= $this->color(0.05, 0.58, 0.53);
            $stream .= $this->rect(45, $y - 3, 4, 16, 'F');
            $stream .= $this->color(0.09, 0.12, 0.18);
            $stream .= $this->text('DIRECTRICES DE USO Y CONSULTA ACADÉMICA', 55, $y + 1, 'F2', 10.5);
            $y -= 20;

            $notes = [
                '1. Material protegido por normativas de derecho de autor y de libre acceso académico para el IESTP Francisco Vigo Caballero.',
                '2. Para reproducir tablas, anexos y metodologías en trabajos de titulación o investigación, cite según la norma APA indicada.',
                '3. Si requiere el archivo original completo con anexos y códigos fuente, utilice el botón «Pestaña Nueva / Fuente Oficial» en el visor.',
            ];

            $stream .= $this->color(0.30, 0.35, 0.42);
            foreach ($notes as $note) {
                $stream .= $this->text($note, 45, $y, 'F1', 8.5);
                $y -= 14;
            }
        }

        // Pie de Página 2
        $stream .= $this->buildFooter(2, 2);

        return $stream;
    }

    /**
     * Construye la barra de pie de página común con numeración.
     */
    private function buildFooter(int $currentPage, int $totalPages): string
    {
        $stream = '';
        $stream .= $this->color(0.85, 0.88, 0.92);
        $stream .= $this->rect(45, 42, $this->pageWidth - 90, 1, 'F');

        $stream .= $this->color(0.45, 0.50, 0.58);
        $stream .= $this->text('IESTP Francisco Vigo Caballero · Uchiza, Tocache, San Martín · Repositorio Institucional', 45, 28, 'F1', 7.5);
        $pageText = "Página {$currentPage} de {$totalPages}";
        $stream .= $this->text($pageText, $this->pageWidth - 105, 28, 'F2', 7.5);

        return $stream;
    }

    /**
     * Renderiza un código QR nativo vectorial mediante rectángulos en el flujo PDF.
     */
    private function renderVectorQrCode(string $url, float $startX, float $startY, float $size): string
    {
        try {
            $matrix = QrCodeService::matrix($url);
            $moduleCount = count($matrix);
            if ($moduleCount === 0) {
                return '';
            }

            $moduleSize = $size / $moduleCount;
            $stream = '';

            // Fondo blanco para contraste
            $stream .= $this->color(1, 1, 1);
            $stream .= $this->rect($startX - 2, $startY - 2, $size + 4, $size + 4, 'F');

            // Módulos negros del código QR
            $stream .= $this->color(0.08, 0.12, 0.18);
            for ($r = 0; $r < $moduleCount; $r++) {
                for ($c = 0; $c < $moduleCount; $c++) {
                    if (! empty($matrix[$r][$c])) {
                        $x = $startX + ($c * $moduleSize);
                        // En PDF el eje Y comienza desde abajo
                        $y = $startY + (($moduleCount - 1 - $r) * $moduleSize);
                        $stream .= sprintf("%.2f %.2f %.2f %.2f re f\n", $x, $y, $moduleSize, $moduleSize);
                    }
                }
            }

            return $stream;
        } catch (\Throwable) {
            // Si el QR falla por URL demasiado larga, no romper la generación
            return '';
        }
    }

    /**
     * Establece color de trazo y relleno en RGB.
     */
    private function color(float $r, float $g, float $b): string
    {
        return sprintf("%.3f %.3f %.3f rg\n%.3f %.3f %.3f RG\n", $r, $g, $b, $r, $g, $b);
    }

    /**
     * Dibuja un rectángulo en el flujo PDF.
     */
    private function rect(float $x, float $y, float $w, float $h, string $mode = 'F'): string
    {
        $op = ($mode === 'F') ? 'f' : (($mode === 'B') ? 'B' : 's');

        return sprintf("%.2f %.2f %.2f %.2f re %s\n", $x, $y, $w, $h, $op);
    }

    /**
     * Escribe un texto posicionado.
     */
    private function text(string $text, float $x, float $y, string $font = 'F1', float $size = 11): string
    {
        $encoded = $this->encodePdfString($text);

        return "BT\n/{$font} {$size} Tf\n".sprintf('1 0 0 1 %.2f %.2f Tm', $x, $y)."\n({$encoded}) Tj\nET\n";
    }

    /**
     * Divide un texto en líneas según un ancho máximo aproximado.
     *
     * @return array<int, string>
     */
    private function wrapText(string $text, float $maxWidth, float $approxCharWidth = 5.5): array
    {
        $words = preg_split('/\s+/', $text) ?: [$text];
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine === '' ? $word : $currentLine.' '.$word;
            if (mb_strlen($testLine) * $approxCharWidth > $maxWidth && $currentLine !== '') {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }

    /**
     * Codifica caracteres a Windows-1252 / WinAnsiEncoding para visualización exacta de tildes y caracteres en PDF.
     */
    private function encodePdfString(string $text): string
    {
        $replacements = [
            '“' => '"', '”' => '"', '‘' => "'", '’' => "'",
            '—' => '-', '–' => '-', '…' => '...',
            '«' => '"', '»' => '"', '•' => '*',
        ];
        $text = strtr($text, $replacements);

        $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        if ($converted === false) {
            $converted = mb_convert_encoding($text, 'Windows-1252', 'UTF-8');
        }

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], (string) $converted);
    }

    /**
     * Compila las páginas en un documento PDF 1.4 binario válido.
     *
     * @param  array<int, string>  $pageStreams
     */
    private function compilePdf(array $pageStreams): string
    {
        $out = "%PDF-1.4\n%\xe2\xe3\xcf\xd3\n";
        $objIndex = 1;

        $catalogId = $objIndex++;
        $pagesId = $objIndex++;
        $fontRegularId = $objIndex++;
        $fontBoldId = $objIndex++;
        $fontItalicId = $objIndex++;

        $pageObjectIds = [];
        $streamData = [];

        foreach ($pageStreams as $stream) {
            $pId = $objIndex++;
            $sId = $objIndex++;
            $pageObjectIds[] = $pId;
            $streamData[] = [$pId, $sId, $stream];
        }

        $offsets = [];

        // 1: Catalog
        $offsets[$catalogId] = strlen($out);
        $out .= "{$catalogId} 0 obj\n<< /Type /Catalog /Pages {$pagesId} 0 R >>\nendobj\n";

        // 2: Pages Tree
        $kids = implode(' ', array_map(fn ($id) => "{$id} 0 R", $pageObjectIds));
        $count = count($pageObjectIds);
        $offsets[$pagesId] = strlen($out);
        $out .= "{$pagesId} 0 obj\n<< /Type /Pages /Kids [{$kids}] /Count {$count} >>\nendobj\n";

        // Fonts estándar Type 1
        $offsets[$fontRegularId] = strlen($out);
        $out .= "{$fontRegularId} 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>\nendobj\n";

        $offsets[$fontBoldId] = strlen($out);
        $out .= "{$fontBoldId} 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>\nendobj\n";

        $offsets[$fontItalicId] = strlen($out);
        $out .= "{$fontItalicId} 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Oblique /Encoding /WinAnsiEncoding >>\nendobj\n";

        // Page and Content Stream objects
        foreach ($streamData as [$pId, $sId, $streamContent]) {
            $offsets[$pId] = strlen($out);
            $out .= "{$pId} 0 obj\n<< /Type /Page /Parent {$pagesId} 0 R /MediaBox [0 0 {$this->pageWidth} {$this->pageHeight}] /Contents {$sId} 0 R /Resources << /Font << /F1 {$fontRegularId} 0 R /F2 {$fontBoldId} 0 R /F3 {$fontItalicId} 0 R >> >> >>\nendobj\n";

            $streamLen = strlen($streamContent);
            $offsets[$sId] = strlen($out);
            $out .= "{$sId} 0 obj\n<< /Length {$streamLen} >>\nstream\n{$streamContent}\nendstream\nendobj\n";
        }

        $xrefOffset = strlen($out);
        $totalObjects = $objIndex;
        $out .= "xref\n0 {$totalObjects}\n0000000000 65535 f \n";
        for ($i = 1; $i < $totalObjects; $i++) {
            $out .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $out .= "trailer\n<< /Size {$totalObjects} /Root {$catalogId} 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";

        return $out;
    }
}
