<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleAddRequest;
use App\Http\Requests\Admin\Role\RoleCreateRequest;
use App\Http\Requests\Admin\Role\RoleDeleteRequest;
use App\Http\Requests\Admin\Role\RoleEditRequest;
use App\Http\Requests\Admin\Role\RoleListRequest;
use App\Http\Requests\Admin\Role\RoleStatusChangeRequest;
use App\Http\Requests\Admin\Role\RoleUpdateRequest;
use App\Http\Requests\Admin\User\UserListDataRequest;
use App\Repositories\User\UserRepositoryInterface as UserRepository;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    public function listRoles(RoleListRequest $request, UserRepository $userRepo)
    {
        $breadcrumbs = [
            ['link' => 'dashboard', 'name' => 'Dashboard'],
            ['name' => 'Roles'],
        ];
        $roles = $userRepo->getAllRole();

        return view('admin.roles.listRoles', compact('breadcrumbs', 'roles'));
    }

    public function addRole(RoleAddRequest $request, UserRepository $userRepo)
    {
        $permissions = $userRepo->getPermissionTemplate();
        $responce['html'] = (string) view('admin.roles.addRole', compact('permissions'));
        $responce['scripts'][] = (string) mix('js/admin/roles/addRole.js');

        return $responce;
    }

    public function createRole(RoleCreateRequest $request, UserRepository $userRepo)
    {
        $data = [
            'name' => $request->name,
        ];
        $permissions = $request->permissions;
        $userRepo->createRole($data, $permissions);

        return redirect()->route('admin_role_list')->with('success', 'Role added successfully');
    }

    public function editRole(RoleEditRequest $request, UserRepository $userRepo)
    {
        $role = $userRepo->getRole($request->id);
        $activePermissions = $role->getPermissionNames();
        $permissions = $userRepo->getPermissionTemplate();
        $responce['html'] = (string) view('admin.roles.editRole', compact('permissions', 'activePermissions', 'role'));
        $responce['scripts'][] = (string) mix('js/admin/roles/editRole.js');

        return $responce;
    }

    public function viewRole(RoleEditRequest $request, UserRepository $userRepo)
    {
        $breadcrumbs = [
            ['link' => 'dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_role_list', 'name' => 'Roles', 'permission' => 'role_read'],
            ['name' => 'View Role'],
        ];
        $role = $userRepo->getRole($request->id);

        return view('admin.roles.viewRole', compact('role', 'breadcrumbs'));
    }

    public function rolesUsersListData(UserListDataRequest $request, UserRepository $userRepo)
    {
        $users = $userRepo->getAllUsersexcepetSuperAdmin($request->all());
        $dataTableJSON = DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('name', function ($user) {
                $data['url'] = request()->user()->can('user_view') ? route('admin_user_edit', ['id' => $user->id]) : '';
                $data['text'] = $user->name;

                return view('admin.elements.listLink', compact('data'));
            })
            ->addColumn('status', function ($user) {
                return view('admin.elements.listStatus')->with('data', $user);
            })
            ->addColumn('action', function ($user) use ($request) {
                $data['edit_url'] = request()->user()->can('user_update') ? route('admin_user_edit', ['id' => $user->id]) : '';
                $data['delete_url'] = request()->user()->can('user_delete') ? route('admin_user_delete', ['id' => $user->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function updateRole(RoleUpdateRequest $request, UserRepository $userRepo)
    {
        $data = [
            'name' => $request->name,
        ];
        $permissions = $request->permissions;
        $userRepo->updateRole($request->id, $data, $permissions);

        return redirect()->back()->with('success', 'Role updated successfully');
    }

    public function statusChange(RoleStatusChangeRequest $request, UserRepository $userRepo)
    {
        if ($userRepo->roleStatusUpdate($request->id)) {
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }

    public function deleteRole(RoleDeleteRequest $request, UserRepository $userRepo)
    {
        $roleUsers = $userRepo->roleUsers($request->id);
        $status = false;

        if (empty($roleUsers) || $roleUsers->count() <= 0) {
            $status = $userRepo->deleteRole($request->id);
        }

        if ($request->expectsJson()) {
            if ($status) {
                return response()->json(['status' => 1, 'message' => 'Role deleted successfully']);
            }

            return response()->json(['status' => 0, 'message' => 'Role deleted failed']);
        } else {
            if ($status) {
                return redirect()
                    ->route('role_list')
                    ->with('success', 'Role deleted successfully');
            } else {
                return redirect()
                    ->route('role_list')
                    ->with('error', 'Role deleted failed');
            }
        }
    }
}