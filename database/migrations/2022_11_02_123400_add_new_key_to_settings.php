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
        Setting::create(['key' => 'company_name', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'company_description', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'fav_icon', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'logo_dark', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'logo_light', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'facebook_url', 'value' => '', 'category' => 'social']);
        Setting::create(['key' => 'twitter_url', 'value' => '', 'category' => 'social']);
        Setting::create(['key' => 'youtube_url', 'value' => '', 'category' => 'social']);
        Setting::create(['key' => 'instagram_url', 'value' => '', 'category' => 'social']);
        Setting::create(['key' => 'meta_tags', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'address', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'email', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'phone', 'value' => '', 'category' => 'store']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('key', 'company_name')->delete();
        Setting::where('key', 'company_description')->delete();
        Setting::where('key', 'fav_icon')->delete();
        Setting::where('key', 'logo_dark')->delete();
        Setting::where('key', 'logo_light')->delete();
        Setting::where('key', 'facebook_url')->delete();
        Setting::where('key', 'twitter_url')->delete();
        Setting::where('key', 'youtube_url')->delete();
        Setting::where('key', 'instagram_url')->delete();
        Setting::where('key', 'meta_tags')->delete();
        Setting::where('key', 'address')->delete();
        Setting::where('key', 'email')->delete();
        Setting::where('key', 'phone')->delete();
    }
};
