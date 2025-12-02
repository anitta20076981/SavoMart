<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->nullable()->index();
            $table->text('image_path')->nullable();
            $table->enum('image_role', ['BASE', 'SMALL', 'THUMBNAIL', 'SWATCH'])->nullable();
            $table->string('alt_text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('product_images');
    }
};
