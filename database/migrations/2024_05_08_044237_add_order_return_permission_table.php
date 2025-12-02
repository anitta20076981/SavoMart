<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Permission::create(['name' => 'order_returns_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'order_returns_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'order_returns_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'order_returns_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::where('name', 'order_returns_read')->delete();
        Permission::where('name', 'order_returns_create')->delete();
        Permission::where('name', 'order_returns_update')->delete();
        Permission::where('name', 'order_returns_delete')->delete();
    }
};
