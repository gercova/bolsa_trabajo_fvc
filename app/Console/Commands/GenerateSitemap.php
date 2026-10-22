<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitemapController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--url= : Base URL to override (default: https://iestpfranciscovigocaballero.edu.pe)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera el archivo sitemap.xml en public/sitemap.xml con las rutas públicas oficiales';

    /**
     * Execute the console command.
     */
    public function handle(SitemapController $sitemapController): int
    {
        $baseUrl = $this->option('url') ?: SitemapController::PRODUCTION_BASE_URL;

        $this->info("Generando sitemap.xml para el dominio: {$baseUrl}...");

        $xml = $sitemapController->buildSitemapXml($baseUrl);
        $outputPath = public_path('sitemap.xml');

        File::put($outputPath, $xml);

        $this->info("✓ Sitemap generado con éxito en: {$outputPath}");
        $this->line('Tamaño: '.strlen($xml).' bytes.');

        return Command::SUCCESS;
    }
}
