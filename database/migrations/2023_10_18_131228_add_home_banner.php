<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Banner;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Banner::create(['name' => 'Home Banner', 'slug' => 'home-banner', 'status' => 'active', 'banner_section_id' => 1, 'is_deletable' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Banner::where('slug', 'home-banner')->delete();
    }
};
