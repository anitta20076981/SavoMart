@section('title', 'View Order')


@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/order/shipment.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/order/shipment.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="shipmentForm" class=" fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_order_shipment_shipment_create', ['order_id' => $order->id]) }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="card-header">
                <div class="card-title">
                    <h2>Order & Account Information
                    </h2>
                </div>
            </div>
            <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                    <div class="position-absolute top-0 end-0 opacity-10 pe-none text-end">
                    </div>
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Order # {{ $order->order_no }} </h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column mw-md-300px w-100">
                            <div class="d-flex flex-stack text-gray-800 mb-3 fs-6">
                                <div class="fw-semibold pe-5">Order Date:</div>
                                <div class="text-end fw-norma">{{ $order->created_at->format('d-m-Y') }}</div>
                            </div>
                            <div class="d-flex flex-stack text-gray-800 mb-3 fs-6">
                                <div class="fw-semibold pe-5">Order Status:</div>
                                <div class="text-end fw-norma">{{ $order->status }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                    <div class="position-absolute top-0 end-0 opacity-10 pe-none text-end">
                    </div>
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Account Information</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column mw-md-300px w-100">
                            <div class="d-flex flex-stack text-gray-800 mb-3 fs-6">
                                <div class="fw-semibold pe-5">Customer Name:</div>
                                <div class="text-end fw-norma">{{ $order->customer->name }}</div>
                            </div>
                            <div class="d-flex flex-stack text-gray-800 mb-3 fs-6">
                                <div class="fw-semibold pe-5">Email:</div>
                                <div class="text-end fw-norma">{{ $order->customer->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Items to Ship
                        </h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">

                        <input type="hidden" name="order_id" id="order_id" value={{ $order->id }}>
                        <input type="hidden" name="currency" id="currency" value={{ config('app.currency.symbol') }}>
                        <input type="hidden" name="shipping_rate" id="shipping_rate" value=0>
                        <input type="hidden" name="tax_amount" id="tax_amount" value={{ $order->tax_amount }}>
                        <input type="hidden" name="grand_total" id="grand_total" value={{ $order->grand_total }}>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="listOrderProducts">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Product</th>
                                    {{-- <th class="min-w-125px">Ordered Qty</th>
                                <th class="min-w-125px">Shipped Qty</th> --}}
                                    <th class="min-w-125px">Quantity to ship</th>
                                    <th class="min-w-125px">Price</th>
                                    <th class="min-w-125px">Total</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipmentProducts as $items)
                                    <input type="hidden" value={{ $items['product_id'] }} id="products[{{ $items['product_id'] }}][product_id]" name="products[{{ $items['product_id'] }}][product_id]">

                                    <tr>
                                        <td>{{ $items['name'] }}
                                            <br>SKU : {{ $items['sku'] }}
                                        </td>

                                        <input class="form-control actual-qty" type="hidden" value={{ $items['quantity'] }} id="products[{{ $items['product_id'] }}][order_qty]" name="products[{{ $items['product_id'] }}][order_qty]">
                                        <input class="form-control" type="hidden" value={{ $items['order_item_id'] }} id="products[{{ $items['product_id'] }}][order_item_id]" name="products[{{ $items['product_id'] }}][order_item_id]">
                                        <input class="form-control" type="hidden" value={{ $items['tax_percent'] }} id="products[{{ $items['product_id'] }}][tax_percent]" name="products[{{ $items['product_id'] }}][tax_percent]">
                                        <input class="form-control" type="hidden" value={{ $items['price'] }} id="products[{{ $items['product_id'] }}][price]" name="products[{{ $items['product_id'] }}][price]">
                                        <input class="form-control product-qty" type="hidden" value={{ $items['quantity'] }} id="products[{{ $items['product_id'] }}][qty]" name="products[{{ $items['product_id'] }}][qty]">

                                        <td>{{ $items['quantity'] }}</td>
                                        <td>{{ $items['price'] }}
                                        </td>
                                        <td> {{ $items['total'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div id="error-div"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" id="saveBtn" class="btn btn-primary fv-button-submit float-end">
            <span class="indicator-label">Save Changes</span>
            <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </form>

</x-admin-layout>
