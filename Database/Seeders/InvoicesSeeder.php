<?php

namespace Modules\Invoices\Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Modules\Invoices\App\Models\Invoice;

class InvoicesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $currentYear = date('Y');

        $statuses = Invoice::statusTypes();

        for ($i = 0; $i < 10; $i++) {
            $name = $faker->name;
            $initials = Str::upper(substr($name, 0, 2));
            $uniqueId = strtoupper(uniqid());
            $generatedName = "$initials-$currentYear-$uniqueId";

            $priceTotal = $faker->randomFloat(2, 10, 1000);
            $paid = $faker->randomFloat(2, 0, $priceTotal);

            $invoice = new Invoice();
            $invoice->name = $generatedName;
            $invoice->order_id = $faker->numberBetween(1000, 9999);
            $invoice->lang = $faker->languageCode;
            $invoice->template_id = $faker->word;
            $invoice->public_token = $faker->uuid;
            $invoice->client_id = $faker->numberBetween(1, 100);
            $invoice->status = $faker->randomElement(array_keys($statuses));
            $invoice->discount_main = $faker->numberBetween(0, 100);
            $invoice->vat = $faker->numberBetween(0, 20);
            $invoice->price_total = $priceTotal;
            $invoice->currency = $faker->currencyCode;
            $invoice->is_emailed = $faker->boolean;
            $invoice->is_paid = $faker->boolean;
            $invoice->is_complete = $faker->boolean;
            $invoice->info_description = $faker->sentence;
            $invoice->item_count = $faker->numberBetween(1, 10);
            $invoice->issued_at = $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d');
            $invoice->due_at = $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d');
            $invoice->paid = $paid;
            $invoice->save();
        }
    }
}
