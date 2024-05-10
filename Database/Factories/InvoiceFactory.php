<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InvoiceItem;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            $factory->define(Invoice::class, function (Faker $faker) {
                return [
                    'customer_id' => rand(1, 20), // pieņemsim, ka ir 20 klienti, pielāgojiet pēc saviem datiem
                    'total_amount' => $faker->randomFloat(2, 50, 500),
                    // citi atribūti
                ];
            })
        ];
    }
}


