<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserAddRequest;
use App\Http\Requests\Admin\User\UserCreateRequest;
use App\Http\Requests\Admin\User\UserDeleteRequest;
use App\Http\Requests\Admin\User\UserEditRequest;
use App\Http\Requests\Admin\User\UserListDataRequest;
use App\Http\Requests\Admin\User\UserListRequest;
use App\Http\Requests\Admin\User\UserPasswordEditRequest;
use App\Http\Requests\Admin\User\UserPasswordUpdateRequest;
use App\Http\Requests\Admin\User\UserStatusChangeRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Repositories\User\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function listUsers(UserListRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'Users'],
        ];

        return view('admin.users.listUsers', compact('breadcrumbs'));
    }

    public function userListData(UserListDataRequest $request, UserRepository $userRepo)
    {
        $users = $userRepo->getAllUsersexcepetSuperAdmin($request->all());
        $dataTableJSON = DataTables::of($users)
            ->addIndexColumn()
            // ->addColumn('role', function ($user) {
            //     return $user->roles->first() ? ucwords(str_replace('_', ' ', $user->roles->first()->name)) : '';
            // })
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

    public function addUser(UserAddRequest $request, UserRepository $userRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_user_list', 'name' => 'Users', 'permission' => 'user_read'],
            ['name' => 'Add User'],
        ];

        $roles = $userRepo->getAllRole();

        return view('admin.users.addUser', compact('roles', 'breadcrumbs'));
    }

    public function createUser(UserCreateRequest $request, UserRepository $userRepo)
    {
        $data = $request->only(['name', 'phone', 'email','status']);

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = Storage::disk('savomart')->putFile('users', $request->file('profile_picture'));
        }
        $user = $userRepo->createUser($data);

        if (!empty($request->role_id)) {
            $user->assignRole($request->role_id);
        }

        return redirect()
            ->route('admin_user_list')
            ->with('success', 'User added successfully');
    }

    public function editUser(UserEditRequest $request, UserRepository $userRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_user_list', 'name' => 'Users', 'permission' => 'user_read'],
            ['name' => 'User Details'],
        ];
        $user = $userRepo->getUser($request->id);
        $roles = $userRepo->getAllRole();

        return view('admin.users.editUser', compact('user', 'roles', 'breadcrumbs'));
    }

    public function updateUser(UserUpdateRequest $request, UserRepository $userRepo)
    {
        $data = $request->only(['name', 'phone','email', 'status']);

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = Storage::disk('savomart')->putFile('users', $request->file('profile_picture'));
        } elseif ($request->has('profile_picture_remove') && $request->profile_picture_remove) {
            $data['profile_picture'] = '';
        }
        $user = $userRepo->updateUser($request->id, $data);

        if ($request->has('role_id')) {
            $user->syncRoles($request->role_id);
        }

        return redirect()->route('admin_user_list')->with('success', 'User updated successfully');
    }

    public function changeUserPassword(UserPasswordEditRequest $request, UserRepository $userRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'Change Password'],
        ];

        return view('admin.users.changeUserPassword', compact('breadcrumbs'));
    }

    public function updateUserPassword(UserPasswordUpdateRequest $request, UserRepository $userRepo)
    {
        $validator = Validator::make($request->all(), [], []);

        if ($request->has('current')) {
            if (!Hash::check($request->current, auth()->user()->password)) {
                $validator->getMessageBag()->add('current', 'The current password is incorrect.');

                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($request->current == $request->password) {
                $validator->getMessageBag()->add('password', 'New password must be different from current.');

                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $data['password'] = Hash::make($request->password);
        $user = $userRepo->updateUser($request->id, $data);

        if ($request->has('current')) {
            return redirect()->route('admin_dashboard_home')->with('success', 'User password updated successfully');
        }

        return redirect()
            ->route('admin_user_list')
            ->with('success', 'User password updated successfully');
    }

    public function statusChange(UserStatusChangeRequest $request, UserRepository $userRepo)
    {
        if ($userRepo->userStatusUpdate($request->id)) {
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }

    public function deleteUser(UserRepository $userRepo, UserDeleteRequest $request)
    {
        if ($userRepo->deleteUser($request->id)) {
            if ($request->ajax()) {
                return response()->json(['status' => 1, 'message' => 'User deleted successfully']);
            } else {
                return redirect()->route('admin_user_list')->with('success', 'User deleted successfully');
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 0, 'message' => 'Failed to delete']);
        } else {
            return redirect()->route('admin_user_list')->with('success', 'Failed to delete');
        }
    }
}