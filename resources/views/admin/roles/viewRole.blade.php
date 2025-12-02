@section('title', 'View Role')

@push('script')
    <script src="{{ asset('js/admin/roles/viewRole.js') }}"></script>
@endpush

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/roles/viewRole.css') }}">
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <div class="d-flex flex-column flex-lg-row">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h2 class="mb-0">{{ $role->name_display }}</h2>
                    </div>
                </div>
                <div class="card-body pt-1">
                    <div class="fw-bold text-gray-600 mb-5">Total users with this role: {{ $role->users->count() }}</div>
                </div>
                <div class="card-footer pt-0">
                    <button type="button" class="btn btn-light btn-active-primary" kt-load-remote-init="false" kt-load-remote-html="true" data-url="{{ route('admin_role_edit', ['id' => $role->id]) }}">Edit Role</button>
                </div>
            </div>
        </div>
        <div class="flex-lg-row-fluid ms-lg-10">
            <div class="card card-flush mb-6 mb-xl-9">
                <div class="card-header pt-5">
                    <div class="card-title">
                        <h2 class="d-flex align-items-center">Users Assigned
                            <span class="text-gray-600 fs-6 ms-1">({{ $role->users->count() }})</span>
                        </h2>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1" data-kt-view-table-toolbar="base">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>
                            <input type="text" data-kt-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Users" />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="rolesUsersTable" data-role_id="{{ $role->id }}" data-url="{{ route('admin_role_users_table') }}">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>#</th>
                                <th class="min-w-150px">User</th>
                                <th class="min-w-125px">Status</th>
                                <th class="min-w-125px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
