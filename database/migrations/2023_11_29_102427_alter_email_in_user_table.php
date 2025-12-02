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
        Schema::table('users', function (Blueprint $table) {
            // Make the 'email' column nullable
            $table->string('email')->nullable()->change();

            // Add a new 'phone' column
            $table->string('phone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes in the 'down' method
            $table->string('email')->nullable(false)->change();
            $table->dropColumn('phone');
        });
    }
};
