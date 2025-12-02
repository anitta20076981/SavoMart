@section('title', 'View Order')


@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/order/invoice.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/order/invoice.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="invoiceForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_order_invoice_invoice_create', ['order_id' => $order->id]) }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="card w-100">
            <div class="card-body">
                <div class="mw-lg-950px mx-auto w-100">
                    <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                        <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">INVOICE</h4>
                        <div class="text-sm-end">
                            <a href="#" class="d-block mw-150px ms-sm-auto">
                                <img alt="Logo" src="{{ asset('images/admin/logos/logo111.jpeg') }}" class="h-50px h-lg-50px" />
                            </a>
                            <div class="text-sm-end fw-semibold fs-4 text-muted mt-7">
                                <div>{{ config('settings.store.company_name') }}</div>
                                <div>{{ config('settings.store.company_description') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="d-flex flex-column gap-7 gap-md-10">
                            <div class="fw-bold fs-2">Dear {{ $order->customer->name }}
                                <span class="fs-6">( {{ $order->customer->email }})</span>,
                                <br />
                                <span class="text-muted fs-5">Here are your order details. We thank you for your purchase.</span>
                            </div>
                            <div class="separator"></div>
                            <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                <div class="flex-root d-flex flex-column">
                                    <span class="text-muted">Order ID</span>
                                    <span class="fs-5">#{{ $order->order_no }}</span>
                                </div>
                                <div class="flex-root d-flex flex-column">
                                    <span class="text-muted">Date</span>
                                    <span class="fs-5">{{ $order->created_at->format('d-m-Y') }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">

                                <div class="flex-root d-flex flex-column">

                                    <div class="d-flex">
                                        <div class="col-8">
                                            <p class="text-muted">Shipping Address</p>
                                            <span class="fs-6"> {{ $order->orderAddress->details }},
                                                <br />{{ $order->orderAddress->street_address }}.
                                                <br />Ph : {{ $order->orderAddress->contact }}.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between flex-column">
                                <div class="table-responsive">
                                    <input type="hidden" name="currency" id="currency" value={{ config('app.currency.symbol') }}>
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="orderItems">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-125px">Product</th>
                                                <th class="min-w-125px">Sku</th>
                                                <th class="min-w-125px">Quantity</th>
                                                <th class="min-w-125px">Unit Price</th>
                                                <th class="min-w-125px">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoiceProducts as $orderItem)
                                                <tr class="mainItems">
                                                    <input type="hidden" value={{ $orderItem['product_id'] }} id="products[{{ $orderItem['product_id'] }}][product_id]" name="products[{{ $orderItem['product_id'] }}][product_id]">
                                                    <input type="hidden" value={{ $orderItem['id'] }} id="products[{{ $orderItem['product_id'] }}][order_item_id]" name="products[{{ $orderItem['product_id'] }}][order_item_id]">
                                                    <input type="hidden" value={{ $orderItem['quantity'] }} id="products[{{ $orderItem['product_id'] }}][order_qty]" name="products[{{ $orderItem['product_id'] }}][order_qty]">
                                                    <input type="hidden" value={{ $orderItem['tax_percent'] }} id="products[{{ $orderItem['product_id'] }}][tax_percent]" name="products[{{ $orderItem['product_id'] }}][tax_percent]">
                                                    <td>{{ $orderItem['name'] }}</td>
                                                    <td>{{ $orderItem['sku'] }}</td>
                                                    <input class="form-control actual-qty" type="hidden" value={{ $orderItem['quantity'] }}>

                                                    <td> {{ $orderItem['quantity'] }}</td>

                                                    <input class="percent" type="hidden" value={{ $orderItem['tax_percent'] }} id="products[{{ $orderItem['product_id'] }}][tax_percent]" name="products[{{ $orderItem['product_id'] }}][tax_percent]">

                                                    <td> <input class="unit_price" type="hidden" value={{ $orderItem['price'] }} id="products[{{ $orderItem['product_id'] }}][unit_price]" name="products[{{ $orderItem['product_id'] }}][unit_price]">
                                                        {{ $orderItem['price'] }}</td>
                                                    <td>
                                                        <label id="grandTotal">{{ $orderItem['price'] * $orderItem['quantity'] }}
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <input type="hidden" name="shipping_rate" id="shipping_rate" value=0>
                                            <input type="hidden" name="tax_amount" id="tax_amount" value={{ $order->tax_amount }}>
                                            <input type="hidden" name="grand_total" id="grand_total" value={{ $order->grand_total }}>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>Grand Total:</td>
                                                <td> <label id="order-grandTotal">
                                                        {{ $grandTotal }}
                                                    </label></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" id="saveBtn" class="btn btn-primary fv-button-submit float-end">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
    <br>
</x-admin-layout>
