<?php

namespace Database\Seeders;

use App\Models\BannerSection;
use Illuminate\Database\Seeder;

class BannerSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BannerSection::create([
            'name' => 'Header Section',
            'status' => 'active',
        ]);
    }
}
