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
        DB::statement("ALTER TABLE `attributes` CHANGE `input_type` `input_type` ENUM('dropdown', 'textswatch', 'visualswatch','textfield','textarea','texteditor','date','datetime','yesno','price') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `attributes` CHANGE `input_type` `input_type` ENUM('dropdown', 'extswatch', 'visualswatch') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
    }
};
