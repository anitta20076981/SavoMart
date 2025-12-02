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
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable()->index();
            $table->string('order_no');
            $table->integer('cart_id')->nullable()->index();
            $table->integer('address_id')->nullable()->index();
            $table->integer('payment_type')->nullable()->index()->comment('1 => cash on delivery','0 => pay on order');
            $table->date('date')->nullable();
            $table->integer('total_items')->nullable()->index();
            $table->decimal('grand_total')->index()->default('0');
            $table->enum('status', ['pending', 'dispatched','rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};