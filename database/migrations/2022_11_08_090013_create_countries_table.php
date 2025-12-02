<?php

use App\Models\Country;
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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('iso2')->nullable();
            $table->string('iso3')->nullable();
            $table->string('short_name')->nullable();
            $table->string('long_name')->nullable();
            $table->boolean('status')->default(false);
            $table->string('country_code')->nullable();
            $table->timestamps();
        });
        Country::truncate();
        $countries = json_decode(file_get_contents(resource_path('json/countries.json')));

        foreach ($countries as $country) {
            if (isset($country->short_name) && $country->short_name) {
                Country::create([
                    'iso2' => $country->iso2,
                    'iso3' => $country->iso3,
                    'short_name' => $country->short_name,
                    'long_name' => $country->long_name,
                    'status' => 1,
                    'country_code' => $country->country_code,
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
        Schema::dropIfExists('countries');
    }
};
