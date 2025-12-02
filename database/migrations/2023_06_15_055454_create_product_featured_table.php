<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('product_featured', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->index();
            $table->date('from')->index();
            $table->date('to')->index(); 
            $table->enum('status', ['active', 'inactive'])->index(); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('product_featured');
    }
};
