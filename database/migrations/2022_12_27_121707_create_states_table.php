<?php

use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('state_code')->nullable();
            $table->string('type')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
        State::truncate();
        $states = json_decode(file_get_contents(resource_path('json/states.json')));

        foreach ($states as $state) {
            State::create([
                'name' => $state->name,
                'country_id' => $state->country_id,
                'state_code' => $state->state_code,
                'type' => $state->type,
                'latitude' => $state->latitude,
                'longitude' => $state->longitude,
                'status' => 1,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
};
