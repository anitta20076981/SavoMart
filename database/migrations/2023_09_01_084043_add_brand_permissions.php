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
        Permission::create(['name' => 'brand_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'brand_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'brand_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'brand_delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'catalog_rule_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'catalog_rule_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'catalog_rule_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'catalog_rule_delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'pages_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'pages_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'pages_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'pages_delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'payment_method_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'payment_method_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'payment_method_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'payment_method_delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'quote_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'quote_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'quote_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'quote_delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'quote_request_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'quote_request_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'quote_request_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'quote_request_delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'shipment_method_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'shipment_method_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'shipment_method_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'shipment_method_delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'tax_read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'tax_create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'tax_update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'tax_delete', 'guard_name' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'brand_read')->delete();
        Permission::where('name', 'brand_create')->delete();
        Permission::where('name', 'brand_update')->delete();
        Permission::where('name', 'brand_delete')->delete();

        Permission::where('name', 'catalog_rule_read')->delete();
        Permission::where('name', 'catalog_rule_create')->delete();
        Permission::where('name', 'catalog_rule_update')->delete();
        Permission::where('name', 'catalog_rule_delete')->delete();

        Permission::where('name', 'pages_read')->delete();
        Permission::where('name', 'pages_create')->delete();
        Permission::where('name', 'pages_update')->delete();
        Permission::where('name', 'pages_delete')->delete();

        Permission::where('name', 'payment_method_read')->delete();
        Permission::where('name', 'payment_method_create')->delete();
        Permission::where('name', 'payment_method_update')->delete();
        Permission::where('name', 'payment_method_delete')->delete();

        Permission::where('name', 'quote_read')->delete();
        Permission::where('name', 'quote_create')->delete();
        Permission::where('name', 'quote_update')->delete();
        Permission::where('name', 'quote_delete')->delete();

        Permission::where('name', 'quote_request_read')->delete();
        Permission::where('name', 'quote_request_create')->delete();
        Permission::where('name', 'quote_request_update')->delete();
        Permission::where('name', 'quote_request_delete')->delete();

        Permission::where('name', 'shipment_method_read')->delete();
        Permission::where('name', 'shipment_method_create')->delete();
        Permission::where('name', 'shipment_method_update')->delete();
        Permission::where('name', 'shipment_method_delete')->delete();

        Permission::where('name', 'tax_read')->delete();
        Permission::where('name', 'tax_create')->delete();
        Permission::where('name', 'tax_update')->delete();
        Permission::where('name', 'tax_delete')->delete();
    }
};