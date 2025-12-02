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
        Schema::create('order_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->index();
            $table->integer('order_item_id')->index();
            $table->string('reason')->nullable()->index();
            $table->string('location')->nullable()->index();
            $table->enum('status', ['pending', 'confirmed', 'completed'])->default('pending');
            $table->longText('reject_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_returns');
    }
};
