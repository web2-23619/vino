<?php

namespace App\Jobs;

use App\Jobs\ScrapePageJob;
use App\Models\ScrapeJob;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class FetchTotalPagesJob implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function handle()
    {
        // Initialisation du client Goutte pour la requête
        $client = new Client();
        
        // URL de la première page
        $url = "https://www.saq.com/fr/produits/vin?p=385";  // Page 1 pour récupérer la pagination

        try {
            // Effectuer une requête GET pour récupérer la page de la première page de produits
            $crawler = $client->request('GET', $url);
        } catch (\Exception $e) {
            // En cas d'erreur lors de la récupération de la page
            Log::error("Erreur lors de la récupération de la page de pagination : " . $e->getMessage());
            return;
        }

        // Chercher l Dernier numéro de la pagination
         $totalPages = $crawler->filter('span.toolbar-number:last-child')->text();  
         $totalPages = (int)$totalPages;
         $totalPages = ceil($totalPages /24);

        // Vérifier si on a trouvé un total de pages valide
        if ($totalPages <= 0) {
            Log::error("Le nombre total de pages est invalide ou non trouvé.");
            return;
        }

        // Log de l'information
        Log::info("Le total de pages à scraper est : $totalPages");

        // Lancer un job pour chaque page à scraper (on commence à la page 1 et jusqu'à la dernière)
        for ($page = 1; $page <= $totalPages; $page++) {
            // Créez un objet ScrapeJob et passez-le au job
            $scrapeJob = ScrapeJob::create([
                'current_page' => $page,
                'status' => 'pending'
            ]);

            // Dispatch du job ScrapePageJob avec l'objet ScrapeJob
            ScrapePageJob::dispatch($scrapeJob);
        }
    }
}
