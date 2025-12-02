<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->index();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->longText('details')->nullable();
            $table->longText('street_address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city', 255)->nullable();
            $table->string('postel_code')->nullable();
            $table->string('contact')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_addresses');
    }
};
