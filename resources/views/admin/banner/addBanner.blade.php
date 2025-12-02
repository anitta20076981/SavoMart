@section('title', 'Add Banner')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/banner/addBanner.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/banner/addBanner.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <form novalidate="novalidate" id="bannerForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_banner_create') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Status</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="rounded-circle bg-success w-15px h-15px" id="banner_status"></div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="banner_status_select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="text-muted fs-7">Set the banner status.</div>
                        <div class="d-none mt-10">
                            <label for="banner_status_datepicker" class="form-label">Select publishing date and time</label>
                            <input class="form-control" id="banner_status_datepicker" placeholder="Pick date & time" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Banner Details</h2>
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
                    <div class="fv-row mb-2">
                        <label class="required form-label">Banner Images</label>
                        <div id="image-dropzone" class="dropzone" data-kt-dropzone-input="true" data-action-url="{{ route('admin_banner_image_save') }}" data-link-update-url="{{ route('admin_banner_link_update') }}" data-delete-url="{{ route('admin_banner_image_delete') }}" data-content="true">
                            <div class="dz-message needsclick">
                                <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                </div>
                            </div>
                        </div>
                        @error('images')
                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="text-muted fs-7">Set Banner Image.</div>
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Banner Section</label>
                        <select id="banner_section_id" name="banner_section_id" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-server="true" data-placeholder="Select Banner Section" data-option-url="{{ route('admin_options_banner_section') }}">
                        </select>
                        @error('banner_section_id')
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
