@section('title', 'List Orders')

@push('script')
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
                                <option value="pending" @if ($status=='pending' ) selected @endif>Pending</option>
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
            <table class="table align-middle table-row-dashed fs-6 gy-5">
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
                    @foreach ($orders as $index => $order)
                    <tr>
                        {{-- Index --}}
                        <td>{{ $index + 1 }}</td>

                        {{-- Created Date --}}
                        <td>
                            {{ optional($order->created_at)->format('Y-m-d') }}
                        </td>

                        {{-- Order No (link if permitted) --}}
                        <td>
                            @can('order_read')
                            <a href="{{ route('admin_order_view', ['id' => $order->id]) }}" class="text-gray-800 text-hover-primary fw-bold">
                                {{ $order->order_no }}
                            </a>
                            @else
                            {{ $order->order_no }}
                            @endcan
                        </td>

                        {{-- Customer Name --}}
                        <td>
                            {{ $order->customer ? $order->customer->name : '' }}
                        </td>

                        {{-- Order Status --}}
                        <td>
                            @include('admin.order.orderStatus', ['data' => $order->status])
                        </td>

                        {{-- Payment Method --}}
                        <td>
                            @include('admin.order.paymentStatus', ['data' => $order->payment_type])
                        </td>

                        {{-- Actions --}}
                        <td>
                            @can('order_delete')
                            <form action="{{ route('admin_order_delete', ['id' => $order->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
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