<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('product_type')->nullable()->default('simple');
            $table->integer('attribute_id');
            $table->longText('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('product_attributes');
    }
};