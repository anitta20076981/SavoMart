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
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shipment_no');
            $table->integer('order_id')->index()->default('0');
            $table->integer('shipment_method_id')->index()->default('0');
            $table->enum('status', ['pending', 'accepted', 'picked', 'shipped', 'delivered'])->index();
            $table->string('tracking_no')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipments');
    }
};
