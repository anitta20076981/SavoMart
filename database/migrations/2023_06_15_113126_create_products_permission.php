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
        Permission::create(['name' => 'products_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'products_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'products_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'products_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'products_read')->delete();
        Permission::where('name', 'products_create')->delete();
        Permission::where('name', 'products_update')->delete();
        Permission::where('name', 'products_delete')->delete();
    }
};
