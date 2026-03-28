<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\JobOffer;
use Illuminate\Support\Facades\Log;

class ScrapeJobBoard extends Command
{
    protected $signature = 'scrape:jobs {source_url} {source_name}';
    protected $description = 'Scrapea ofertas laborales de una URL específica';

    public function handle()
    {
        $url = $this->argument('source_url');
        $sourceName = $this->argument('source_name');
        
        $this->info("Iniciando scraping en: {$url}");

        $client = new Client([
            'timeout'  => 10.0,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'es-ES,es;q=0.8,en-US;q=0.5,en;q=0.3',
            ]
        ]);

        try {
            // Simulamos un pequeño retraso para no saturar el servidor destino (Rate Limiting básico)
            sleep(rand(1, 3)); 
            
            $response   = $client->request('GET', $url);
            $html       = (string) $response->getBody();
            $crawler    = new Crawler($html);

            $count      = 0;

            // OJO: Estos selectores (.job-card, .title, etc.) DEBEN cambiarse según la web que vayas a scrapear
            $crawler->filter('.job-card')->each(function (Crawler $node) use ($sourceName, &$count) {
                try {
                    $title = $node->filter('.job-title')->text();
                    $company = $node->filter('.job-company')->text();
                    $jobUrl = $node->filter('.job-link')->attr('href');
                    
                    // Asegurar que la URL sea absoluta
                    if (!str_starts_with($jobUrl, 'http')) {
                        $jobUrl = 'https://sitiodeejemplo.com' . $jobUrl;
                    }

                    JobOffer::updateOrCreate(
                        ['url' => $jobUrl],
                        [
                            'title' => trim($title),
                            'company' => trim($company),
                            'source' => $sourceName,
                            'is_active' => true
                        ]
                    );
                    $count++;
                } catch (\Exception $e) {
                    // Si falla una tarjeta (ej. le falta un dato), lo registramos y seguimos con la siguiente
                    Log::warning("Error al parsear una tarjeta de trabajo: " . $e->getMessage());
                }
            });

            $this->info("¡Scraping completado! Se procesaron {$count} ofertas de {$sourceName}.");

        } catch (\Exception $e) {
            $this->error('Error fatal durante el scraping: ' . $e->getMessage());
            Log::error('Scraping Error: ' . $e->getMessage());
        }
    }
}