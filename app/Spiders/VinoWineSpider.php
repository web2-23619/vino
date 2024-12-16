<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use RoachPHP\Downloader\Middleware\UserAgentMiddleware;

class VinoWineSpider extends BasicSpider
{


    public array $startUrls = [
        'https://www.saq.com/fr/produits/vin?product_list_limit=48'
    ];


    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
        [UserAgentMiddleware::class, 
        ['userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36']],
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        //
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 2;

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {

        dd('Spider started');
        $statusCode = $response->getStatusCode();
        echo "Received status code: $statusCode\n";
        
        $links = $response->filter('div.column.main')->links();
        
        foreach($links as $link) {
            yield $this->request('GET', $link->getUri(), 'parseDetails');
        }

        $nextPageLink = $response->filter('li.pages-item-next');
    
        if($nextPageLink) {
            $nextPageUrl = $nextPageLink->link()->getUri();
            yield $this->request('GET', $nextPageUrl, 'parseDetails');
        }
    }

    public function parseDetails(Response $response): Generator {
        $imageUrl = $response->filterXPath('//*[@id="MagicZoomPlusImage-product-333885"]')->extract(['href'])[0];
        $title = $response->filter('h1.page-title')->text();
        $type = $response->filterXPath('//*[@id="maincontent"]/div/div/div[1]/div[2]/div[2]/div/div[1]');
        $volume = $response->filterXPath('//*[@id="maincontent"]/div/div/div[1]/div[2]/div[2]/div/div[2]/span/strong');
        $country = $response->filterXPath('//*[@id="maincontent"]/div/div/div[1]/div[2]/div[2]/div/div[3]/span/strong');
        $price = $response->filterXPath('//*[@id="product-price-333885"]/span')->text();
        $upc = $response->filterXPath('//*[@id="additional"]/div/div[2]/ul/li[15]/strong')->text();


        yield $this->item(compact('imageUrl', 'title', 'type', 'volume', 'country', 'price', 'upc'));
    }
}
