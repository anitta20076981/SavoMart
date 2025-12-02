<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthenticationLogTable extends Migration
{
    public function up()
    {
        Schema::create('authentication_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('authenticatable_id');
            $table->string('authenticatable_type');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->boolean('login_successful');
            $table->timestamp('login_at');
            // Add more columns as needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('authentication_log');
    }
}
