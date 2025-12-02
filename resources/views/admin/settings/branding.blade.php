@section('title', 'Branding')

@push('style')
<link rel="stylesheet" href="{{ mix('css/admin/settings/branding.css') }}">
@endpush

@push('script')
<script src="{{ mix('js/admin/settings/branding.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" aria-expanded="true">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Branding Details</h3>
            </div>
        </div>
        <div id="kt_account_settings_profile_details" class="collapse show">
            <form id="brandingForm" action="{{ route('admin_settings_store_save_settings') }}" method="post" class="form" enctype="multipart/form-data">
                @csrf
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">English Name</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <input type="text" name="company_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="English Name" value="{{ old('company_name', $settings->get('company_name')->value) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Arabic Name</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <input type="text" name="company_name_ar" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Arabic Name" value="{{ old('company_name_ar', $settings->get('company_name_ar')->value) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">English Description</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <textarea name="company_description" class="form-control form-control form-control-solid" placeholder="English Description" data-kt-autosize="true">{{ old('company_description', $settings->get('company_description')->value) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Arabic Description</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <textarea name="company_description_ar" class="form-control form-control form-control-solid" placeholder="Arabic Description" data-kt-autosize="true">{{ old('company_description_ar', $settings->get('company_description_ar')->value) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Fav Icon</label>
                        <div class="col-lg-8">
                            <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                                <div class="image-input-wrapper w-125px h-125px" @if ($settings->get('fav_icon')->value && Storage::disk('grocery')->exists($settings->get('fav_icon')->value)) style="background-image:
                                    url({{ Storage::disk('grocery')->url($settings->get('fav_icon')->value) }})" @endif>
                                </div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="fav_icon" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="fav_icon_remove" />
                                </label>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                @if ($settings->get('fav_icon')->value && Storage::disk('grocery')->exists($settings->get('fav_icon')->value))
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                @endif
                            </div>
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Dark Logo</label>
                        <div class="col-lg-8">
                            <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                                <div class="image-input-wrapper w-125px h-125px" @if ($settings->get('logo_dark')->value && Storage::disk('grocery')->exists($settings->get('fav_icon')->value)) style="background-image:
                                    url({{ Storage::disk('grocery')->url($settings->get('logo_dark')->value) }})" @endif>
                                </div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="logo_dark" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="logo_dark_remove" />
                                </label>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                @if ($settings->get('logo_dark')->value && Storage::disk('grocery')->exists($settings->get('logo_dark')->value))
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                @endif
                            </div>
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Light Logo</label>
                        <div class="col-lg-8">
                            <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                                <div class="image-input-wrapper w-125px h-125px" @if ($settings->get('logo_light')->value && Storage::disk('grocery')->exists($settings->get('fav_icon')->value)) style="background-image:
                                    url({{ Storage::disk('grocery')->url($settings->get('logo_light')->value) }})" @endif>
                                </div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="logo_light" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="logo_light_remove" />
                                </label>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                @if ($settings->get('logo_light')->value && Storage::disk('grocery')->exists($settings->get('logo_light')->value))
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                @endif
                            </div>
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Meta Tags</label>
                        <div class="col-lg-8 fv-row">
                            <textarea name="meta_tags" class="form-control form-control form-control-solid" placeholder="Meta Tags" data-kt-autosize="true">{{ old('meta_tags', $settings->get('meta_tags')->value) }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <input type="text" name="email" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Email" value="{{ old('email', $settings->get('email')->value) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Phone</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <input type="text" name="phone" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Phone" value="{{ old('phone', $settings->get('phone')->value) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">
                        <span class="indicator-label">Save Changes</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>