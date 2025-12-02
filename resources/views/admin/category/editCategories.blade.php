@section('title', 'Edit Category')


@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/category/editCategory.css') }}">
@endpush

@push('script')
<script src="{{ mix('js/admin/category/editCategory.js') }}"></script>
@endpush

<x-admin-layout>

    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <form novalidate="novalidate" id="CategoryForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_categories_update') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $category->id }}">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Status</h2>
                    </div>
                    <div class="card-toolbar">
                        @if ($category->status == 'active')
                        <div class="rounded-circle bg-success w-15px h-15px" id="category_status"></div>
                        @else
                        <div class="rounded-circle bg-danger w-15px h-15px" id="category_status"></div>
                        @endif
                    </div>
                </div>
                <div class="card-body pt-0">
                    <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="category_status_select">
                        <option value="active" @if ($category->status == 'active') selected @endif>Active</option>
                        <option value="inactive" @if ($category->status == 'inactive') selected @endif>Inactive</option>
                    </select>
                    <div class="text-muted fs-7">Set category status.</div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_user_overview_tab" aria-selected="true" role="tab">Details</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_user_overview_tab" role="tabpanel">
                    <div class="card card-flush mb-6 mb-xl-9">
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
                                        <input type="text" name="name" class="form-control mb-2" placeholder="Name in English" value="{{ old('name', $category->name) }}">
                                        @error('name')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Name (Arabic)</label>
                                        <input type="text" name="name_ar" class="form-control mb-2" placeholder="Name in Arabic" value="{{ old('name_ar', $category->name_ar) }}">
                                        @error('name_ar')
                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="form-label">Parent Category</label>
                                <select id="parent_category_id" name="parent_category_id" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Parent Category" data-option-url="{{ route('admin_options_categories') }}" value="{{ old('parent_category_id') }}">

                                    @if (isset($categoryParent) && $categoryParent != '')
                                    <option id="parent_category_id" value="{{ $categoryParent->id }}">
                                        {{ $categoryParent->name }}
                                    </option>
                                    @endif
                                </select>
                                @error('parent_category_id')
                                <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="fv-row fv-plugins-icon-container">
                                <label class="form-label">Icon</label><br>
                                <div class="image-input @if (!$category->icon || !Storage::disk('grocery')->exists($category->icon)) image-input-empty @endif image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                    <div class="image-input-wrapper w-150px h-150px" @if ($category->icon && Storage::disk('grocery')->exists($category->icon)) style="background-image:
                                        url({{ Storage::disk('grocery')->url($category->icon) }})" @endif></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="icon" accept=".png,.jpg,.jpeg">
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
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_categories_list') }}" class="btn btn-light me-5">Cancel</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>