<div class="modal fade" id="orderAddressEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Update Address</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-modal-action="close">
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 my-7">
                <form novalidate="novalidate" id="orderAddressEditForm" class="form" method="POST" action="{{ route('admin_order_address_update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" id="order_id" value={{ $orderAddress->order_id }}>
                    <input type="hidden" name="type" id="type" value={{ $orderAddress->type }}>

                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-6 fv-row">
                                <label class="required form-label">First Name</label>
                                <input type="text" class="form-control" placeholder="Enter First Name" name="first_name" id="first_name" value="{{ $orderAddress->first_name }}" />
                                @error('first_name')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                                <div id="first_name-error-div"></div>
                            </div>
                            <div class="col-6 fv-row">
                                <label class="required form-label">Last Name</label>
                                <input type="text" class="form-control" placeholder="Enter Last Name" name="last_name" id="last_name" value="{{ $orderAddress->last_name }}" />
                                @error('last_name')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                                <div id="last_name-error-div"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 fv-row">
                                <label class="required form-label">Address</label>
                                <input type="text" class="form-control" placeholder="Enter Street Address" name="street_address" id="street_address" value="{{ $orderAddress->street_address }}" />
                                @error('street_address')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                                <div id="street_address-error-div"></div>
                            </div>
                            <div class="col-6 fv-row">
                                <label class="required form-label">Country</label>
                                <select id="country_id" name="country_id" class="form-select" data-dropdown-parent=".modal" data-kt-select2="true" data-server="true" data-control="select2" data-placeholder="Select Country" data-option-url="{{ route('admin_options_countries') }}" value="{{ old('country') }}">
                                    @if (isset($old['country']) && $old['country'] != '')
                                        <option value="{{ $old['country']->id }}">
                                            {{ $old['country']->short_name }}
                                        </option>
                                    @endif
                                </select>
                                @error('country_id')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                                <div id="country_id-error-div"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 fv-row">
                                <label class="required form-label">State</label>
                                <select id="state_id" name="state_id" class="form-select" data-select2-filter=@json(['country_id' => ['selector' => '#country_id']]) data-dropdown-parent=".modal" data-kt-select2="true" data-server="true" data-placeholder="Select State" data-option-url="{{ route('admin_options_states') }}" value="{{ old('state') }}">
                                    @if (isset($old['state']) && $old['state'] != '')
                                        <option value="{{ $old['state']->id }}">
                                            {{ $old['state']->name }}
                                        </option>
                                    @endif
                                </select>
                                @error('state_id')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                                <div id="state_id-error-div"></div>
                            </div>
                            <div class="col-6 fv-row">
                                <label class="required form-label">City</label>
                                <input type="text" class="form-control" placeholder="Enter City" name="city" id="city" value="{{ $orderAddress->city }}" />
                                @error('city')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                                <div id="city-error-div"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 fv-row">
                                <label class="required form-label">Postal Code</label>
                                <input type="text" class="form-control" placeholder="Enter Postel Code" name="postel_code" id="postel_code" value="{{ $orderAddress->postel_code }}" />
                                @error('postel_code')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                                <div id="postel_code-error-div"></div>
                            </div>
                            <div class="col-6 fv-row">
                                <label class="required form-label">Contact</label>
                                <input type="text" class="form-control" placeholder="Enter Contact" name="contact" id="contact" value="{{ $orderAddress->contact }}" />
                                @error('contact')
                                    <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light btn-active-light-primary me-2 fv-button-back">Back</button> <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                            <span class="indicator-label">Save Changes</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
