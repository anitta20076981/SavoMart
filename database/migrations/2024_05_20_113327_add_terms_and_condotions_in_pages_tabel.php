<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Page;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Page::create(['name' => 'Terms And Conditions', 'slug' => 'terms-and-conditions', 'is_deletable' => '0', 'status' => 'active']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Page::truncate();
    }
};
