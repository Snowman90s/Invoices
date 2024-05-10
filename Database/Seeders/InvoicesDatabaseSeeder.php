<?php

namespace Modules\Invoices\Database\Seeders;

use Illuminate\Database\Seeder;

class InvoicesDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InvoicesSeeder::class,
            InvoicesScannerWidgetSeeder::class,
            InvoicesItemsSeeder::class,
            InvoicesPaymentsSeeder::class,
        ]);
    }
}
