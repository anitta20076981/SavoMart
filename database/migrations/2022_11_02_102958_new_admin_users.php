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

        User::where('email', 'web@gmail.com')->forceDelete();
        $savoMartAdminUser = new User();
        $savoMartAdminUser->status = true;
        $savoMartAdminUser->name = 'savoMart Admin';
        $savoMartAdminUser->email = 'web@gmail.com';
        $savoMartAdminUser->password = Hash::make('Grocery@01');
        $savoMartAdminUser->save();
        $savoMartAdminUser->assignRole($savoMartAdminRole);

        // Grocery Admin
        Role::where('name', 'grocery_admin')->delete();
        $groceryAdminRole = Role::create(['name' => 'grocery_admin', 'guard_name' => 'admin']);

        User::where('email', 'admin@grocery.com')->forceDelete();
        $groceryAdminUser = new User();
        $groceryAdminUser->name = 'Grocery Admin';
        $groceryAdminUser->status = true;
        $groceryAdminUser->email = 'admin@grocery.com';
        $groceryAdminUser->password = Hash::make('Grocery@01');
        $groceryAdminUser->deleted_at = null;
        $groceryAdminUser->save();
        $groceryAdminUser->assignRole($groceryAdminRole);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::where('name', 'super_privilege')->delete();
        Role::where('name', 'grocery_admin')->delete();
        User::where('email', 'web@gmail.com')->forceDelete();
        User::where('email', 'admin@grocery.com')->forceDelete();
    }
};