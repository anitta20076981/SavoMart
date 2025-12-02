<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_id')->index();
            $table->integer('product_id')->index();
            $table->decimal('quantity', 8, 2)->index()->default('0');
            $table->decimal('unit_price')->index()->default('0');
            $table->decimal('total_price')->index()->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('cart_items');
    }
};
