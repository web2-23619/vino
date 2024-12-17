<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Spiders\VinoWineSpider;
use RoachPHP\Roach;

class SpiderController extends Controller
{
    public function index() {
        // dd(new VinoWineSpider());

        $items = Roach::collectSpider(VinoWineSpider::class);
        // dd($items); 
        // dd(class_exists('App\Spiders\VinoWineSpider'));

        return view('spider', ['items' => $items]);
        // return view('spider', ['items' => $items]);
    }
}
