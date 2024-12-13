<?php


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bottle;
use App\Faker\WineBottleNameProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bouteille>
 */
class BottleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     public function definition(): array
     {
         return [
             'name' => WineBottleNameProvider::bottleName(),
             'price' => $this->faker->randomFloat(2, 5, 100),
             'image_url' => $this->faker->imageUrl(640, 480, 'wine', true),
             'country' => $this->faker->country(),
             'volume' => $this->faker->numberBetween(750, 1500),
             'type' => $this->faker->randomElement(['rouge', 'blanc', 'rose', 'vin rouge', 'vin blanc', 'vin rose']),
             'upc_saq' =>  (string)$this->faker->ean13(),
         ];
     }
}
