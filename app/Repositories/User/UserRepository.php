<?php

namespace App\Repositories\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserRepositoryInterface
{
    public function createUser($details)
    {
        $user = new User();

        foreach ($details as $key => $value) {
            if (!empty($value)) {
                $user->$key = $value;
            }
        }
        $user->save();

        return $user;
    }

    public function updateUser($userId, $details)
    {
        $user = User::find($userId);

        foreach ($details as $key => $value) {
            $user->$key = $value;
        }
        $user->save();

        return $user;
    }

    public function getUser($userId)
    {
        return User::find($userId);
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function deleteUser($userId)
    {
        return User::find($userId)->delete();
    }

    public function createRole($data, $permissions)
    {
        $role = Role::create($data);
        $role->syncPermissions($permissions);

        return $role;
    }

    public function updateRole($roleId, $data, $permissions)
    {
        $role = Role::find($roleId);
        $role->name = $data['name'];
        $role->save();
        $role->syncPermissions($permissions);

        return $role;
    }

    public function getRole($roleId)
    {
        return Role::find($roleId);
    }

    public function getAllRoles($data)
    {
        return Role::select(app(Role::class)->getTable() . '.*')
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status'])) {
                    $query->where('status', '=', $data['status']);
                }
            });
    }

    public function getAllRole()
    {
        return Role::all();
    }

    public function deleteRole($roleId)
    {
        return Role::find($roleId)->delete();
    }

    public function userStatusUpdate($userId)
    {
        $user = User::find($userId);
        $user->status = $user->status ? 0 : 1;
        $user->save();

        return $user;
    }

    public function roleStatusUpdate($roleId)
    {
        $role = Role::find($roleId);
        $role->status = $role->status ? 0 : 1;
        $role->save();

        return $role;
    }

    public function roleUsers($id)
    {
        $role = Role::find($id);

        return $role->users;
    }

    public function getPermissionTemplate()
    {
        return [
            ['label' => 'Attribute Sets', 'key' => 'attribute_set'],
            ['label' => 'Banner', 'key' => 'banner'],
            ['label' => 'Brand', 'key' => 'brand'],
            ['label' => 'Catalog Rule', 'key' => 'catalog_rule'],
            ['label' => 'Category', 'key' => 'categories'],
            ['label' => 'Pages', 'key' => 'pages'],
            ['label' => 'Payment Method', 'key' => 'payment_method'],
            ['label' => 'Quote', 'key' => 'quote'],
            ['label' => 'Quote Request', 'key' => 'quote_request'],
            ['label' => 'Roles and permission', 'key' => 'role'],
            ['label' => 'Settings', 'key' => 'settings'],
            ['label' => 'Products', 'key' => 'products'],
            ['label' => 'Product Attributes', 'key' => 'attribute'],
            ['label' => 'Shipment Method', 'key' => 'shipment_method'],
            ['label' => 'Tax', 'key' => 'tax'],
            ['label' => 'Users', 'key' => 'user'],

        ];
    }

    public function searchRole($keyword)
    {
        $role = Role::where('status', 1)->where('name', 'like', "%{$keyword}%");

        if (auth()->user()->roles_array) {
            $role = $role->whereIn('id', auth()->user()->roles_array);
        }

        return $role->orderBy('name', 'asc')->paginate(30, ['*'], 'page', request()->get('page'));
    }

    public function getAllUsersexcepetSuperAdmin($data)
    {
        $users = User::select(app(User::class)->getTable() . '.*');

        if (isset($data['status']) && $data['status'] != '') {
            $users->where('status', $data['status']);
        }
        // if (isset($data['role_id']) && $data['role_id'] != "") {
        //     $users->whereHas('roles', function ($query) use ($data) {
        //         return $query->where('role_id', $data['role_id']);
        //     });
        // }
        // Exclude users with email 'web@gmail.com'
        $users->where('email', '!=', 'web@gmail.com');

        $users = $users->orderBy('created_at', 'desc');

        return $users->orderBy('id', 'Desc');
    }

    public function deleteTokens(array $tokens = [])
    {
        return User::whereIn('fcm_token', $tokens)->update(['fcm_token' => '']);
    }

    public function registerDevice(array $input)
    {
        $fcmId = implode('-:-', [$input['device_type'], $input['fcm_token']]);
        $user = User::find(auth()->user()->id);
        $user->fcm_token = $fcmId;

        if ($user->save()) {
            return $user;
        }

        return false;
    }
}
