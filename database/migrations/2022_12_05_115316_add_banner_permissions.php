<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create(['name' => 'banner_read']);
        Permission::create(['name' => 'banner_create']);
        Permission::create(['name' => 'banner_update']);
        Permission::create(['name' => 'banner_delete']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'banner_read')->delete();
        Permission::where('name', 'banner_create')->delete();
        Permission::where('name', 'banner_update')->delete();
        Permission::where('name', 'banner_delete')->delete();
    }
};
