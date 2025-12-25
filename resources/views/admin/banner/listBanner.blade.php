@section('title', 'List Banners')

@push('script')
@endpush

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/banner/listBanner.css') }}">
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <x-dt-toolbar>
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                    <div class="px-5 py-3">
                        <div class="fs-4 text-dark fw-bold">Filter Options</div>
                    </div>
                    <div class="separator border-gray-200"></div>
                    <div class="px-5 py-3">
                        <div class="mb-5">
                            <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                            <select id="status" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select status" data-allow-clear="true" data-dropdown-parent="#kt-toolbar-filter">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-table-filter="reset">Reset</button>
                            <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-table-filter="filter">Apply</button>
                        </div>
                    </div>
                </div>
                @can('banner_create')
                <a href="{{ route('admin_banner_add') }}" class="btn btn-primary">Add Banner</a>
                @endcan
            </x-dt-toolbar>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="listBanner">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th class="min-w-125px">Name</th>
                        <th class="min-w-125px">Status</th>
                        <th class="min-w-125px">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600">
                    @foreach ($banners as $index => $banner)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @can('banner_view')
                            <a href="{{ route('admin_banner_edit', ['id' => $banner->id]) }}" class="text-gray-800 text-hover-primary fw-bold">
                                {{ $banner->name }}
                            </a>
                            @else
                            {{ $banner->name }}
                            @endcan
                        </td>
                        <td>{{ $banner->status }}</td>

                        <td>
                            @can('banner_update')
                            <a href="{{ route('admin_banner_edit', ['id' => $banner->id]) }}" class="btn btn-sm btn-light-primary">
                                Edit
                            </a>
                            @endcan

                            @can('banner_delete')
                            <form action="{{ route('admin_banner_banner_delete', ['id' => $banner->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-light-danger">
                                    Delete
                                </button>
                            </form>
                            @endcan
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>