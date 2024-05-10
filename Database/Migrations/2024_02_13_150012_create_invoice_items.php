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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('plan_id')->nullable();
            $table->integer('membership_id')->nullable();
            $table->string('type')->nullable()->default('product');
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->integer('vat')->default(21);
            $table->string('description')->nullable();
            $table->decimal('price_total', 10, 2)->nullable();
            $table->decimal('discount_total', 10, 2)->nullable();
            $table->decimal('pre_total', 10, 2)->nullable();
            $table->decimal('in_total', 10, 2)->nullable();;
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
