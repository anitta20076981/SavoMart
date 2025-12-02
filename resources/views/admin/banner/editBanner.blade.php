@section('title', 'Edit Banner')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/banner/editBanner.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/banner/editBanner.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <form novalidate="novalidate" id="bannerForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_banner_update_banner') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $banner->id }}">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Status</h2>
                        </div>
                        <div class="card-toolbar">
                            @if ($banner->status == 'active')
                                <div class="rounded-circle bg-success w-15px h-15px" id="banner_status"></div>
                            @else
                                <div class="rounded-circle bg-danger w-15px h-15px" id="banner_status"></div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="banner_status_select">
                            <option></option>
                            <option value="active" @if ($banner->status == 'active') selected @endif>Active</option>
                            <option value="inactive" @if ($banner->status == 'inactive') selected @endif>Inactive</option>
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
                                <h2>Banner Details</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="required form-label">Name</label>
                                <input type="text" name="name" class="form-control mb-2" placeholder="Name" value="{{ old('name', $banner->name) }}">
                                @error('name')
                                    <div class="invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="fv-row mb-2">
                                <label class="form-label">Banner Images</label>
                                <div id="image-dropzone" data-kt-dropzone-input="true" class="dropzone" data-banner_id="{{ $banner->id }}" data-action-url="{{ route('admin_banner_image_save') }}" data-fetchable="true" data-fetch-url="{{ route('admin_banner_fetch_image', ['banner_id' => $banner->id]) }}" data-delete-url="{{ route('admin_banner_image_delete') }}" data-content="true" data-link-update-url="{{ route('admin_banner_link_update') }}">
                                    <div class="dz-message needsclick">
                                        <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                        <div class="ms-4">
                                            <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted fs-7">Set Banner Image.</div>
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <label class="required form-label">Banner Section</label>
                                <select id="banner_section_id" name="banner_section_id" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-server="true" data-placeholder="Select Banner Section" data-option-url="{{ route('admin_options_banner_section') }}">
                                    @if (isset($bannerSection))
                                        <option value="{{ $bannerSection->id }}">
                                            {{ $bannerSection->name }}</option>
                                    @endif
                                </select>
                                @error('banner_section_id')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_banner_list') }}" class="btn btn-light me-5">Cancel</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
