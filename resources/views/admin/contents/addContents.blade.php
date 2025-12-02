@section('title', 'Add Content')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/contents/addContents.css') }}">
@endpush

@push('script')
<script src="{{ mix('js/admin/contents/addContents.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <form novalidate="novalidate" id="ContentsForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_contents_save') }}" enctype="multipart/form-data" method="POST">
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
                        <div class="text-muted fs-7">Set the thumbnail. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                        @error('thumbnail')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Status</h2>
                    </div>
                    <div class="card-toolbar">
                        <div class="rounded-circle bg-success w-15px h-15px" id="content_status"></div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="status" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div class="text-muted fs-7">Set the Content status.</div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Content Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Name</label>
                        <input type="text" name="name" class="form-control mb-2" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Title</label>
                        <input type="text" name="title" class="form-control mb-2" placeholder="Title" value="{{ old('title') }}">
                        @error('title')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <!-- <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control mb-2" placeholder="Slug" value="{{ old('slug') }}">
                        @error('slug')
                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div> -->
                    <!-- <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label">Content Category</label>
                        <select data-option-url="{{ route('admin_options_content_categories') }}" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-server="true" name="content_category_id" id="content_category_id" data-placeholder="Select category">
                        </select>
                        @error('content_category_id')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div> -->
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">File</label>
                        <input type="file" name="file" class="form-control mb-2" placeholder="File" value="">
                        @error('file')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label">Content</label>
                        <textarea id="content" name="content" data-kt-tinymce-editor="true" data-kt-initialized="false" class="form-control min-h-200px mb-2">{{ old('content') }}</textarea>
                        @error('content')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>

        </div>
    </form>
</x-admin-layout>