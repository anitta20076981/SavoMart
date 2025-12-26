@section('title', 'List Customers')

@push('script')
@endpush

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/customer/listCustomer.css') }}">
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
                @can('brand_create')
                <a href="{{ route('admin_customer_add') }}" class="btn btn-primary">Add Customer</a>
                @endcan
            </x-dt-toolbar>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th class="min-w-125px">Name</th>
                        <th class="min-w-125px">Phone</th>
                        <th class="min-w-150px" style="min-width:250px">Approval</th>
                        <th class="min-w-125px">Status</th>
                        <th class="text-end min-w-70px">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600">
                    @foreach ($customers as $index => $customer)
                    <tr>
                        {{-- Index --}}
                        <td>{{ $index + 1 }}</td>

                        {{-- First Name (link if permitted) --}}
                        <td>
                            @can('customer_update')
                            <a href="{{ route('admin_customer_edit', ['id' => $customer->id]) }}" class="text-gray-800 text-hover-primary fw-bold">
                                {{ $customer->first_name }}
                            </a>
                            @else
                            {{ $customer->first_name }}
                            @endcan
                        </td>

                        {{-- Status --}}
                        <td>
                            @include('admin.elements.listStatus', ['data' => $customer])
                        </td>

                        {{-- Approve --}}
                        <td>
                            @include('admin.elements.listApproval', ['data' => $customer])
                        </td>

                        {{-- Actions --}}
                        <td>
                            @can('customer_update')
                            <a href="{{ route('admin_customer_edit', ['id' => $customer->id]) }}" class="btn btn-sm btn-light-primary">
                                Edit
                            </a>
                            @endcan

                            @can('customer_delete')
                            <form action="{{ route('admin_customer_delete', ['id' => $customer->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light-danger">
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