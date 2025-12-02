<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function createUser($details);

    public function updateUser(User $user, $details);

    public function getUser($userId);

    public function getAllUsers();

    public function deleteUser($userId);

    public function createRole($data, $permission);

    public function updateRole($roleId, $data, $permissions);

    public function roleStatusUpdate($roleId);

    public function userStatusUpdate($userId);

    public function getRole($roleId);

    public function getAllRoles($data);

    public function getAllRole();

    public function deleteRole($roleId);

    public function roleUsers($id);

    public function getPermissionTemplate();

    public function searchRole($keyword);

    public function getAllUsersexcepetSuperAdmin($data);

    public function registerDevice(array $input);
}
