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
        Permission::create(['name' => 'application_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'application_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'application_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'application_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'application_read')->delete();
        Permission::where('name', 'application_create')->delete();
        Permission::where('name', 'application_update')->delete();
        Permission::where('name', 'application_delete')->delete();
    }
};
