<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(100)->create();

        $this->call([
            BannerSeeder::class,
        ]);
        \App\Models\Brand::factory(10)->create();
        \App\Models\Page::factory(10)->create();
        // \App\Models\ShipmentMethod::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\Product::factory(10)->create();

        $this->call([
            PaymentMethodSeeder::class,
        ]);

        $this->call([
            CustomerSeeder::class,
        ]);
    }
}
