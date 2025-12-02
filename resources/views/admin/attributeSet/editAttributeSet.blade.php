@section('title', 'Edit Attribute Set')

@push('vendor-style')
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/libs/bs-stepper/bs-stepper.css') }}" />
@endpush

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/attributeSet/editAttributeSet.css') }}">
@endpush

@push('script')
<script src="{{ mix('js/admin/attributeSet/editAttributeSet.js') }}"></script>
@endpush

<x-admin-layout>

    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="attributeSetForm" class="form flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_attribute_set_update') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $attributeSet->id }}">
        <div class="d-flex">
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
                            <option value="active" @if (old('status', $attributeSet->status) == 'active') selected @endif>Active</option>
                            <option value="inactive" @if (old('status', $attributeSet->status) == 'inactive') selected @endif>Inactive</option>
                        </select>
                        <div class="text-muted fs-7">Set the attribute status.</div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="d-flex card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Attribute Details</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <label class="required form-label">Name</label>
                            <input type="text" name="name" class="form-control mb-2" placeholder="Name" value="{{ old('name', $attributeSet->name) }}" @if ($attributeSet->id == 1) readonly @endif>
                            @error('name')
                            <div class="alert alert-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card card-flush py-4" id="attribute_set_option_container">
            <div class="card-header">
                <div class="card-title">
                    <h2>Manage Options</h2>
                </div>
            </div>
            <div class="card-body pt-0">

                <div class="row row-cols-lg-2 g-10">
                    <div class="col">
                        <div class="card card-bordered">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Assigned Attributes</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-1 g-10 min-h-200px draggable-zone" tabindex="0" data-kt-drag-zone='assigned'>
                                    @foreach ($attributeSet->attributes as $attribute)
                                    <div class="col draggable w-auto draggable-handle" tabindex="0" data-kt-drag-attribute-value="{{ $attribute->id }}" data-kt-drag-attribute-zone='assigned'>
                                        <div class="card bg-light-success">
                                            <div class="card-header" style="padding: 0;padding-left: 10px; min-height:auto;">
                                                <div class="card-title">
                                                    <h3 class="card-label" style="margin: 0;font-size:14px;">{{ $attribute->name }}</h3>
                                                    <input type="hidden" name="assigned_attributes[]" id="attribute-input-{{ $attribute->id }}" value="{{ $attribute->id }}">
                                                </div>
                                                <div class="card-toolbar">
                                                    <a href="#" class="btn btn-hover-light-primary draggable-handle" style="padding:0;">
                                                        <span class="svg-icon">
                                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor"></path>
                                                                <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-bordered">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Unassigned Attributes</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-1 g-10 min-h-200px draggable-zone" tabindex="0" data-kt-drag-zone='un-assigned'>
                                    @if (isset($unAssignedAttributes) && $unAssignedAttributes)
                                    @foreach ($unAssignedAttributes as $attribute)
                                    <div class="col draggable w-auto draggable-handle" tabindex="0" data-kt-drag-attribute-value="{{ $attribute->id }}" data-kt-drag-attribute-zone='un-assigned'>
                                        <div class="card bg-light-danger">
                                            <div class="card-header" style="padding: 0;padding-left: 10px; min-height:auto;">
                                                <div class="card-title">
                                                    <h3 class="card-label" style="margin: 0;font-size:14px;">{{ $attribute->name }}</h3>
                                                </div>
                                                <div class="card-toolbar">
                                                    <a href="#" class="btn btn-hover-light-primary draggable-handle" style="padding:0;">
                                                        <span class="svg-icon">
                                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor"></path>
                                                                <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="justify-content-end text-end mt-4">
            <a href="{{ route('admin_attribute_set_list') }}" class="btn btn-light me-5">Cancel</a>
            <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                <span class="indicator-label">Save Changes</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>

</x-admin-layout>