<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('banner_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('banner_id')->index();
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->string('file');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('banner_item');
    }
};
