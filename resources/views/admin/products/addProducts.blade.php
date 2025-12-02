@section('title', 'Add Products')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/products/addProducts.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/products/addProducts.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="addProductForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework stepper stepper-pills stepper-column stepper-multistep" action="{{ route('admin_products_save') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Thumbnail</h2>
                    </div>
                </div>
                <div class="card-body text-center pt-0">
                    <div class="fv-row fv-plugins-icon-container">
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="thumbnail" accept=".png, .jpg, .jpeg">
                                <input type="hidden" name="thumbnail_remove">
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Set the thambnail. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                        @error('thumbnail')
                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
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
                                    <input type="text" name="sku" id="product-sku" class="form-control mb-2" placeholder="SKU Number" value="{{ old('sku') }}" data-sku-unique-url="{{ route('admin_products_sku_validation') }}" />
                                    <div class="text-muted fs-7">Enter the product SKU.</div>
                                    @error('sku')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="mb-10 fv-row">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="required form-label">Product Name (English)</label>
                                            <input type="text" name="productName" id="pruduct-name" class="form-control mb-2" placeholder="Product name in English" value="{{ old('productName') }}" />
                                            <div class="text-muted fs-7">A product name is required and recommended to be unique.</div>
                                            @error('productName')
                                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="required form-label">Product Name (Arabic)</label>
                                            <input type="text" name="name_ar" id="name_ar" class="form-control mb-2" placeholder="Product name in Arabic" value="{{ old('name_ar') }}" />
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
                                        <textarea id="description" name="description" data-kt-tinymce-editor="true" data-kt-initialized="false" class="form-control min-h-200px mb-2"> {{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                        <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Description (Arabic)</label>
                                        <textarea id="description_ar" name="description_ar" data-kt-tinymce-editor="true" data-kt-initialized="false" class="form-control min-h-200px mb-2"> {{ old('description_ar') }}</textarea>
                                        @error('description_ar')
                                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                        <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Attributes</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div id="attributes_container"></div>
                            </div>
                        </div>

                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Media</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <label class="form-label">Product Images/Video</label>
                                <div id="image-dropzone" class="dropzone" data-kt-dropzone-input="true" data-action-url="{{ route('admin_products_image_save') }}" data-delete-url="{{ route('admin_products_image_delete') }}" data-accepted-file=".mp4,.mkv,.avi,.png, .jpg, .jpeg">
                                    <div class="dz-message needsclick">
                                        <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                        <div class="ms-4">
                                            <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
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
                                    <input type="number" name="quantity" id="quantity" class="form-control mb-2" placeholder="" value={{ old('quantity') ? old('quantity') : 0 }} min="0" />
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
                                    <input type="number" name="price" id="pruduct-base-price" id="price" class="form-control mb-2" placeholder="Product price" value="{{ old('price', 0) }}" />
                                    <div class="text-muted fs-7">Set the product price.</div>
                                    @error('price')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="fv-row">
                                    <label class="fs-6 fw-semibold mb-2 col-12">Special Discount</label>
                                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                        <div class="col">
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start p-6" data-kt-button="true">
                                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="discount_option" value="no_discount" checked="checked" />
                                                </span>
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bold text-gray-800 d-block">No Discount</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col">
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="discount_option" value="percentage" />
                                                </span>
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bold text-gray-800 d-block">Percentage %</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col">
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                    <input class="form-check-input" type="radio" name="discount_option" value="fixed_price" />
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
                                <div class="d-none mb-12 fv-row noUi-container" id="add_product_discount_percentage">
                                    <label class="form-label">Set Discount Percentage</label>
                                    <div class="d-flex flex-column text-center mb-5">
                                        <div class="d-flex align-items-start justify-content-center mb-7">
                                            <span class="fw-bold fs-3x" data-kt-noUiSlider-span="true" id="discount_percentage_slider">0</span>
                                            <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                                        </div>
                                        <div data-kt-noUiSlider="true" class="noUi-sm"></div>
                                        <input type="hidden" data-kt-noUiSlider-value="true" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage') }}" />
                                    </div>
                                    <div class="text-muted fs-7">Set a percentage discount to be applied on this product.</div>
                                </div>

                                <div class="row">
                                    <div class="d-none mb-10 fv-row col-6" id="add_product_discount_fixed">
                                        <label class="form-label">Fixed Discounted Price</label>
                                        <input type="number" name="discounted_price" id="discounted_price" value="0" class="form-control mb-2" placeholder="Discounted price" />
                                        <div Id="discounted_price-error-div" class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                    <div class="d-none mb-10 fv-row col-6" id="add_product_special_price">
                                        <label class="form-label">Special Price</label>
                                        <input type="number" name="special_price" id="special_price" readonly class="form-control mb-2" placeholder="Special price" />
                                    </div>
                                    <div class="d-none col-12" id="add_product_discount_date">
                                        <div class="row">

                                            <div class="col-6 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">From Date</label>
                                                <input class="form-control flatpickr-input active" data-kt-date-input="true" data-kt-time-enabled="true" data-kt-initialized="false" data-format="{{ config('date_format.date_time_store') }}" placeholder="Select From date" name="special_price_from" id="special_price_from" value="{{ old('special_price_from') }}" />
                                                @error('special_price_from')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>

                                            <div class="col-6 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">To Date</label>
                                                <input class="form-control flatpickr-input active" data-kt-date-input="true" data-kt-time-enabled="true" data-kt-initialized="false" data-format="{{ config('date_format.date_time_store') }}" placeholder="Select to date" name="special_price_to" id="special_price_to" value="{{ old('special_price_to') }}" data-mindate="{{ date(config('date_format.date_only_store')) }}" />
                                                @error('special_price_from')
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
                                <div id="categoryTreeDiv" data-tree-load-url="{{ route('admin_products_category_tree_load') }}">
                                    @include('admin.category.treeForm', ['selectedCategories' => old('categories', [])])
                                </div>
                                <input type="checkbox" name="categories[]" data-error="#category_id-error-div" hidden class="tree-checkbox category_0 category-tree" id="category_id">
                            </div>
                            @error('categories')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="addProductAdvanced" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <input type="hidden" value="" id="current_varient_attributes">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Configurations </h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>Configurable products allow customers to choose options (Ex: shirt color). You need to create a simple product for each configuration (Ex: a product for each color).</p>
                                <button type="button" data-kt-load-drawer="true" data-url="{{ route('admin_products_configuration_form') }}" data-drawer-parameters=@json(['attribute_set_id' => ['selector' => '#product_attribute_set_select']]) class="btn btn-primary configurable-drawer-button" data-drawer-id="configuration-drawer">Create Configurations</button>
                                <div id="addVariationContainer" data-url="{{ route('admin_products_add_variation_list') }}"></div>
                                <input type="hidden" data-edit-product-section="0" class="is-edit">
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Related Products </h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <select id="related_product_id" name="related_product_id[]" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Product" data-option-url="{{ route('admin_options_products') }}" data-multiple="true" multiple>
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
                                    <input class="form-check-input" name="featured_product" type="checkbox" value="1" @if (old('featured_product')) checked @endif id="featured_product" />
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
                                    With in <input class="form-control" name="delivery_expected_time" type="number" id="delivery_expected_time" />
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
                <button type="button" class="btn btn-light btn-active-light-primary me-2 productSave">Back</button>

                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>

</x-admin-layout>
