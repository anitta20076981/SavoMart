@section('title', 'Add Category')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/category/addCategory.css') }}">
@endpush

@push('script')
<script src="{{ mix('js/admin/category/addCategory.js') }}"></script>
@endpush

<x-admin-layout>

    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="categoryForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_categories_save') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Status</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="rounded-circle bg-success w-15px h-15px" id="category_status"></div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="category_status_select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="text-muted fs-7">Set the category status.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Category Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required form-label">Name (English)</label>
                                <input type="text" name="name" class="form-control mb-2" placeholder="Name in English" value="{{ old('name') }}">
                                @error('name')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                            <input type="hidden" name="name_ar" id="name_ar" value="name_ar">

                        </div>
                    </div>
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label">Parent Category</label>
                        <select id="parent_category" name="parent_category" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Parent Category" data-option-url="{{ route('admin_options_categories') }}" value="{{ old('customer_id') }}" data-allow-clear="true">
                        </select>
                        @error('parent_category')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="d-flex">
                        <div class="fv-row fv-plugins-icon-container">
                            <label class="form-label">Icon</label><br>
                            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="icon" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" name="icon_remove">
                                </label>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel" data-kt-initialized="1">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                            </div>
                            <div class="text-muted fs-7">Set icon for category. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                            @error('icon')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_categories_list') }}" class="btn btn-light me-5">Back</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>