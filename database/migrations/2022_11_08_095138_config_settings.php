<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::create(['key' => 'timezone', 'value' => 'Asia/Kolkata', 'category' => 'config']);
        Setting::create(['key' => 'date_only_display', 'value' => 'd-m-Y', 'category' => 'config']);
        Setting::create(['key' => 'date_only_js', 'value' => 'd-m-Y', 'category' => 'config']);
        Setting::create(['key' => 'date_only_store', 'value' => 'Y-m-d', 'category' => 'config']);
        Setting::create(['key' => 'country_id', 'value' => '105', 'category' => 'config']);
        Setting::create(['key' => 'currency_id', 'value' => '4', 'category' => 'config']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('key', 'timezone')->delete();
        Setting::where('key', 'date_only_display')->delete();
        Setting::where('key', 'date_only_js')->delete();
        Setting::where('key', 'date_only_store')->delete();
        Setting::where('key', 'country_id')->delete();
        Setting::where('key', 'currency_id')->delete();
    }
};
