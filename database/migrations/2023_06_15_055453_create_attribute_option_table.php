<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('attribute_options', function (Blueprint $table) {
            $table->integer('id', true)->index();
            $table->integer('attribute_id')->unsigned()->nullable()->index();
            $table->string('swatch')->nullable();
            $table->string('label')->nullable();
            $table->string('value')->nullable();
            $table->integer('sort_order')->nullable()->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('attribute_options');
    }
};
