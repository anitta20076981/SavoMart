<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->longText('description');
            $table->string('logo')->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
       // Schema::drop('applications');
    }
};
