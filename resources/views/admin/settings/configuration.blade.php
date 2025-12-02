@section('title', 'Configuration')

@push('style')
    <link rel="stylesheet" href="{{ mix('css/admin/settings/configuration.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/settings/configuration.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" aria-expanded="true">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Configuration</h3>
            </div>
        </div>
        <div id="kt_account_settings_profile_details" class="collapse show">
            <form id="brandingForm" action="{{ route('admin_settings_store_save_settings') }}" method="post" class="form" enctype="multipart/form-data">
                @csrf
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Timzone: {{ config('app.timezone') }}</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <select id="timezone" name="timzone" class="form-select" data-control="select2" data-server="true" data-option-url="{{ route('admin_options_timezones') }}">
                                        @if (isset($old['timezone']) && $old['timezone'])
                                            <option value="{{ $old['timezone']->timezone }}" selected>{{ $old['timezone']->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Country</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <select id="country_id" name="country_id" class="form-select" data-control="select2" data-server="true" data-option-url="{{ route('admin_options_countries') }}">
                                        @if (isset($old['country_id']) && $old['country_id'])
                                            <option value="{{ $old['country_id']->id }}" selected>{{ $old['country_id']->short_name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Currency</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <select id="currency_id" name="currency_id" class="form-select" data-control="select2" data-server="true" data-option-url="{{ route('admin_options_currencies') }}">
                                        @if (isset($old['currency_id']) && $old['currency_id'])
                                            <option value="{{ $old['currency_id']->id }}" selected>{{ $old['currency_id']->name . '-' . $old['currency_id']->symbol }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Date Format</label>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-2 fv-row">
                                    <select name="dateformat[]" class="form-select" data-control="select2">
                                        <option value="d" @if (str_split($settings->get('date_only_js')->value)[0] == 'd') selected @endif>Day</option>
                                        <option value="m" @if (str_split($settings->get('date_only_js')->value)[0] == 'm') selected @endif>Month</option>
                                        <option value="Y" @if (str_split($settings->get('date_only_js')->value)[0] == 'Y') selected @endif>Year</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <select name="dateformat[]" class="form-select" data-control="select2">
                                        <option value="/" @if (str_split($settings->get('date_only_js')->value)[1] == '/') selected @endif>/</option>
                                        <option value="-" @if (str_split($settings->get('date_only_js')->value)[1] == '-') selected @endif>-</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <select name="dateformat[]" class="form-select" data-control="select2">
                                        <option value="d" @if (str_split($settings->get('date_only_js')->value)[2] == 'd') selected @endif>Day</option>
                                        <option value="m" @if (str_split($settings->get('date_only_js')->value)[2] == 'm') selected @endif>Month</option>
                                        <option value="Y" @if (str_split($settings->get('date_only_js')->value)[2] == 'Y') selected @endif>Year</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <select name="dateformat[]" class="form-select" data-control="select2">
                                        <option value="/" @if (str_split($settings->get('date_only_js')->value)[3] == '/') selected @endif>/</option>
                                        <option value="-" @if (str_split($settings->get('date_only_js')->value)[3] == '-') selected @endif>-</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <select name="dateformat[]" class="form-select" data-control="select2">
                                        <option value="d" @if (str_split($settings->get('date_only_js')->value)[4] == 'd') selected @endif>Day</option>
                                        <option value="m" @if (str_split($settings->get('date_only_js')->value)[4] == 'm') selected @endif>Month</option>
                                        <option value="Y" @if (str_split($settings->get('date_only_js')->value)[4] == 'Y') selected @endif>Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
