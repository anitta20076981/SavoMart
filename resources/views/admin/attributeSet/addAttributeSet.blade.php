@section('title', 'Add Attribute Set')

@push('style')
<link href="{{ mix('css/admin/attributeSet/addAttributeSet.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('script')
<script src="{{ mix('js/admin/attributeSet/addAttributeSet.js') }}"></script>
@endpush

<x-admin-layout>

    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="attributeSetForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_attribute_set_create') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Status</h2>
                    </div>
                    <div class="card-toolbar">
                        <div class="rounded-circle bg-success w-15px h-15px" id="attribute_status_indicator"></div>
                    </div>
                </div>
                <div class="card-body pt-0 fv-row">
                    <label class="required form-label">Status</label>
                    <select class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="attribute_status_select" name="status">
                        <option></option>
                        <option value="active" @if (old('status')=='active' ) selected @endif>Active</option>
                        <option value="inactive" @if (old('status')=='inactive' ) selected @endif>Inactive</option>
                    </select>
                    <div class="text-muted fs-7">Set the attribute status.</div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Attribute Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Name</label>
                        <input type="text" name="name" class="form-control mb-2" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                        <div class="alert alert-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_attribute_set_list') }}" class="btn btn-light me-5">Back</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>

</x-admin-layout>