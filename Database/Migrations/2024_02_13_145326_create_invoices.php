<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('lang')->nullable();
            $table->string('template_id')->nullable()->default('default');
            $table->string('public_token')->nullable();


            // from
            $table->integer('master_detail_id')->nullable()->default(1);

            // to 
            $table->integer('client_id')->nullable();
            $table->integer('client_detail_id')->nullable();

            $table->string('status', 30)->nullable()->default('draft');

            // financial data
            $table->integer('discount_main')->nullable();
            $table->integer('vat')->nullable()->default(0);
            $table->decimal('price_total', 12, 2)->default(0)->nullable();
            $table->decimal('discount_total', 12, 2)->nullable();
            $table->decimal('pre_total', 12, 2)->default(0)->nullable();
            $table->decimal('vat_total', 12, 2)->default(0)->nullable();
            $table->decimal('in_total', 12, 2)->default(0)->nullable();
            $table->decimal('paid', 12, 2)->default(0)->nullable();
            $table->decimal('refunded', 12, 2)->default(0)->nullable();
            $table->string('currency')->nullable();

            $table->boolean('is_emailed')->nullable();
            $table->boolean('is_paid')->nullable();
            $table->boolean('is_complete')->nullable()->default(false);

            $table->text('info_description')->nullable();
            $table->integer('item_count')->nullable();

            $table->date('issued_at')->nullable();
            $table->date('due_at')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
