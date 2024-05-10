<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InvoiceItem;
use Faker\Generator as Faker;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            $factory->define(InvoiceItem::class, function (Faker $faker) {
                return [
                    'description' => $faker->sentence,
                    'amount' => $faker->randomFloat(2, 10, 100),
                    // citi atribūti
                ];
            })
            
        ];
    }
}

