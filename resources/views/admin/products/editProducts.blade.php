@section('title', 'Edit Products')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/products/editProducts.css') }}">
@endpush

@push('script')
<script src="{{ mix('js/admin/products/editProducts.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>


    <form novalidate="novalidate" id="editProductForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework stepper stepper-pills stepper-column stepper-multistep" action="{{ route('admin_products_update') }}" enctype="multipart/form-data" method="POST">
        @csrf

        <input type="hidden" name="id" value="{{ $product->id }}" id="product_id">
        <input type="hidden" name="type" value="{{ $product->type }}" id="product_type">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Thumbnail</h2>
                    </div>
                </div>
                <div class="card-body text-center pt-0">
                    <div class="fv-row fv-plugins-icon-container">
                        <div class="image-input @if (!$thumbnail || !Storage::disk('savomart')->exists($thumbnail)) image-input-empty @endif image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px" @if ($thumbnail && Storage::disk('savomart')->exists($thumbnail)) style="background-image:
                                url({{ Storage::disk('savomart')->url($thumbnail) }})" @endif></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="thumbnail" accept=".png,.jpg,.jpeg">
                                <input type="hidden" name="thumbnail_remove">
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Set the thumbnail. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                        @error('thumbnail')
                        <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4">

                @if ($product->status == 'pending')
                <div class="card-header">
                    <div class="card-title">
                        <h2>Status</h2>
                    </div>
                </div>
                <div class="row m-0 row-cols-2 row-cols-md-2 row-cols-lg-2 row-cols-xl-2">

                    <div class="col"> <button type="button" class="btn btn-success btn-sm  w-100  product-accept-button" id="productAccept" data-url="{{ route('admin_products_accept') }}">
                            Publish </button></div>
                    <div class="col"> <button type="button" class="btn btn-danger btn-sm w-100  product-reject-button" id="productReject" data-url="{{ route('admin_products_accept') }}">
                            Reject</button></div>
                </div>
                @else
                <div class="card-header">
                    <div class="card-title">
                        <h2>Status</h2>
                    </div>
                    <div class="card-toolbar">
                        @if ($product->status == 'suspend')
                        <div class="rounded-circle bg-warning w-15px h-15px " id="product_status"></div>
                        @elseif($product->status == 'draft')
                        <div class="rounded-circle bg-info w-15px h-15px" id="product_status"></div>
                        @elseif($product->status == 'rejected')
                        <div class="rounded-circle bg-danger w-15px h-15px" id="product_status"></div>
                        @else
                        <div class="rounded-circle bg-success w-15px h-15px" id="product_status"></div>
                        @endif
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if ($product->status == 'rejected')
                    <h4>Rejected </h4>
                    <input type="hidden" name="productStatus" id="productStatus" value="{{ $product->status }}">
                    @else
                    <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="productStatus_select" name="productStatus">
                        <option></option>
                        <option value="active" @if ($product->status == 'active') selected @endif>Active</option>
                        <option value="inactive" @if ($product->status == 'inactive') selected @endif>Inactive</option>
                    </select>
                    <div class="text-muted fs-7">Set the product status.</div>
                    @endif
                    @error('productStatus')
                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                @endif

            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#addProductGeneral">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#addProductAdvanced">Advanced</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="addProductGeneral" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>General</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row">
                                <label class="required form-label">Attribute Set</label>
                                <select class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select an option" data-option-url="{{ route('admin_options_attribute_sets') }}" data-form-url="{{ route('admin_products_attribute_form') }}" id="product_attribute_set_select" name="attribute_set_id">
                                    @if (isset($old['attribute_set_id']) && $old['attribute_set_id'] != '')
                                    <option value="{{ $old['attribute_set_id']->id }}" selected>{{ $old['attribute_set_id']->name }}</option>
                                    @endif
                                </select>
                                @error('attribute_set_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="mb-10 fv-row">
                                <label class="required form-label">SKU</label>
                                <input type="text" name="sku" id="product-sku" class="form-control mb-2" placeholder="SKU Number" value="{{ old('name', $product->sku) }}" data-sku-unique-url="{{ route('admin_products_sku_validation') }}" />
                                <div class="text-muted fs-7">Enter the product SKU.</div>
                                @error('sku')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="mb-10 fv-row">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="required form-label">Product Name (English)</label>
                                        <input type="text" name="productName" id="pruduct-name" class="form-control mb-2" placeholder="Product name in English" value="{{ old('name', $product->name) }}" />
                                        <div class="text-muted fs-7">A product name is required and recommended to be unique.</div>
                                        @error('productName')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Product Name (Arabic)</label>
                                        <input type="text" name="name_ar" id="name_ar" class="form-control mb-2" placeholder="Product name in Arabic" value="{{ old('name_ar', $product->name_ar) }}" />
                                        <div class="text-muted fs-7">A product name is required and recommended to be unique.</div>
                                        @error('name_ar')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Description (English)</label>
                                    <textarea id="description" name="description" data-kt-tinymce-editor="true" data-kt-initialized="false" class="form-control min-h-200px mb-2"> {!! $product->description !!}</textarea>
                                    @error('description')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                    <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description (Arabic)</label>
                                    <textarea id="description_ar" name="description_ar" data-kt-tinymce-editor="true" data-kt-initialized="false" class="form-control min-h-200px mb-2"> {!! $product->description_ar !!}</textarea>
                                    @error('description_ar')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                    <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Attributes</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div id="attributes_container"></div>
                            </div>
                        </div> -->
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Media</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="fv-row mb-2">
                                    <label class="form-label">Product Images/Video</label>
                                    <div id="image-dropzone" data-kt-dropzone-input="true" class="dropzone" data-product_id="{{ $product->id }}" data-action-url="{{ route('admin_products_image_save') }}" data-fetchable="true" data-fetch-url="{{ route('admin_products_fetch_image', ['product_id' => $product->id]) }}" data-delete-url="{{ route('admin_products_image_delete') }}" data-accepted-file=".mp4,.mkv,.avi,.png, .jpg, .jpeg">
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
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Inventory</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control mb-2" placeholder="" value="{{ old('quantity', $product->quantity) }}" />
                                    @error('quantity')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Pricing</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Base Price</label>
                                    <input type="number" name="price" id="pruduct-base-price" class="form-control mb-2" placeholder="Product price" value="{{ $product->price }}" />
                                    <div class="text-muted fs-7">Set the product price.</div>
                                    @error('price')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="fv-row">
                                    <label class="fs-6 fw-semibold mb-2 col-12">Special Discount</label>
                                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                        <div class="col">
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="discount_option" value="no_discount" {{ $product->discount_id == 'no_discount' ? 'checked' : '' }} />
                                                </span>
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bold text-gray-800 d-block">No Discount</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col">
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="discount_option" value="percentage" {{ $product->discount_id == 'percentage' ? 'checked' : '' }} />
                                                </span>
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bold text-gray-800 d-block">Percentage %</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col">
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="discount_option" value="fixed_price" {{ $product->discount_id == 'fixed_price' ? 'checked' : '' }} />
                                                </span>
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bold text-gray-800 d-block">Fixed Price</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('discount_option')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="d-none mb-12 fv-row noUi-container" id="edit_product_discount_percentage">
                                    <label class="form-label">Set Discount Percentage</label>
                                    <div class="d-flex flex-column text-center mb-5">
                                        <div class="d-flex align-items-start justify-content-center mb-7">
                                            <span class="fw-bold fs-3x" data-kt-noUiSlider-span="true" id="discount_percentage_slider">0</span>
                                            <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                                        </div>
                                        <div data-kt-noUiSlider="true" class="noUi-sm"></div>
                                        <input type="hidden" data-kt-noUiSlider-value="true" name="discount_percentage" id="discount_percentage" value="{{ $product->discount_percentage }}" />
                                    </div>
                                    <div class="text-muted fs-7">Set a percentage discount to be applied on this product.</div>
                                </div>

                                <div class="row">
                                    <div class="d-none fv-row col-6" id="edit_product_discount_fixed">
                                        <label class="form-label">Fixed Discounted Price</label>
                                        <input type="number" name="discounted_price" id="discounted_price" value="{{ old('discounted_price', $product->discount_amount) }}" class="form-control mb-2" placeholder="Discounted price" />
                                        <div Id="discounted_price-error-div" class="fv-plugins-message-container invalid-feedback"></div>
                                        @error('discounted_price')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                    <div class="d-none fv-row col-6" id="edit_product_special_price">
                                        <label class="form-label">Special Price</label>
                                        <input type="text" name="special_price" id="special_price" class="form-control mb-2" placeholder="Special price" value="{{ $product->special_price }}" readonly />
                                        @error('special_price')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                    <div class="d-none fv-row col-12" id="edit_product_discount_date">
                                        <div class="row">
                                            <div class="col-6 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">From Date</label>
                                                <input class="form-control flatpickr-input active" data-kt-date-input="true" data-kt-time-enabled="true" data-kt-initialized="false" data-format="{{ config('date_format.date_only_store') }}" placeholder="Select From date" name="special_price_from" id="special_price_from" value="{{ old('special_price_from', $product->special_price_from) }}" />
                                                @error('special_price_from')
                                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="col-6 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">To Date</label>
                                                <input class="form-control flatpickr-input active" data-kt-date-input="true" data-kt-time-enabled="true" data-kt-initialized="false" data-format="{{ config('date_format.date_only_store') }}" placeholder="Select to date" name="special_price_to" id="special_price_to" value="{{ old('special_price_to', $product->special_price_to) }}" />
                                                @error('special_price_to')
                                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Categories</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div id="categoryTreeDiv">
                                    @include('admin.category.treeForm', ['selectedCategories' => $selectedCategories])
                                </div>
                                @error('categories')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="addProductAdvanced" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">


                        @if ($product->type != 'virtual_product')
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Variations</h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>Configurable products allow customers to choose options (Ex: shirt color). You need to create a simple product for each configuration (Ex: a product for each color).</p>
                                <button type="button" data-kt-load-drawer="true" data-url="{{ route('admin_products_configuration_form') }}" data-drawer-parameters=@json(['attribute_set_id'=> ['selector' => '#product_attribute_set_select']]) class="btn btn-primary" data-drawer-id="configuration-drawer"> {{ count($product->variations) ? 'Edit' : 'Create' }} Configurations</button>
                                @if (isset($product->variations) && count($product->variations))
                                <input type="hidden" value="{{ $varientAttributeOptions }}" id="available_varient_attribute_options">
                                <input type="hidden" value="{{ $varientAttribute }}" id="available_varient_attributes">
                                <input type="hidden" value="{{ $varientAttribute }}" id="current_varient_attributes">
                                <input type="hidden" data-edit-product-section="1" class="is-edit">
                                <div id="editVariationContainer" data-url="{{ route('admin_products_edit_variation_list') }}"></div>


                                <div class="card card-xxl-stretch mb-5 mt-5 mb-xl-8" id="available_product_varient_table">
                                    <div class="border-0 pt-5">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold fs-3 mb-1">Current Variations</span>
                                            <span class="text-muted mt-1 fw-semibold fs-7">{{ count($product->variations) }} variants in {{ $product->name }}</span>
                                        </h3>
                                    </div>
                                    <div class="py-3">
                                        <div class="table-responsive">
                                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                                <thead>
                                                    <tr class="fw-bold text-muted">
                                                        <th class="w-25px">
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check">
                                                            </div>
                                                        </th>
                                                        <th class="min-w-200px">Name</th>
                                                        <th class="min-w-150px">Sku</th>
                                                        <th class="min-w-150px">Status</th>
                                                        <th class="min-w-150px">Quantity</th>
                                                        <th class="min-w-100px text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (isset($variations) && count($variations))
                                                    @foreach ($variations as $key => $variation)
                                                    <tr data-variation-row="{{ $variation->id }}">
                                                        <input type="hidden" name="available_product_variations[{{ $key }}]" value="{{ $variation->id }}">

                                                        <td>
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input widget-9-check" type="checkbox" value="1">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="symbol symbol-45px me-5">
                                                                    @if ($variation->thumbnail)
                                                                    <img src="{{ Storage::disk('savomart')->url($variation->thumbnail) }}" alt="">
                                                                    @else
                                                                    <img alt="Logo" src="{{ asset('images/admin/logos/logo111.jpeg') }}" class="h-45px logo" />
                                                                    @endif

                                                                </div>
                                                                <div class="d-flex justify-content-start flex-column">
                                                                    <a href="{{ route('admin_products_edit', ['id' => $variation->id]) }}" class="text-dark fw-bold text-hover-primary fs-6">{{ $variation->name }}</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-dark fw-bold d-block fs-6">{{ $variation->sku }}</a>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="d-flex flex-column w-100 me-2">
                                                                <div class="d-flex flex-stack mb-2">
                                                                    <span class=" {{ $variation->status == 'publish' ? 'text-success' : 'text-danget' }} me-2 fs-7 fw-bold">{{ $variation->status }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-dark fw-bold d-block fs-6">{{ $variation->quantity }}</a>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                                <a href="{{ route('admin_products_edit', ['id' => $variation->id]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                                    <span class="svg-icon svg-icon-3">
                                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path opacity="0.3"
                                                                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                                                fill="currentColor"></path>
                                                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                                <a href="javascript:void(0)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-variation-row-delete="{{ $key }}">
                                                                    <span class="svg-icon svg-icon-3">
                                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                                                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                                                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="varientContainer"></div>
                                @else
                                <input type="hidden" value="" id="current_varient_attributes">
                                <input type="hidden" data-edit-product-section="0" class="is-edit">
                                <div id="addVariationContainer" data-url="{{ route('admin_products_add_variation_list') }}"></div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Related Products </h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <select id="related_product_id" name="related_product_id[]" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Product" data-option-url="{{ route('admin_options_products') }}" data-multiple="true" multiple>
                                    @foreach ($product->productRelations as $product)
                                    <option value="{{ $product->id }}" selected> {{ $product->name }} </option>
                                    @endforeach
                                </select>
                                @error('related_product_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>

                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Featured</h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" name="featured_product" type="checkbox" value="1" @if ($product->featuredProduct != null) checked @endif id="featured_product" />

                                    <label class="form-check-label" for="featured_product">
                                        Featured Product
                                    </label>
                                </div>
                                @error('featured_product')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Delivery Expected Time</h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch form-check-custom form-check-solid col-6">
                                    With in <input class="form-control" name="delivery_expected_time" type="number" id="delivery_expected_time" value={{ $product->delivery_expected_time }} />
                                    <label class="form-check-label" for="delivery_expected_time">
                                        Hr
                                    </label>
                                </div>
                                @error('delivery_expected_time')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_products_list') }}" class="btn btn-light me-5">Cancel</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>

</x-admin-layout>