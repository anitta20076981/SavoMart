<?php

use App\Models\Currency;
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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable();
            $table->string('name_plural', 70)->nullable();
            $table->string('code', 4)->nullable();
            $table->string('symbol', 6)->nullable();
            $table->tinyInteger('decimals')->default(0);
            $table->timestamps();
        });
        Currency::truncate();
        $currencies = json_decode(file_get_contents(resource_path('json/currencies.json')));

        foreach ($currencies as $currency) {
            if (isset($currency->name) && $currency->name) {
                Currency::create([
                    'name' => $currency->name,
                    'name_plural' => $currency->namePlural,
                    'code' => $currency->code,
                    'symbol' => $currency->symbol,
                    'decimals' => $currency->decimalDigits,
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
        Schema::dropIfExists('currencies');
    }
};
