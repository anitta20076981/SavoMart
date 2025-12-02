<?php

use App\Models\Timezone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timezones', function (Blueprint $table) {
            $table->id();
            $table->string('timezone')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
        Timezone::truncate();
        $timezones = json_decode(file_get_contents(resource_path('json/timezones.json')));

        foreach ($timezones as $timezone) {
            if (isset($timezone->name) && $timezone->timezone) {
                Timezone::create([
                    'timezone' => $timezone->timezone,
                    'name' => $timezone->name,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timezones');
    }
};
