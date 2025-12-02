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
        Permission::create(['name' => 'attribute_set_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'attribute_set_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'attribute_set_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'attribute_set_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'attribute_set_read')->delete();
        Permission::where('name', 'attribute_set_create')->delete();
        Permission::where('name', 'attribute_set_update')->delete();
        Permission::where('name', 'attribute_set_delete')->delete();
    }
};
