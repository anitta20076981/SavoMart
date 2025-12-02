<?php

use App\Models\BannerSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        BannerSection::create(['name' => 'Home Banner Section', 'price' => 100, 'status' => 'active']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        BannerSection::truncate();
    }
};
