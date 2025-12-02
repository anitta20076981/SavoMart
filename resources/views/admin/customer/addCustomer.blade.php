@section('title', 'Add Customer')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin/customer/addCustomer.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/customer/addCustomer.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="container-xxl">
            <form novalidate="novalidate" id="customerForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_customer_create') }}" enctype="multipart/form-data" method="POST">
                @csrf

                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#customer-data">General</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#customer-account-details">Account</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#customer-details">Details</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="customer-data" role="tab-panel">
                            <div class="row">
                                <div class="d-flex flex-column gap-7 gap-lg-10 col-md-4">
                                    <div class="card card-flush py-4">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Profile Picture</h2>
                                            </div>
                                        </div>
                                        <div class="card-body text-center pt-0">
                                            <div class="fv-row fv-plugins-icon-container">
                                                <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                                    <div class="image-input-wrapper w-150px h-150px"></div>
                                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                                                        <i class="bi bi-pencil-fill fs-7"></i>
                                                        <input type="file" name="profile_image" accept=".png, .jpg, .jpeg">
                                                        <input type="hidden" name="profile_image_remove">
                                                    </label>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel" data-kt-initialized="1">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                </div>
                                                <div class="text-muted fs-7">Set the profile picture. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                                                @error('profile_image')
                                                    <div class="invalid-feedback"> {{ $message }} </div>
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
                                                <div class="rounded-circle bg-success w-15px h-15px" id="customer_status"></div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="customer_status_select">
                                                <option value="active" @if (old('status') == 'active') selected @endif>Active</option>
                                                <option value="inactive" @if (old('status') == 'inactive') selected @endif>Inactive</option>
                                            </select>
                                            <div class="text-muted fs-7">Set customer status.</div>
                                        </div>
                                    </div>
                                    {{-- <div class="card card-flush py-4">
                                        <div class="card card-flush py-4">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h2>Is Vendor?</h2>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <select class="form-select mb-2" name="is_vendor" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="is_vendor">
                                                    <option value="0" @if (old('is_vendor') == '0') selected @endif>No</option>
                                                    <option value="1" @if (old('is_vendor') == '1') selected @endif>Yes</option>
                                                </select>
                                                <div class="text-muted fs-7">Set vendor status.</div>

                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="d-flex flex-column gap-7 gap-lg-10 col-md-8">
                                    <div class="card card-flush py-4">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>General</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">First Name</label>
                                                <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" value="{{ old('first_name') }}">
                                                @error('first_name')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Last Name</label>
                                                <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" value="{{ old('last_name') }}">
                                                @error('last_name')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Email</label>
                                                <input type="text" name="email" class="form-control mb-2" placeholder="Email" value="{{ old('email') }}">
                                                @error('email')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Phone</label>
                                                <div class="row">
                                                    {{-- <div class="d-flex flex-column col-md-4">
                                                        <select id="country_code_id" name="country_code_id" class="form-select form-select-solid fw-bold" data-placeholder="Select Country" data-kt-select2="true" data-server="true" data-image-select="true" data-option-url="{{ route('admin_options_country_code') }}">
                                                            <option value="">Select Country</option>
                                                            @if (isset($old['country_code_id']) && $old['country_code_id'] != '')
                                                                <option value="{{ $old['country_code_id']->id }}" selected>
                                                                    <span><img sytle="display: inline-block;" src="{{ asset('images/flags/' . $old['country_code_id']->image) }}" />+{{ $old['country_code_id']->country_code . ' - ' . $old['country_code_id']->short_name }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div> --}}
                                                    <div class="d-flex flex-column col-md-12">
                                                        <input type="number" name="phone" class="form-control mb-2" placeholder="Phone" value="{{ old('phone') }}">
                                                    </div>
                                                    @error('phone')
                                                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Password</label>
                                                <input type="password" id="password" name="password" class="form-control mb-2" placeholder="Password" value="{{ old('password') }}">
                                                @error('password')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Confirm Password</label>
                                                <input type="password" name="confirm_password" id="confirm_password" class="form-control mb-2" placeholder="Confirm Password" value="{{ old('confirm_password') }}">
                                                @error('confirm_password')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="customer-account-details" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <div class="card card-flush py-4">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Customer Account Details</h2>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="mb-2 col-6 fv-row">
                                                <label class=" form-label">Account Number</label>
                                                <input type="text" name="account_no" class="form-control mb-2" placeholder="Account Number" value="{{ old('account_no') }}">
                                                @error('account_no')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row ">
                                                <label class=" form-label">Bank Name</label>
                                                <input type="text" name="bank_name" class="form-control mb-2" placeholder="Bank Name" value="{{ old('bank_name') }}">
                                                @error('account_no')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-2 col-6 fv-row ">
                                                <label class=" form-label">Ifsc</label>
                                                <input type="text" name="ifsc" class="form-control mb-2" placeholder="Ifsc" value="{{ old('ifsc') }}">
                                                @error('ifsc')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row ">
                                                <label class=" form-label">Branch Name</label>
                                                <input type="text" name="branch_name" class="form-control mb-2" placeholder="Branch Name" value="{{ old('branch_name') }}">
                                                @error('branch_name')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-2 col-6 fv-row ">
                                                <label class="form-label">Address</label>
                                                <textarea id="address" name="address" class="form-control mb-2">{{ old('address') }}</textarea>
                                                @error('address')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="customer-details" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <div class="card card-flush py-4">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Customer Details</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        {{-- <div class="row">
                                            <div class="mb-2 col-6 fv-row">
                                                <label class="form-label">Store Name</label>
                                                <input type="text" name="store_name" class="form-control mb-2" placeholder="Store Name" value="{{ old('store_name') }}">
                                                @error('store_name')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row">
                                                <label class="form-label">Business Type</label>
                                                <select class="form-select mb-2" name="bussiness_type" data-control="select2" data-hide-search="true" data-placeholder="Select Business Type" id="bussiness_type">
                                                    <option name="" value="">Select Business Type</option>
                                                    @foreach (businessTypes() as $enumKey => $type)
                                                        <option value="{{ $enumKey }}" name="bussiness_type">{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                                @error('bussiness_type')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        {{-- <div class="row">
                                            <div class="mb-2 col-6 fv-row">
                                                <label class="form-label">Goods Categories</label>
                                                <input data-kt-tagify-input="true" id="goods_categories" name="goods_categories" class="form-control mb-2" value="" />
                                                @error('goods_categories')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row">
                                                <label class=" form-label">Pin Code</label>
                                                <input type="text" name="pin_code" class="form-control mb-2" placeholder="Pin Code" value="{{ old('pin_code') }}">
                                                @error('pin_code')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        <div class="row">
                                            <div class="mb-2 col-6 fv-row">
                                                <label class="form-label">Address Line 1</label>
                                                <textarea id="address_line1" name="address_line1" class="form-control mb-2">{{ old('address_line1') }}</textarea>
                                                @error('address_line1')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row">
                                                <label class=" form-label">Phone Number</label>
                                                <input type="number" name="number" class="form-control mb-2" placeholder="Phone Number" value="{{ old('number') }}">
                                                @error('number')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-2 col-6 fv-row">
                                                <label class="form-label">Street</label>
                                                <textarea id="street" name="street" class="form-control mb-2">{{ old('street') }}</textarea>
                                                @error('street')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- <div class="row">
                                            <div class="mb-2 col-6 fv-row">
                                                <label class=" form-label">Country</label>
                                                <select id="country_id" name="country_id" class="form-select form-select-solid fw-bold" data-placeholder="Select Country" data-kt-select2="true" data-server="true" data-option-url="{{ route('admin_options_countries') }}" value="{{ old('country_id') }}">
                                                    <option value="">Select Country</option>
                                                    @if (isset($old['country_id']) && $old['country_id'] != '')
                                                        <option value="{{ $old['country_id']->id }}" selected>
                                                            {{ $old['country_id']->short_name }}
                                                        </option>
                                                    @endif
                                                </select>
                                                @error('country_id')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row">
                                                <label class=" form-label">State</label>
                                                <select id="state_id" name="state_id" class="form-select form-select-solid fw-bold" data-select2-filter=@json(['country_id' => ['value' => 105]]) data-placeholder="Select State" data-kt-select2="true" data-server="true" data-option-url="{{ route('admin_options_states') }}" value="{{ old('state_id') }}">
                                                </select>
                                                @error('state_id')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        {{-- <div class="row">
                                            <div class="mb-2 col-6 fv-row">
                                                <label class=" form-label">City</label>
                                                <input type="text" name="city" class="form-control mb-2" placeholder="City" value="{{ old('city') }}">
                                                @error('city')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row">
                                                <label class=" form-label">Aadhar Number</label>
                                                <input type="text" name="aadhar_number" class="form-control mb-2" placeholder="Aadhar Number" value="{{ old('aadhar_number') }}">
                                                @error('aadhar_number')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        {{-- <div class="row">
                                            <div class="mb-2 col-6 fv-row">
                                                <label class=" form-label">Pan Number</label>
                                                <input type="text" name="pan_number" class="form-control mb-2" placeholder="Pan Number" value="{{ old('pan_number') }}">
                                                @error('pan_number')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row">
                                                <label class="form-label">Bussiness Name</label>
                                                <input type="text" name="bussiness_name" class="form-control mb-2" placeholder="Bussiness Name" value="{{ old('bussiness_name') }}">
                                                @error('bussiness_name')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        {{-- <div class="row">
                                            <div class="mb-2 col-6 fv-row vendor-details">
                                                <label class="form-label">Vendor Document Type</label>
                                                <select class="form-select form-select-solid fw-bold" data-placeholder="Select Document Type" data-kt-select2="true">
                                                    <option value="">Select Vendor Document Type</option>
                                                    @foreach (vendorDocTypes() as $enumKey => $type)
                                                        <option value="{{ $enumKey }}" name="vendor_document_type">{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                                @error('vendor_document_type')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row vendor-details">
                                                <label class="form-label">Vendor Document</label>
                                                <input type="file" name="vendor_document" class="form-control mb-2" placeholder="Vendor Document" value="">
                                                @error('vendor_document')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        {{-- <div class="row">


                                            <div class="mb-2 col-6 fv-row has_gst">
                                                <label class="form-label">Has Gst</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" name="has_gst" type="checkbox" value="1" @if (old('has_gst')) checked @endif id="has_gst" />
                                                    <label class="form-check-label" for="has_gst">
                                                        Has Gst
                                                    </label>
                                                </div>
                                                @error('has_gst')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>

                                            <div class="mb-2 col-6 fv-row gst-details">
                                                <label class="required form-label">Gst Number</label>
                                                <input type="text" name="gst_number" class="form-control mb-2" placeholder="Gst Number" value="{{ old('gst_number') }}">
                                                @error('gst_number')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        {{-- <div class="row">
                                            <div class="mb-2 col-6 fv-row gst-details">
                                                <label class="required form-label">Gst Certificate</label>
                                                <input type="file" name="gst_certificate" class="form-control mb-2" placeholder="Gst Certificate" value="{{ old('gst_certificate') }}">
                                                @error('gst_certificate')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row gst-details">
                                                <label class="required form-label">Gst Date Of In Corparation</label>
                                                <div class="position-relative d-flex align-items-center w-652px">
                                                    <input class="form-control form-control-transparent fw-bold pe-5" data-kt-date-input="true" data-format="{{ config('date_format.date_only_js') }}" placeholder="Select start date" name="gst_date_of_in_corparation" id="gst_date_of_in_corparation" value="{{ old('gst_date_of_in_corparation') }}" />
                                                    <span class="svg-icon svg-icon-2 position-absolute end-0 ms-4">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                @error('gst_date_of_in_corparation')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-2 col-6 fv-row nonGst_reason_for_exemption">
                                                <label class="form-label">Non Gst Reason For Exemption</label>
                                                <textarea id="nonGst_reason_for_exemption" name="nonGst_reason_for_exemption" class="form-control mb-2">{{ old('nonGst_reason_for_exemption') }}</textarea>
                                                @error('nonGst_reason_for_exemption')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-6 fv-row">
                                                <label class="form-label">Signature</label>
                                                <input type="file" name="signature" class="form-control mb-2" placeholder="Vendor Document" value="">
                                                @error('signature')
                                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Tab content-->
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
        </div>
    </div>
</x-admin-layout>
