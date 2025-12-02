<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('categories_attribute_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('categories_set_id')->nullable()->index();
            $table->integer('attributes_id')->nullable()->index();
        });
    }

    public function down()
    {
        Schema::drop('categories_attribute_sets');
    }
};
