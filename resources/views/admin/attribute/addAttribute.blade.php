@section('title', 'Add Attribute')

@push('style')
<link href="{{ mix('css/admin/attribute/addAttribute.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('script')
<script src="{{ mix('js/admin/attribute/addAttribute.js') }}"></script>
@endpush

<x-admin-layout>

    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <form novalidate="novalidate" id="attributeForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_attribute_create') }}" enctype="multipart/form-data" method="POST">
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
                    <select class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="attribute_status_select" name="status">
                        <option></option>
                        <option value="active" @if (old('status')=='active' ) selected @endif selected>Active</option>
                        <option value="inactive" @if (old('status')=='inactive' ) selected @endif>Inactive</option>
                    </select>
                    <div class="text-muted fs-7">Set the attribute status.</div>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Values Required</h2>
                    </div>
                </div>
                <div class="card-body pt-0 fv-row">
                    <select class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="attribute_required_select" name="is_required">
                        <option></option>
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    <div class="text-muted fs-7">Set the Values Required.</div>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Input Type</h2>
                    </div>
                </div>
                <div class="card-body pt-0 fv-row" data-select2-id="select2-data-138-rfhm">
                    <label class="required form-label">Select a input type</label>
                    <select class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="attribute_input_type_select" name="input_type">
                        <option value="">Select Input Type</option>
                        @foreach (attributeInputtype() as $enumKey => $type)
                        <option @if (old('input_type')==$enumKey) selected @endif value="{{ $enumKey }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    <div class="text-muted fs-7">Set the input type.</div>
                    @error('input_type')
                    <div class="alert alert-danger"> {{ $message }} </div>
                    @enderror
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
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required form-label">Name (English)</label>
                                <input type="text" name="name" class="form-control mb-6" placeholder="Name in English" value="{{ old('name') }}">
                                @error('name')
                                <div class="alert alert-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Name (Arabic)</label>
                                <input type="text" name="name_ar" class="form-control mb-2" placeholder="Name in Arabic" value="{{ old('name_ar') }}">
                                @error('name_ar')
                                <div class="alert alert-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label">Attribute Code</label>
                        <input type="text" name="code" class="form-control mb-2" placeholder="Attribute Code" value="{{ old('code') }}">
                        <div class="text-muted fs-7">This is used internally. Make sure you don't use spaces or more than 20 symbols.</div>
                        @error('code')
                        <div class="alert alert-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4 d-none" id="attribute_option_container">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Manage Options</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <input type="hidden" name="has_options" id="has_options" value="">
                    <div id="attribute-options-repeater">
                        <div class="form-group">
                            <div data-repeater-list="attribute_options">
                                @if (old('attribute_options'))
                                @php
                                $options = old('attribute_options');
                                @endphp
                                @foreach ($options as $data)
                                <div data-repeater-item="">
                                    <div class="form-group row">
                                        <div class="col-md-2 d-none attribute_color_column">
                                            <label class="form-label">Swatch:</label>
                                            <input type="color" class="form-control mb-2 mb-md-0" name="swatch" id="color" value="{{ old('swatch', $data['swatch']) ? old('swatch', $data['swatch']) : '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Label En</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="label" placeholder="English" value="{{ old('label', $data['label']) }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Label Ar</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="label_ar" placeholder="Arabic" value="{{ old('label_ar', $data['label_ar']) }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Value En</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="value" placeholder="English" value="{{ old('value', $data['value']) }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Value Ar</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="value_ar" placeholder="Arabic" value="{{ old('value_ar', $data['value_ar']) }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                <i class="la la-trash-o"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div data-repeater-item="">
                                    <div class="form-group row">
                                        <div class="col-md-2 d-none attribute_color_column">
                                            <label class="form-label">Swatch:</label>
                                            <input type="color" class="form-control mb-2 mb-md-0" name="swatch" id="color" value="#FFFFFF">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Label En</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="label" placeholder="English" value="{{ old('label') }}" required />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Label Ar</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="label_ar" placeholder="Arabic" value="{{ old('label_ar') }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Value En</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="value" placeholder="English" value="{{ old('value') }}" required />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Value Ar</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="value_ar" placeholder="Arabic" value="{{ old('value_ar') }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                <i class="la la-trash-o"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mt-5">
                            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                <i class="la la-plus"></i>Add
                            </a>
                        </div>

                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_attribute_list') }}" class="btn btn-light me-5">Back</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>

</x-admin-layout>