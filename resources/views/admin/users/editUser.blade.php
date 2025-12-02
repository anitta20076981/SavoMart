@section('title', 'Edit User')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/users/editUser.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/users/editUser.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <form novalidate="novalidate" id="userForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_user_update') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Profile Picture</h2>
                    </div>
                </div>
                <div class="card-body text-center pt-0">
                    <div class="fv-row fv-plugins-icon-container">
                        <div class="image-input @if (!$user->profile_picture || !Storage::disk('grocery')->exists($user->profile_picture)) image-input-empty @endif image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px" @if ($user->profile_picture && Storage::disk('grocery')->exists($user->profile_picture)) style="background-image:
                                url({{ Storage::disk('grocery')->url($user->profile_picture) }})" data-image="{{ Storage::disk('grocery')->url($user->profile_picture) }}" @endif></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="profile_picture" accept=".png,.jpg,.jpeg">
                                <input type="hidden" name="profile_picture_remove">
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Set the profile picture. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                        @error('profile_picture')
                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Status</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="user_status_select">
                        <option value="active" @if ($user->status == 'active') selected @endif>Active</option>
                        <option value="inactive" @if ($user->status == 'inactive') selected @endif>Inactive</option>
                    </select>
                    <div class="text-muted fs-7">Set the user status.</div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_user_overview_tab" aria-selected="true" role="tab">Details</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#kt_user_security_tab" data-kt-initialized="1" aria-selected="false" tabindex="-1" role="tab">Security</a>
                </li>
                @canany(['user_delete'])
                    <li class="nav-item ms-auto">
                        <a href="#" class="btn btn-primary ps-7" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">Actions
                            <span class="svg-icon svg-icon-2 me-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6" data-kt-menu="true">
                            <!-- <div class="separator my-3"></div> -->
                            @can('user_delete')
                                <div class="menu-item px-5">
                                    <a href="{{ route('admin_user_delete', ['id' => $user->id]) }}" class="menu-link text-danger px-5">Delete user</a>
                                </div>
                            @endcan
                        </div>
                    </li>
                @endcan
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_user_overview_tab" role="tabpanel">
                    <div class="card card-flush mb-6 mb-xl-9">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>User Details</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="required form-label">Name</label>
                                <input type="text" name="name" class="form-control mb-2" placeholder="Name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="required form-label">Phone</label>
                                <input type="number" name="phone" class="form-control mb-2" placeholder="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="required form-label">Email</label>
                                <input type="text" name="email" class="form-control mb-2" placeholder="Email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                            {{-- <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="required form-label">Role</label>
                                <select id="role_id" name="role_id" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select role">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @if (old('role_id', optional($user->roles->first())->id) == $role->id) selected @endif>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_user_security_tab" role="tabpanel">
                    <div class="card card-flush mb-6 mb-xl-9">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>User Password</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control mb-2" placeholder="Password">
                            </div>
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirm" class="form-control mb-2" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_user_list') }}" class="btn btn-light me-5">Cancel</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
