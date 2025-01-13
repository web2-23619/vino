<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class GoutteController extends Controller
{

    // Une fonction pour créer un objet data

    public function item(array $products){
  
        return $products;
    }

    public function get_data($client, $url) {

        $products = [];

        $crawler = $client->request('GET', $url);

        $crawler->filterXPath('//*[@id="maincontent"]/div/div[2]/div[3]/ol')->each(function ($node) use ($client, &$products) {
            $productPage = $client->click($node->link());

            $productImageUrl = $productPage->filter('img')->attr('src') ?? 'No image';
            $productTitle = $productPage->filter('h1.page-title')->text() ?? 'Title not found';
            $productType = $productPage->filter('div.attribute.identity')->text() ?? 'Type not found';
            $productVolume = $productPage->filter('div.attribute.format')->text() ?? 'Volume not found';
            $productCountry = $productPage->filter('div.attribute.country')->text() ?? 'Country not found';
            $productPrice = $productPage->filter('div.product-info-price')->text() ?? 'Price not found';
            $productUpc = $productPage->filter('strong[data-th="Code CUP"]')->text() ?? 'Upc not found';

            $products[] = $this->item(compact(
                'productImageUrl', 'productTitle', 'productType',
                'productVolume', 'productCountry', 'productPrice', 'productUpc'
            ));
            dd($products);
        });
        return $products;

    }

    public function index() {

        set_time_limit(1000);

        $client = new Client();
        $url = 'https://www.saq.com/fr/produits/vin?page=1';

        $allProducts = [];

        $crawler = $client->request('GET', $url);
        $nextPage = $crawler->filter('li.item.pages-item-next a.action.next');

        while ($nextPage) {
            $products = $this->get_data($client, $url);
            $allProducts = array_merge($allProducts, $products);

            try {
                $url = $nextPage->link()->getUri();
            } catch (InvalidArgumentExecption) {
                return null;
            }
        }

        return view('goutte', ['products' => $allProducts]);
    }

}
