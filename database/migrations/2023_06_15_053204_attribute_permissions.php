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
        Permission::create(['name' => 'attribute_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'attribute_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'attribute_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'attribute_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'attribute_read')->delete();
        Permission::where('name', 'attribute_create')->delete();
        Permission::where('name', 'attribute_update')->delete();
        Permission::where('name', 'attribute_delete')->delete();
    }
};
