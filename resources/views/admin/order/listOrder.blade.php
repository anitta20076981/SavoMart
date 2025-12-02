@section('title', 'List Orders')

@push('script')
    <script src="{{ mix('js/admin/order/listOrder.js') }}"></script>
@endpush

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/order/listOrder.css') }}">
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
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
                            <select id="status" class="form-select" data-kt-select2="true" data-placeholder="Select status" data-allow-clear="true" data-dropdown-parent="#kt-toolbar-filter">
                                <option value="">All</option>
                                <option value="pending" @if ($status == 'pending') selected @endif>Pending</option>
                                <option value="processing">Processing</option>
                                <option value="dispatched">Dispatched</option>
                                <option value="delivered">Delivered</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Customers</label>
                            <select id="customer_id" name="customer_id" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Customer" data-option-url="{{ route('admin_options_customers') }}" data-allow-clear="true" data-dropdown-parent="#kt-toolbar-filter">
                            </select>
                            @error('customer_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-table-filter="reset">Reset</button>
                            <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-table-filter="filter">Apply</button>
                        </div>
                    </div>
                </div>
                @can('order_create')
                    <a href="{{ route('admin_order_add') }}" class="btn btn-primary">Add Order</a>
                @endcan
            </x-dt-toolbar>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="listOrders" data-url="{{ route('admin_order_table') }}">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th class="min-w-125px">Placed On</th>
                        <th class="min-w-125px">Order No</th>
                        <th class="min-w-125px">Customer Name</th>
                        <th class="min-w-125px">Order Status</th>
                        {{-- <th class="min-w-125px">Mode Of Payment</th> --}}
                        <th class="min-w-70px export-none">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
