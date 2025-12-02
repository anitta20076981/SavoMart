@section('title', 'Add Order')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/order/addOrder.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/order/addOrder.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="orderForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_order_save') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="quote_id" value="{{ $quote ? $quote->id : '' }}">
        <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Order Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        {{-- <div class="fv-row">
                            <label class="form-label">Order No</label>
                            <input type="hidden" name="order_no" value="{{ $orderNo }}">
                            <div class="fw-bold fs-3">#{{ $orderNo }}
                            </div>
                        </div> --}}
                        <div class="fv-row">
                            <label class="required form-label">Payment Method</label>
                            <select name="payment_method_id" data-control="select2" class="form-select">
                                <option value="1">Cash on Delivery</option>
                                <option value="0">pay Now</option>
                            </select>
                            @error('payment_method_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>

                        {{-- <div class="fv-row">
                            <label class="required form-label">Shipping Method</label>
                            <select id="shipment_method_id" name="shipment_method_id" data-control="select2" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Payment Method" data-option-url="{{ route('admin_options_shipmentmethod') }}" value="{{ old('shipment_method_id') }}">
                                @if (isset($old['payment_method_id']) && $old['payment_method_id'] != '')
                                    <option value="{{ $old['shipment_method_id']->id }}">
                                        {{ $old['shipment_method_id']->name }}
                                    </option>
                                @endif
                            </select>
                            @error('shipment_method_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div> --}}
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
                        @if ($quote)
                            <input type="hidden" name="customer_id" value="{{ $quote->requested_customer_id }}">
                            @include('admin.customer.customerCard', ['customer' => $quote->requestedCustomer])
                        @else
                            <select id="customer_id" name="customer_id" class="form-select customer_id" data-kt-select2="true" data-server="true" data-placeholder="Select Customers" data-option-url="{{ route('admin_options_customers') }}" value="{{ old('customer_id') }}">
                                @if (isset($old['customer_id']) && $old['customer_id'] != '')
                                    <option value="{{ $old['customer_id']->id }}">
                                        {{ $old['customer_id']->name }}
                                    </option>
                                @endif
                            </select>
                            @error('customer_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                            <div class="customer-card"></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Product Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="modal-body">
                        <div id="addRow" class="col">
                            <div class="row justify-content-md-center">
                                <div class="input-field col m2 s3">
                                    <label class="form-label required">Category</label>
                                    <select id="category_id" name="category_id" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Category" data-option-url="{{ route('admin_options_categories') }}">

                                    </select>
                                    </select><span class="red-star"></span>
                                    <div id="category_id-error-div" class="div_erro"></div>
                                </div>
                                <div class="input-field col m2 s3">
                                    <label class="form-label required">Product</label>
                                    <select id="product_id" name="product_id" class="form-select" data-allow-clear="true" data-select2-filter=@json(['category_id' => ['selector' => '#category_id']]) data-placeholder="Select Product" data-kt-select2="true" data-server="true" data-option-url="{{ route('admin_options_categoryBasedProducts') }}" value="{{ old('product_id') }}">
                                    </select>
                                    <div id="product_id-error-div" class="div_erro"></div>

                                </div>
                                <div class="input-field col m2 s3">
                                    <label class="form-label required">Quntity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control mb-2" placeholder="Enter Quantity" />
                                    <div id="quantity-error-div" class="div_erro"></div>

                                </div>
                                <div class="input-field col m3 s3">
                                    <button class="addProductBtn btn btn-success mt-7" type="button" data-product-details-url={{ route('admin_products_details') }}>
                                        <span>Add </span>
                                    </button>
                                    <div id="add_item-error-div" class="div_erro"></div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <table class="table" id="orderItems">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>quantity</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4 deliveryaddress">
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-5 gap-md-4 address-section shipping-address-section mt-3" id="shipping-address-section">
                        <div class=" fs-3 fw-bold mb-n2">Delivery Details</div>
                        <span id="shippingAddressErrorMsg" style="display:none;" class="text-danger">Add shipping address , or tick the above checkbox</span>

                        <div class="customerAddressCard"></div>
                        <div class="hidden-shipping-div address-div">
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="form-label required">Delivery Details</label>
                                    <input class="form-control" name="shipping_address_address_1" id="shipping_address_address_1" placeholder="Address Line 1" value="{{ old('shipping_address_address_1') }}" />
                                    @error('shipping_address_address_1')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <label class="form-label required">street</label>
                                    <input type="text" class="form-control" name="street" id="street" placeholder="Enter Street" value="{{ old('street') }}" />
                                    @error('street')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <div class="fv-row flex-row-fluid">
                                        <label class=" form-label required">Phone Number</label>
                                        <input type="number" class="form-control" name="shipping_address_contact" id="shipping_address_contact" placeholder="Phone Number" value="{{ old('shipping_address_contact') }}" />
                                        @error('shipping_address_contact')
                                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-light btn-active-light-primary me-2 fv-button-back">Back</button>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit saveOrder">
                    <span class="indicator-label">Place Order</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
