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
        Schema::table('order', function (Blueprint $table) {
            $table->enum('invoice_status', ['pending', 'complete'])->default('pending')->after('status');
            $table->enum('shipment_status', ['pending', 'complete'])->default('pending')->after('invoice_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('invoice_status');
            $table->dropColumn('shipment_status');
        });
    }
};
