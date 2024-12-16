<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Spiders\VinoWineSpider;
use RoachPHP\Roach;

class SpiderController extends Controller
{
    public function index() {

        Roach::startSpider(VinoWineSpider::class);
        // dd(new VinoWineSpider());

        $items = Roach::collectSpider(VinoWineSpider::class);
        // dd($items); 
        // dd(class_exists('App\Spiders\VinoWineSpider'));


        return view('spider', compact('items'));
    }
}
