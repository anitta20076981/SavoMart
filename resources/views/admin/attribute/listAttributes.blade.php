@section('title', 'List Attributes')

@push('script')
<script src="{{ mix('js/admin/attribute/listAttribute.js') }}"></script>
@endpush

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/attribute/listAttribute.css') }}">
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
                            <select id="status" class="form-select" data-hide-search="true" data-kt-select2="true" data-placeholder="Select status" data-allow-clear="true" data-dropdown-parent="#kt-toolbar-filter">
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
                @can('attribute_create')
                <a href="{{ route('admin_attribute_add') }}" class="btn btn-primary">Add Attribute</a>
                @endcan
            </x-dt-toolbar>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="listAttributes" data-url="{{ route('admin_attribute_table') }}">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th class="min-w-125px">Attribute Name</th>
                        <th class="min-w-125px">Code</th>
                        <th class="min-w-125px">Type</th>
                        <th class="min-w-125px">Required</th>
                        <th class="min-w-125px">Status</th>
                        <th class="min-w-100px">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>