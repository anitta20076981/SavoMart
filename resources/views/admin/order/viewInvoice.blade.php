@section('title', 'View Invoice')


@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/order/invoice.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/order/invoice.js') }}"></script>
@endpush

<x-admin-layout>

    <div class="content d-flex flex-column flex-column-fluid" id="ash_content">
        <div id="ash_content_container" class="container-xxl">
            <div class="card">
                <div class="card-body py-20">
                    <div class="mw-lg-950px mx-auto w-100">
                        <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                            <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">INVOICE</h4>
                            <div class="text-sm-end">
                                <a href="#" class="d-block mw-150px ms-sm-auto">
                                    <img alt="Logo" src="{{ asset('images/admin/logos/logo111.jpeg') }}" class="h-50px h-lg-50px" /> </a>
                                <div class="text-sm-end fw-semibold fs-4 text-muted mt-7">
                                    <div>{{ config('settings.store.company_name') }}</div>
                                    <div>{{ config('settings.store.company_description') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="pb-12">
                            <div class="d-flex flex-column gap-7 gap-md-10">
                                <div class="fw-bold fs-2">Dear {{ $invoice->order->customer->name }}
                                    <span class="fs-6">( {{ $invoice->order->customer->email }})</span>,
                                    <br />
                                    <span class="text-muted fs-5">Here are your order details. We thank you for your purchase.</span>
                                </div>
                                <div class="separator"></div>
                                <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">Order ID</span>
                                        <span class="fs-5">#{{ $invoice->order->order_no }}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">Date</span>
                                        <span class="fs-5">{{ $invoice->order->created_at->format('d-m-Y') }}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">Invoice ID</span>
                                        <span class="fs-5">#{{ $invoice->invoice_no }}</span>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">Shipping Address</span>
                                        <span class="fs-6"> {{ $invoice->order->orderAddress->details }},
                                            <br />{{ $invoice->order->orderAddress->street_address }}
                                            <br />Ph : {{ $invoice->order->orderAddress->contact }}.</span>

                                    </div>
                                </div>
                                <div class="d-flex justify-content-between flex-column">
                                    <div class="table-responsive border-bottom mb-9">
                                        <input type="hidden" name="order_id" id="order_id" value={{ $invoice->order->id }}>
                                        <input type="hidden" name="currency" id="currency" value={{ config('app.currency.symbol') }}>
                                        <input type="hidden" name="shipping_rate" id="shipping_rate" value=0>
                                        <input type="hidden" name="tax_amount" id="tax_amount" value={{ $invoice->order->tax_amount }}>
                                        <input type="hidden" name="grand_total" id="grand_total" value={{ $invoice->order->grand_total }}>
                                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="listOrderedProducts">
                                            <thead>
                                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-125px">Product</th>
                                                    <th class="min-w-125px">Sku</th>
                                                    <th class="min-w-125px">Quantity</th>
                                                    <th class="min-w-125px">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoiceItems as $item)
                                                    <tr>
                                                        <td>{{ $item->product->name }}</td>
                                                        <td>{{ $item->product->sku }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ $item->total_amount }}</td>
                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
