@section('title', 'Edit Order')


@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/order/editOrder.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/order/editOrder.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <form novalidate="novalidate" id="editOrderForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_order_update') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $order->id }}">

        <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="selectedproductId" id="selectedproductId" value='@json($productId)'>

        <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Order Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <div class="fv-row">
                            <label class="form-label">Order No</label>
                            <input type="hidden" name="order_no" value="{{ $order->order_no }}">
                            <div class="fw-bold fs-3">#{{ $order->order_no }}
                            </div>
                        </div>
                        <div class="fv-row">
                            <label class="required form-label">Payment Method</label>
                            <select id="payment_method_id" name="payment_method_id" data-control="select2" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Payment Method" data-option-url="{{ route('admin_options_paymentmethod') }}" value="{{ old('payment_method_id') }}">
                                @if (isset($old['payment_method_id']) && $old['payment_method_id'] != '')
                                    <option value="{{ $old['payment_method_id']->id }}">
                                        {{ $old['payment_method_id']->name }}
                                    </option>
                                @endif
                            </select>
                            @error('payment_method_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="fv-row">
                            <label class="required form-label">Shipping Method</label>
                            <select id="shipment_method_id" name="shipment_method_id" data-control="select2" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Payment Method" data-option-url="{{ route('admin_options_shipmentmethod') }}" value="{{ old('shipment_method_id') }}">
                                @if (isset($old['shipment_method_id']) && $old['shipment_method_id'] != '')
                                    <option value="{{ $old['shipment_method_id']->id }}">
                                        {{ $old['shipment_method_id']->name }}
                                    </option>
                                @endif
                            </select>
                            @error('shipment_method_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Order Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Customer</label>
                        <select id="customer_id" name="customer_id" class="form-select customer_id" data-kt-select2="true" data-server="true" data-placeholder="Select Customers" data-option-url="{{ route('admin_options_customers') }}" value="{{ old('customer_id') }}" data-customer-details-url="{{ route('admin_customer_address_customer_address') }}" data-customer-address-url="{{ route('admin_customer_address_customer_address') }}">
                            @if (isset($old['customer_id']) && $old['customer_id'] != '')
                                <option value="{{ $old['customer_id']->id }}">
                                    {{ $old['customer_id']->name }}
                                </option>
                            @endif
                        </select>
                        @error('customer_id')
                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                        <div class="customer-card">
                            @include('admin.customer.customerCard', ['customer' => $order->customer])
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-10">
                <div class="card card-flush p-4">
                    <label class="form-label">Add products to this order</label>
                    <div class="row row-cols-1 row-cols-xl-2 row-cols-md-2 border border-dashed rounded pt-3 pb-1 px-2 mb-5 mh-300px overflow-scroll" id="order_selected_products">
                        @foreach ($order->orderItems as $items)
                            <div class="col my-2" data-order-filter="product" data-product_id={{ $items->product_id }} data-order-product-node="product_{{ $items->product_id }}">
                                <div class="d-flex align-items-center border border-dashed rounded p-3 bg-body">
                                    <a href="javascript:void(0);" kt-load-remote-init="false" kt-load-remote-html="true" data-url="{{ route('admin_products_view', ['id' => $product->id]) }}" class="symbol symbol-50px">
                                        @if (!isset($items->productThumbnail->image_path))
                                            <span class="symbol-label" style="background-image:url({{ asset('images/admin/logos/logo-small.png') }}"></span>
                                        @else
                                            <span class="symbol-label" style="background-image:url({{ Storage::disk('foodovity')->url($items->product->productThumbnail->image_path) }}"></span>
                                        @endif
                                    </a>
                                    <div class="ms-5">
                                        <a href="javascript:void(0);" kt-load-remote-init="false" kt-load-remote-html="true" data-url="{{ route('admin_products_view', ['id' => $product->id]) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $items->product->name }}</a>
                                        <div class="fw-semibold fs-7">Price:
                                            <span data-order-filter="price">{{ formatAmount($items->price) }}</span>
                                        </div>
                                        <div class="text-muted fs-7">SKU: {{ $items->product->sku }}</div>
                                    </div>
                                    <div class="ms-5 added-product" data-product-values="true">
                                        <input type="hidden" data-product-="true" value={{ $items->id }} name="products[{{ $items->product_id }}][id]">
                                        <input type="hidden" data-product-="true" value={{ $items->product_id }} name="products[{{ $items->product_id }}][product_id]">
                                        <input type="hidden" class="product-price" data-product-price="true" value={{ $items->price }} name="products[{{ $items->product_id }}][price]">
                                        <input type="hidden" class="product-min-salable-qty" data-product-min-salable-qty="true" value={{ $items->product->productInventory->min_salable_quantity }} name="products[{{ $items->product_id }}][min_salable_quantity]">
                                        <input type="hidden" class="product-max-salable-qty" data-product-max-salable-qty="true" value={{ $items->product->productInventory->max_salable_quantity }} name="products[{{ $items->product_id }}][max_salable_quantity]">
                                        <input type="number" data-product-qty="true" class="form-control mb-2 w-125px float-end orderQty" placeholder="Qty" value={{ $items->quantity }} name="products[{{ $items->product_id }}][quantity]" min="1">
                                        <input type="hidden" class="product-base-price" data-product-base-price="true" value={{ $items->base_price }} name="products[{{ $items->product_id }}][base-price]">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <span class="w-100 text-muted d-none product-empty">Select one or more products from the list below by ticking the checkbox.</span>
                    </div>
                    <div id="error-div"></div>
                    <span id="errorMsg" style="display:none;" class="text-danger">Enter valid quanity</span>
                    <span id="productRequiredMsg" style="display:none;" class="text-danger">Select one or more products for request an order</span>
                    <div class="fw-bold fs-4">Total Cost:
                        <span id="order_total_price">{{ formatAmount($order->sub_total) }}</span>
                        <input type="hidden" name="total_price" id="total_price" value={{ $order->sub_total }}>
                    </div>
                    @error('products')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                    @enderror
                    <div class="separator"></div>
                    <div class="d-flex row align-items-center mb-n7">
                        <div class="col-md-6 position-relative">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4 top-25">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>
                            <input type="text" data-kt-order-products-filter="search" class="form-control w-100 ps-14" placeholder="Search Products" />
                        </div>
                        <div class="col-md-6">
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="form-label">Category</label>
                                <select id="category_id" name="category_id" data-allow-clear="true" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Category" data-option-url="{{ route('admin_options_categories') }}" value="{{ old('customer_id') }}">
                                </select>
                                @error('category_id')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="listProducts" data-url="{{ route('admin_order_editproductsTable') }}">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="select-checkbox"></th>
                                <th class="min-w-125px">Product</th>
                                <th class="min-w-125px">Quantity</th>
                            </tr>
                        </thead>

                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-flush py-4 deliveryaddress">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Delivery Details</h2>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-5 gap-md-4 mb-10 address-section billing-address-section">
                        <div class="fs-3 fw-bold mb-n2">Billing Address</div>
                        <div class="customerAddressCard"></div>
                        <div class="hidden-billing-div address-div">
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="required form-label">Billing Address </label>
                                    <input class="form-control" name="billing_address_address_1" id="billing_address_address_1" placeholder="Address Line 1" value={{ $order->billingAddress->street_address }} />
                                    @error('billing_address_address_1')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <input type="hidden" name="billing_address_id" id="billing_address_id" value={{ $order->billingAddress->id }}>
                                <div class="fv-row flex-row-fluid">
                                    <label class="form-label">City</label>
                                    <input class="form-control" name="billing_address_city" id="billing_address_city" placeholder="City" value={{ $order->billingAddress->city }} />
                                    @error('billing_address_city')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="required form-label">Postcode</label>
                                    <input class="form-control" name="billing_address_postcode" id="billing_address_postcode" placeholder="Postcode" value={{ $order->billingAddress->postel_code }} />
                                    @error('billing_address_postcode')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <div class="fv-row flex-row-fluid">
                                        <label class="required form-label">Phone Number</label>
                                        <input class="form-control" name="billing_address_contact" id="billing_address_contact" placeholder="Phone Number" value={{ $order->billingAddress->contact }} />
                                        @error('billing_address_contact')
                                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="required form-label">State</label>
                                    <select id="billing_address_state" name="billing_address_state" class="form-select" data-placeholder="Select State" data-kt-select2="true" data-server="true" data-option-url="{{ route('admin_options_states') }}" value="{{ old('state') }}">
                                        @if (isset($old['state']) && $old['state'] != '')
                                            <option value="{{ $old['state']->id }}">
                                                {{ $old['state']->name }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('billing_address_state')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="0" name="same_as_billing" id="same_as_billing" />
                            <label class="form-check-label" for="same_as_billing">Shipping address is the same as billing address</label>
                        </div> --}}
                    </div>
                    <div class="d-flex flex-column gap-5 gap-md-4 address-section shipping-address-section mt-3" id="shipping-address-section">
                        <div class="fs-3 fw-bold mb-n2">Shipping Address</div>
                        <div class="customerAddressCard"></div>
                        <div class="hidden-shipping-div address-div">
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="form-label">Shipping Address</label>
                                    <input class="form-control" name="shipping_address_address_1" id="shipping_address_address_1" placeholder="Address Line 1" value={{ $order->shippingAddress->street_address }} />
                                    @error('shipping_address_address_1')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <input type="hidden" name="shipping_address_id" id="shipping_address_id" value={{ $order->shippingAddress->id }} />
                                <div class="fv-row flex-row-fluid">
                                    <label class="form-label">City</label>
                                    <input class="form-control" name="shipping_address_city" id="shipping_address_city" placeholder="City" value={{ $order->shippingAddress->city }} />
                                    @error('shipping_address_city')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="form-label">Postcode</label>
                                    <input class="form-control" name="shipping_address_postcode" id="shipping_address_postcode" placeholder="Pin Code" value={{ $order->shippingAddress->postel_code }} />
                                    @error('shipping_address_postcode')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control" name="shipping_address_contact" id="shipping_address_contact" placeholder="Phone Number" value={{ $order->shippingAddress->contact }} />
                                    @error('shipping_address_contact')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="form-label">State</label>
                                    <select id="shipping_address_state" name="shipping_address_state" class="form-select" data-placeholder="Select State" data-kt-select2="true" data-server="true" data-option-url="{{ route('admin_options_states') }}" value="{{ old('state') }}">
                                        @if (isset($old['shipment_state']) && $old['shipment_state'] != '')
                                            <option value="{{ $old['shipment_state']->id }}">
                                                {{ $old['shipment_state']->name }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('shipping_address_state')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-light btn-active-light-primary me-2 fv-button-back">Cancel</button>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Place Order</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
