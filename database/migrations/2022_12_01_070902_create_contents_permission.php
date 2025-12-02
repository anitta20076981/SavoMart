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
        Permission::create(['name' => 'contents_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'contents_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'contents_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'contents_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'contents_read')->delete();
        Permission::where('name', 'contents_create')->delete();
        Permission::where('name', 'contents_update')->delete();
        Permission::where('name', 'contents_delete')->delete();
    }
};
