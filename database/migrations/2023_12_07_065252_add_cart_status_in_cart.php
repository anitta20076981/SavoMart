<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->enum('cart_status', ['in_cart', 'in_order',])->default('in_cart');
        });
    }

    public function down()
    {
        // Revert the changes if needed
        Schema::table('cart', function (Blueprint $table) {
           $table->dropColumn('cart_status');
        });
    }
};