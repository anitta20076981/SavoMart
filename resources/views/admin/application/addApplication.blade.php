@section('title', 'Add Application')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/application/addApplication.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/application/addApplication.js') }}"></script>
@endpush

<x-admin-layout>

    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="applicationForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_application_create') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Logo</h2>
                    </div>
                </div>
                <div class="card-body text-center pt-0">
                    <div class="fv-row fv-plugins-icon-container">
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="logo" accept=".png, .jpg, .jpeg">
                                <input type="hidden" name="logo_remove">
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Set the profile picture. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                        @error('logo')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Application Details</h2>
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
                        <label class="required form-label">Description</label>
                        <textarea id="description" name="description" data-kt-tinymce-editor="true" data-kt-initialized="false" class="min-h-200px mb-2"></textarea>
                        @error('description')
                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-light btn-active-light-primary me-2 fv-button-back">Back</button>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
