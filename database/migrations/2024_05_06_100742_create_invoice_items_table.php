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
        Schema::create('order_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id');
            $table->integer('order_item_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->double('tax_amount');
            $table->double('total_amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_invoice_items');
    }
};
