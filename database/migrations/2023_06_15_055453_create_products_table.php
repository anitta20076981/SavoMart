<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('quantity')->nullable();
            $table->enum('status', ['active', 'inactive']);

            $table->enum('type', ['configurable_product', 'virtual_product', 'simple_product'])->default('simple_product'); 
            $table->integer('attribute_set_id');
            $table->integer('parent_id')->nullable();   
            $table->longText('description')->nullable();
            $table->enum('stock_status', ['outofstock', 'lowstock', 'instock']);
            
            $table->string('price')->nullable()->default('0');
            $table->decimal('special_price', 8, 2)->index()->default('0');
            $table->datetime('special_price_to')->nullable();
            $table->datetime('special_price_from')->nullable();

            $table->integer('discount_id')->nullable();
            $table->decimal('discount_percentage', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('products');
    }
};