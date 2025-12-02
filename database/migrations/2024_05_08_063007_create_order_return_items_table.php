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
        Schema::create('order_return_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_return_id')->index();
            $table->integer('order_item_id')->index();
            $table->integer('product_id')->index();
            $table->decimal('price', 8, 2)->index()->default('0');
            $table->decimal('quantity', 8, 2)->index()->default('0');
            $table->decimal('total', 8, 2)->index()->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_return_items');
    }
};
