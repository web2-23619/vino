<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Faker\WineBottleNameProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bouteille>
 */
class BouteilleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     public function definition(): array
     {
         return [
             'nom' => WineBottleNameProvider::bottleName(),
             'prix' => $this->faker->randomFloat(2, 5, 100),
             'image' => $this->faker->imageUrl(640, 480, 'wine', true),
             'pays' => $this->faker->country(),
             'volume' => $this->faker->numberBetween(750, 1500),
             'type' => $this->faker->randomElement(['rouge', 'blanc', 'rose', 'vin rouge', 'vin blanc', 'vin rose']),
             'upc_saq' =>  (string)$this->faker->ean13(),
         ];
     }
}
