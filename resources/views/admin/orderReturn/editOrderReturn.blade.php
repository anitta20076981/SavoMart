@section('title', 'Edit Order Return')


@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/orderReturn/editOrderReturn.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/orderReturn/editOrderReturn.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8" role="tablist">
            <li class="nav-item ms-auto">
                @if ($orderReturn->status == 'pending')
                    <button class="btn btn-success btn-sm order-return-confirm-status-button" data-status-change-url="{{ route('admin_order_return_status_update', ['id' => $orderReturn->id]) }}" data-status-check="confirmed">
                        Confirmed</button>
                @endif
                @if ($orderReturn->status == 'pending' || $orderReturn->status == 'confirmed')
                    <button class="btn btn-success btn-sm order-return-complete-status-button" data-status-change-url="{{ route('admin_order_return_status_update', ['id' => $orderReturn->id]) }}" data-status-check="completed">
                        Completed</button>
                @endif
                @if ($orderReturn->status == 'pending')
                    <button class="btn btn-danger btn-sm order-return-reject-status-button" data-status-change-url="{{ route('admin_order_return_status_update', ['id' => $orderReturn->id]) }}" data-status-check="rejected">
                        Reject</button>
                @endif
            </li>

        </ul>
    </div>
    <form novalidate="novalidate" id="orderReturnEditForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_order_return_update') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $orderReturn->id }}">
        <input type="hidden" id="order_return_id" name="order_return_id" value="{{ $orderReturn->id }}">

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
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="ash_user_overview_tab" role="tabpanel">
                    <div class="card card-flush mb-6 mb-xl-9">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Return Details</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-6 fv-row ">
                                    <label class="required form-label">Order No</label>
                                    <input type="text" id="order_no" name="order_no" class="form-control mb-2" placeholder="Order No" readonly value={{ $order->order_no }}>
                                    @error('order_no')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <div class="row">
                                <label class=" form-label">Product Details</label>
                                <div class="row row-cols-1 row-cols-xl-2 row-cols-md-2 border border-dashed rounded pt-6 pb-1 px-2 mb-5 mh-300px overflow-scroll" id="order_selected_products">
                                    <div class="col my-2" data-order-filter="product" data-product_id={{ $orderItem->product->id }} data-order-product-node="product_{{ $orderItem->product->id }}">
                                        <div class="d-flex align-items-center border border-dashed rounded p-3 bg-body">
                                            <div class="ms-5">
                                                <a target="_blank" href="{{ route('admin_products_edit', ['id' => $orderItem->product->id]) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $orderItem->product->name }}</a>
                                                <div class="fw-semibold fs-7">Price: {{ config('app.currency.symbol') }}
                                                    <span data-order-filter="price">{{ $orderItem->product->price }}</span>
                                                </div>
                                                <div class="text-muted fs-7">SKU: {{ $orderItem->product->sku }}</div>
                                                <div class="text-muted fs-7">Ordered Qty: {{ $orderItem->quantity }}</div>
                                            </div>

                                        </div>
                                    </div>
                                    <span class="w-100 text-muted d-none product-empty">Select one or more products from the list below by ticking the checkbox.</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-6 fv-row ">
                                    <label class="required form-label">Location</label>
                                    <input type="text" id="location" name="location" class="form-control mb-2" placeholder="Location" value={{ $orderReturn->location }}>
                                    @error('location')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-6 fv-row ">
                                    <label class="required form-label">Reason</label>
                                    <textarea id="reason" name="reason" class="form-control mb-2">{{ $orderReturn->reason }}</textarea>
                                    @error('reason')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Media</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="fv-row mb-2">
                                    <label class="form-label"> Images</label>
                                    <div id="image-dropzone" data-kt-dropzone-input="true" class="dropzone" data-order_return_id="{{ $orderReturn->id }}" data-action-url="{{ route('admin_order_return_image_save') }}" data-fetchable="true" data-fetch-url="{{ route('admin_order_return_fetch_image', ['order_return_id' => $orderReturn->id]) }}" data-delete-url="{{ route('admin_order_return_image_delete') }}">
                                        <div class="dz-message needsclick">
                                            <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                            <div class="ms-4">
                                                <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted fs-7">Set the product media gallery.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_order_return_list') }}" class="btn btn-light me-5">Cancel</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
