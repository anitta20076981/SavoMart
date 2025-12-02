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
        Permission::create(['name' => 'customer_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'customer_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'customer_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'customer_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'customer_read')->delete();
        Permission::where('name', 'customer_create')->delete();
        Permission::where('name', 'customer_update')->delete();
        Permission::where('name', 'customer_delete')->delete();
    }
};
