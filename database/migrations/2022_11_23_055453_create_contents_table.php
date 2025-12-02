<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content_category_id')->nullable();
            $table->string('name');
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('file')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('slug');
            $table->enum('status', ['active', 'inactive']);
            $table->tinyInteger('is_deletable')->index()->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('contents');
    }
};
