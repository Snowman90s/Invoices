<?php

namespace Modules\Invoices\Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\Invoices\App\Models\Invoice;
use Modules\Invoices\App\Models\Payment;

class InvoicesPaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            $numPayments = rand(1, 4);

            for ($i = 0; $i < $numPayments; $i++) {
                Payment::create([
                    'client_id' => $invoice->client_id,
                    'invoice_id' => $invoice->id,
                    'type' => $faker->randomElement(['cash', 'credit', 'debit']),
                    'date' => $faker->date(),
                    'amount' => $faker->randomFloat(2, 1, 1000),
                    'admin_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
