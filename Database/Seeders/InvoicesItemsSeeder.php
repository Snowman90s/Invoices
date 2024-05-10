<?php

namespace Modules\Invoices\Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\Invoices\App\Models\Invoice;
use Modules\Invoices\App\Models\InvoiceItem;

class InvoicesItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $faker = Faker::create();

        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            $numItems = rand(2, 4);

            for ($i = 0; $i < $numItems; $i++) {
                $price = $faker->randomFloat(2, 1, 1000);
                $discount = $faker->randomFloat(2, 0, $price);
                $quantity = $faker->randomDigitNotNull;
                $description = $faker->sentence;
                $price_total = $price * $quantity;
                $discount_total = $discount * $quantity;
                $pre_total = $price_total;
                $in_total = $price_total - $discount_total;

                InvoiceItem::create([
                    'name' => $faker->word,
                    'invoice_id' => $invoice->id,
                    'client_id' => $invoice->client_id,
                    'service_id' => null,
                    'plan_id' => null,
                    'membership_id' => null,
                    'type' => 'product',
                    'price' => $price,
                    'discount' => $discount,
                    'discount_type' => null,
                    'quantity' => $quantity,
                    'vat' => null,
                    'description' => $description,
                    'price_total' => $price_total,
                    'discount_total' => $discount_total,
                    'pre_total' => $pre_total,
                    'in_total' => $in_total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
