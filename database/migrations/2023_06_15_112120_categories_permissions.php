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
        Permission::create(['name' => 'categories_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'categories_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'categories_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'categories_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'categories_read')->delete();
        Permission::where('name', 'categories_create')->delete();
        Permission::where('name', 'categories_update')->delete();
        Permission::where('name', 'categories_delete')->delete();
    }
};
