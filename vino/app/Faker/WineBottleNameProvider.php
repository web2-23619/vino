<?php

namespace App\Faker;


class WineBottleNameProvider {
    protected static $bottlesNames = [
        "Elegant Cabernet from Bordeaux",
        "Bold Merlot from Napa Valley",
        "Rich Chardonnay from Tuscany",
        "Smooth Pinot Noir from Barossa",
        "Crisp Sauvignon Blanc from Loire",
        "Fruity Syrah from Rioja",
        "Vintage Reserve Malbec",
        "Golden Hills Zinfandel",
        "Classic Estate Riesling",
        "Oak Barrel Tempranillo"
    ];

    public static function bottleName(){
        return self::$bottlesNames[array_rand(self::$bottlesNames)];
    }
}