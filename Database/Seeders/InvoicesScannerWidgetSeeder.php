<?php

namespace Modules\Invoices\Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\Scanner\App\Models\ScannerWidget;

class InvoicesScannerWidgetSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $codePatterns = [
            '[A-Za-z]{2}-[0-9]{4}-[0-9A-Za-z]{2,13}',
            '[A-Za-z]{2}-[0-9]{4}-[0-9A-Za-z]{2,13}-V2'
        ];

        for ($i = 0; $i < 2; $i++) {
            $scannerWidget = new ScannerWidget();
            $scannerWidget->title = $faker->word;
            $scannerWidget->description = $faker->sentence;
            $scannerWidget->module = 'Invoices';
            $scannerWidget->widget_name = 'Invoice';
            $scannerWidget->access_flag = 'invoices.access_invoices';
            $scannerWidget->code_pattern = $codePatterns[$i];
            $scannerWidget->is_active = 1;
            $scannerWidget->save();
        }
    }
}
