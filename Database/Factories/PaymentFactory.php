<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payment;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            $factory->define(Payment::class, function (Faker $faker) {
                return [
                    'invoice_id' => rand(1, 50), // pieņemsim, ka ir 50 rēķini, pielāgojiet pēc saviem datiem
                    'amount' => $faker->randomFloat(2, 10, 100),
                    // citi atribūti
                ];
            })
        ];
    }
}


