<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //savoMart Admin
        Role::where('name', 'super_privilege')->delete();
        $savoMartAdminRole = Role::create(['name' => 'super_privilege', 'guard_name' => 'admin']);

        User::where('email', 'savomart@gmail.com')->forceDelete();
        $savoMartAdminUser = new User();
        $savoMartAdminUser->status = true;
        $savoMartAdminUser->name = 'savoMart Admin';
        $savoMartAdminUser->email = 'savomart@gmail.com';
        $savoMartAdminUser->password = Hash::make('SavoMart@01');
        $savoMartAdminUser->save();
        $savoMartAdminUser->assignRole($savoMartAdminRole);

        // savomart Admin
        Role::where('name', 'savomart_admin')->delete();
        $savoMartAdminRole = Role::create(['name' => 'savomart_admin', 'guard_name' => 'admin']);

        User::where('email', 'admin@savomart.com')->forceDelete();
        $savoMartAdminUser = new User();
        $savoMartAdminUser->name = 'SavoMart Admin';
        $savoMartAdminUser->status = true;
        $savoMartAdminUser->email = 'admin@savomart.com';
        $savoMartAdminUser->password = Hash::make('SavoMart@01');
        $savoMartAdminUser->deleted_at = null;
        $savoMartAdminUser->save();
        $savoMartAdminUser->assignRole($savoMartAdminRole);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::where('name', 'super_privilege')->delete();
        Role::where('name', 'savomart_admin')->delete();
        User::where('email', 'savomart@gmail.com')->forceDelete();
        User::where('email', 'admin@savomart.com')->forceDelete();
    }
};