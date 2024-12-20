<?php

namespace App\Jobs;

use App\Models\ScrapeJob;
use App\Models\Bottle;
use Goutte\Client;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ScrapePageJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    protected $scrapeJob;

    // Nombre de tentatives et délai d'expiration
    public $tries = 6; 
    public $timeout = 180;

    public function __construct(ScrapeJob $scrapeJob)
    {
        $this->scrapeJob = $scrapeJob;
    }

    public function handle()
    {
        $client = new Client();
        $currentPage = $this->scrapeJob->current_page;
        $url = "https://www.saq.com/fr/produits/vin?p=" . $currentPage;

        try {
            $crawler = $client->request('GET', $url);
        } catch (Exception $e) {
            Log::error("Erreur lors de la récupération de la page $url : " . $e->getMessage());

            // Réessayer après 60 secondes si l'échec est dû à un problème temporaire
            if ($this->attempts() < 5) {
                Log::info("Tentative " . $this->attempts() . " échouée pour la page $currentPage, réessai...");
                $this->release(60);
            } else {
                Log::error("Le job pour la page $currentPage a échoué après plusieurs tentatives.");
            }

            return;  // Arrêter l'exécution du job
        }

        // Attendre quelques secondes pour simuler un comportement humain
        sleep(rand(5, 10));

        $links = $crawler->filter('a.product.photo')->each(function ($node) {
            return $node->attr('href');
        });

        if (count($links) === 0) {
            Log::info("Aucun produit trouvé sur la page $currentPage.");
            return;
        }

        $bottlesData = [];
        foreach ($links as $link) {
            try {
                $crawler = $client->request('GET', $link);

                $bottle = [
                    'name' => $this->cleanString($crawler->filter('h1.page-title')->text()),
                    'price' => $this->cleanPrice($crawler->filter('div.product-info-price')->text()),
                    'image_url' => $this->cleanImageUrl($crawler->filter('img[itemprop="image"]')->attr('src')),
                    'country' => $this->cleanCountry($crawler->filter('div.attribute.country')->text()),
                    'volume' => $this->cleanVolume($crawler->filter('div.attribute.format')->text()),
                    'type' => $this->cleanString($crawler->filter('div.attribute.identity')->text()),
                    'upc_saq' => $this->cleanString($crawler->filter('strong[data-th="Code CUP"]')->text()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Vérifier si le produit existe déjà avant de l'ajouter
                if (!Bottle::where('upc_saq', $bottle['upc_saq'])->exists()) {
                    $bottlesData[] = $bottle;
                }

                // Insérer les produits par lots de 50
                if (count($bottlesData) >= 50) {
                    Bottle::insert($bottlesData);
                    $bottlesData = [];  // Vider le tableau après l'insertion
                }

            } catch (Exception $e) {
                Log::error("Erreur lors du traitement du lien $link : " . $e->getMessage());
            }
        }

        // Insérer les produits restants
        if (count($bottlesData) > 0) {
            Bottle::insert($bottlesData);
        }

        // Mettre à jour la page après le scraping
        $this->scrapeJob->update(['current_page' => $currentPage + 1]);

        Log::info("La page $currentPage a été scrappée avec succès.");
    }

    // Méthodes de nettoyage des données
    private function cleanString($string)
    {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    private function cleanPrice($price)
    {
        $price = preg_replace('/[^0-9.,]/', '', $price);
        $price = str_replace(',', '.', $price);
        return is_numeric($price) ? number_format((float)$price, 2, '.', '') : 0.00;
    }

    private function cleanImageUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) ? $url : 'https://via.placeholder.com/150';
    }

    private function cleanCountry($country)
    {
        return trim(preg_replace('/\|/', '', $country));
    }

    private function cleanVolume($volume)
    {
        if (preg_match('/(\d+)\s*ml/', $volume, $matches)) {
            return (int)$matches[1];  // Retourner le nombre extrait
        }
        return 0;  // Si aucune correspondance n'est trouvée, retourner 0
    }

    // Méthode de gestion des échecs
    public function failed(Exception $exception)
    {
        Log::error("Le job a échoué pour la page {$this->scrapeJob->current_page} : " . $exception->getMessage());
    }
}
