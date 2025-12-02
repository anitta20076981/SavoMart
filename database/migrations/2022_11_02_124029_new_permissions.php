<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = ['user', 'role', 'settings', 'banner', 'cms'];

        foreach ($permissions as  $value) {
            Permission::create(['name' => $value . '_read', 'guard_name' => 'admin']);
            Permission::create(['name' => $value . '_create', 'guard_name' => 'admin']);
            Permission::create(['name' => $value . '_update', 'guard_name' => 'admin']);
            Permission::create(['name' => $value . '_delete', 'guard_name' => 'admin']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permissions = ['user', 'role', 'settings', 'banner', 'cms'];

        foreach ($permissions as $value) {
            Permission::where('name', $value . '_read')->delete();
            Permission::where('name', $value . '_create')->delete();
            Permission::where('name', $value . '_update')->delete();
            Permission::where('name', $value . '_delete')->delete();
        }
    }
};