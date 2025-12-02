<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `attribute_sets` CHANGE `status` `status` ENUM('active', 'inactive') default 'active';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_sets', function ($table) {
            $table->string('status')->after('name')->nullable()->change();
        });
    }
};
