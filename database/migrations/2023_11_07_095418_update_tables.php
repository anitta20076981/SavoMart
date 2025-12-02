<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_ar')->after('name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_ar')->after('name')->nullable();
            $table->longText('description_ar')->after('description')->nullable();
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->string('name_ar')->after('name');
        });

        Schema::table('attribute_options', function (Blueprint $table) {
            $table->string('label_ar')->after('label')->nullable();
            $table->string('value_ar')->after('value')->nullable();
        });

        Setting::create(['key' => 'company_name_ar', 'value' => '', 'category' => 'store']);
        Setting::create(['key' => 'company_description_ar', 'value' => '', 'category' => 'store']);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function ($table) {
            $table->dropColumn('name_ar'); 
        });

        Schema::table('products', function ($table) {
            $table->dropColumn('name_ar','description_ar'); 
        });

        Schema::table('attributes', function ($table) {
            $table->dropColumn('name_ar'); 
        });

        Schema::table('attribute_options', function ($table) {
            $table->dropColumn('label_ar','value_ar'); 
        });
        
        Setting::where('key', 'company_name_ar')->delete();
        Setting::where('key', 'company_description_ar')->delete();

    }
};