<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('attribute_option_value', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('attribute_option_id')->index();
            $table->string('value', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('attribute_option_value');
    }
};
