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
        Permission::create(['name' => 'data_transfer_read', 'guard_name' => 'admin']);

        // Export
        Permission::create(['name' => 'data_export_read',   'guard_name' => 'admin']);
        Permission::create(['name' => 'data_export_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'data_export_delete', 'guard_name' => 'admin']);

        // Import
        Permission::create(['name' => 'data_import_read',   'guard_name' => 'admin']);
        Permission::create(['name' => 'data_import_create', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::where('name', 'data_transfer_read')->delete();
        
        // Export
        Permission::where('name', 'data_export_read')->delete();
        
        Permission::where('name', 'data_export_create')->delete();
        Permission::where('name', 'data_export_delete')->delete();

        // Import
        Permission::where('name', 'data_import_read')->delete();
        Permission::where('name', 'data_import_create')->delete();
    }
};