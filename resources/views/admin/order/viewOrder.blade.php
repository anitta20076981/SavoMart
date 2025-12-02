@section('title', 'View Order')


@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/order/viewOrder.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/order/viewOrder.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#order_summary">Order Summary</a>
                </li>
                {{-- @if ($order->invoice != null && $order->status != 'rejected') --}}
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#invoice_summary">Invoice</a>
                </li>
                {{-- @endif --}}
                {{-- @if ($order->shipment != null && $order->status != 'rejected') --}}
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#shipments">Shipment</a>
                </li>
                {{-- @endif --}}
            </ul>
            @if ($order->status == 'pending')
                <button class="btn btn-success btn-sm me-lg-n7 order-cancel-button" data-cancel-url="{{ route('admin_order_cancel', ['id' => $order->id]) }}">
                    Cancel Order </button>
            @endif


            @if ($order->invoice_status == 'pending' && $order->status != 'rejected')
                <a href="{{ route('admin_order_invoice_add_invoice', ['id' => $order->id]) }}" class="btn btn-success btn-sm me-lg-n7">Invoice</a>
            @endif
            @if ($order->shipment_status == 'pending' && $order->status != 'rejected')
                <a href="{{ route('admin_order_shipment_add_shipment', ['id' => $order->id]) }}" class="btn btn-success btn-sm me-lg-n7">Shipment</a>
            @endif
            @if ($order->status == 'dispatched')
                <button class="btn btn-success btn-sm me-lg-n7 order-delivered-button" data-delivered-url="{{ route('admin_order_delivered', ['order_id' => $order->id]) }}">
                    Submit Delivery </button>
            @endif

        </div>
        <div class="tab-content shipment-contr">
            <div class="tab-pane fade show active" id="order_summary" role="tab-panel">
                <div class="d-flex flex-column mb-10 flex-xl-row gap-7 gap-lg-10">
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Order Details (#{{ $order->order_no }})
                                    @if ($order->status == 'placed')
                                        <div class="badge badge-light-info">Placed</div>
                                    @endif
                                    @if ($order->status == 'pending')
                                        <div class="badge badge-light-warning">Processing</div>
                                    @endif
                                    @if ($order->status == 'dispatched')
                                        <div class="badge badge-light-dark">Dispatched</div>
                                    @endif
                                    @if ($order->status == 'delivered')
                                        <div class="badge badge-light-success">Delivered</div>
                                    @endif
                                    @if ($order->status == 'rejected')
                                        <div class="badge badge-light-danger">Rejected</div>
                                    @endif
                                </h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.3"
                                                                d="M19 3.40002C18.4 3.40002 18 3.80002 18 4.40002V8.40002H14V4.40002C14 3.80002 13.6 3.40002 13 3.40002C12.4 3.40002 12 3.80002 12 4.40002V8.40002H8V4.40002C8 3.80002 7.6 3.40002 7 3.40002C6.4 3.40002 6 3.80002 6 4.40002V8.40002H2V4.40002C2 3.80002 1.6 3.40002 1 3.40002C0.4 3.40002 0 3.80002 0 4.40002V19.4C0 20 0.4 20.4 1 20.4H19C19.6 20.4 20 20 20 19.4V4.40002C20 3.80002 19.6 3.40002 19 3.40002ZM18 10.4V13.4H14V10.4H18ZM12 10.4V13.4H8V10.4H12ZM12 15.4V18.4H8V15.4H12ZM6 10.4V13.4H2V10.4H6ZM2 15.4H6V18.4H2V15.4ZM14 18.4V15.4H18V18.4H14Z"
                                                                fill="currentColor" />
                                                            <path d="M19 0.400024H1C0.4 0.400024 0 0.800024 0 1.40002V4.40002C0 5.00002 0.4 5.40002 1 5.40002H19C19.6 5.40002 20 5.00002 20 4.40002V1.40002C20 0.800024 19.6 0.400024 19 0.400024Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    Date And Time
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $order->created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Customer Details</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.3" d="M16.5 9C16.5 13.125 13.125 16.5 9 16.5C4.875 16.5 1.5 13.125 1.5 9C1.5 4.875 4.875 1.5 9 1.5C13.125 1.5 16.5 4.875 16.5 9Z" fill="currentColor" />
                                                            <path d="M9 16.5C10.95 16.5 12.75 15.75 14.025 14.55C13.425 12.675 11.4 11.25 9 11.25C6.6 11.25 4.57499 12.675 3.97499 14.55C5.24999 15.75 7.05 16.5 9 16.5Z" fill="currentColor" />
                                                            <rect x="7" y="6" width="4" height="4" rx="2" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    Customer
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <a href="{{ auth()->user()->can('customer_read') ? route('admin_customer_view', ['id' => $order->customer ? $order->customer->id : '']) : 'javascript:void(0)' }}" target="_blank" class="text-gray-600 text-hover-primary">{{ $order->customer ? $order->customer->name : '' }}</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor" />
                                                            <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    Email
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $order->customer ? $order->customer->email : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5 20H19V21C19 21.6 18.6 22 18 22H6C5.4 22 5 21.6 5 21V20ZM19 3C19 2.4 18.6 2 18 2H6C5.4 2 5 2.4 5 3V4H19V3Z" fill="currentColor" />
                                                            <path opacity="0.3" d="M19 4H5V20H19V4Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    Phone
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $order->customer ? $order->customer->phone : '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column mb-10 flex-xl-row gap-7 gap-lg-10">
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>
                                    <span class="svg-icon svg-icon-2 me-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z" fill="currentColor" />
                                            <path opacity="0.3" d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z" fill="currentColor" />
                                            <path d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z" fill="currentColor" />
                                        </svg>
                                    </span> Payment Details
                                </h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    Payment Status
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                @if ($order->payment_type != 1)
                                                    <div class="badge badge-light-info">Pending</div>
                                                @else
                                                    <div class="badge badge-light-info">Cash on Delivery</div>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2><span class="svg-icon svg-icon-2 me-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z" fill="currentColor" />
                                            <path opacity="0.3" d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z" fill="currentColor" />
                                        </svg>
                                    </span> Shipping Details
                                </h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">

                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    Shipment Adress
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ $order->orderAddress ? $order->orderAddress->details : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    Street
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ $order->orderAddress ? $order->orderAddress->street_address : '' }}
                                            </td>
                                        </tr>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    Phone
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ $order->orderAddress ? $order->orderAddress->contact : '' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Products</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="listShipment">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">

                                            <th class="min-w-125px">Name</th>
                                            <th class="min-w-125px">Date</th>
                                            <th class="min-w-125px">Order No</th>
                                            <th class="min-w-125px">Quantity</th>
                                            <th class="min-w-125px">Unit Price</th>
                                            <th class="min-w-125px">Total Price</th>
                                            @if ($order->status == 'delivered' && $order->delivery_date == date('Y-m-d'))
                                                <th class="min-w-125px">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($order->orderItems != null)
                                            @foreach ($order->orderItems as $items)
                                                <tr>
                                                    <td>{{ $items->product->name }}</td>
                                                    <td>{{ $items->created_at }}</td>
                                                    <td>{{ $items->order->order_no }}</td>

                                                    {{-- <td>{{ $items->quantity }}</td> --}}
                                                    <td>
                                                        <div class="d-flex">
                                                            <span class="minus">-</span>
                                                            <input class="form-control quantity-input" type="number" name="quantity" @if ($order->status != 'pending') readonly @endif value={{ number_format($items->quantity, 0, '.', '') }} data-available-qty={{ $items->product->quantity }} data-ordered-qty={{ $items->quantity }} data-url="{{ route('admin_order_quantity_update', ['order_item_id' => $items->id, 'order_id' => $items->order->id]) }}">
                                                            <span class="plus">+</span>
                                                        </div>
                                                        <p class="error-message" style="color: #f1416c;"></p>
                                                    </td>
                                                    <td><span class="price">{{ $items->unit_price }}</span></td>

                                                    <td><span class="total-price">{{ $items->total_price }}</span></td>
                                                    @if ($order->status == 'delivered' && $order->delivery_date == date('Y-m-d'))
                                                        @if (isset($items->orderReturnItem))
                                                            <td>
                                                                @include('admin.order.orderReturnStatus', ['data' => $items->return_status])
                                                            </td>
                                                        @else
                                                            <td> <a href="{{ route('admin_order_return_add', ['order_item_id' => $items->id, 'order_id' => $items->order->id]) }}" class="btn btn-success">
                                                                    Return
                                                                </a></td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="invoice_summary" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Invoice</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="listInvoice">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-125px">Invoice No</th>
                                            <th class="min-w-125px">Invoice Date</th>
                                            <th class="min-w-125px">Order No</th>
                                            <th class="min-w-125px">Order Date</th>
                                            <th class="min-w-125px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($order->invoice != null)
                                            <tr>
                                                <td>{{ $order->invoice->invoice_no }}</td>
                                                <td>{{ $order->invoice->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $order->invoice->order->order_no }}</td>
                                                <td>{{ $order->invoice->order->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('admin_order_invoice_invoice_view', ['id' => $order->invoice->id]) }}" class="btn btn-sm btn-light btn-active-light-primary">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="tab-pane fade" id="shipments" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Shipment</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="listShipment">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">

                                            <th class="min-w-125px">Shipment No</th>
                                            <th class="min-w-125px">Shipment Date</th>
                                            <th class="min-w-125px">Order No</th>
                                            <th class="min-w-125px">Order Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($order->shipment != null)
                                            @foreach ($order->shipment as $items)
                                                <tr>
                                                    <td>{{ $items->shipment_no }}</td>
                                                    <td>{{ $items->created_at->format('Y-m-d') }}</td>
                                                    <td>{{ $items->order->order_no }}</td>
                                                    <td>{{ $items->order->created_at->format('Y-m-d') }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
