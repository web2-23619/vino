<?php

namespace App\Console\Commands;

use App\Jobs\FetchTotalPagesJob; 
use Illuminate\Console\Command;

class ScrapeProductsQueue extends Command
{
    protected $signature = 'scrape:products-queue';
    protected $description = 'Scrape products and save them to the database using queues';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Démarrage du scraping des produits...");

        // Lancer le job pour récupérer le nombre total de pages et ensuite démarrer le scraping
        FetchTotalPagesJob::dispatch();

        $this->info("Le processus de scraping a démarré !");
    }
}
