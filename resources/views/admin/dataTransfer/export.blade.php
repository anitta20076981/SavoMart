@section('title', 'Export')

@push('style')
    <link rel="stylesheet" href="{{ mix('css/admin/dashboard/home.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/data/import.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 w-50">
        <div class="card card-flush py-4">
            <div class="card-header">
            </div>
            <div class="card-body pt-0" >
                <form action="{{ route('admin_data_transfer_export_handle') }}" method="POST">
                    @csrf

                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Entity Type</label>
                        <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="-- Select an option --" id="entity_type" name="entity_type">
                            <option disabled selected>-- Select an option --</option>
                            <option value="1">Products</option>
                        </select>
                        <div class="text-muted fs-7">Set the Entity Type.</div>
                        @error('entity_type')
                        <div class="alert alert-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Export File Format</label>
                        <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="-- Select an option --" id="export_type" name="export_type">
                            <option disabled selected>-- Select an option --</option>
                            {{-- <option value="csv">CSV</option> --}}
                            <option value="xlsx">Excel</option>
                        </select>
                        <div class="text-muted fs-7">Set the Export File Format.</div>
                        @error('export_type')
                        <div class="alert alert-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin_data_transfer_index') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">
                            Cancel
                        </a>
                        <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                            <span class="indicator-label">
                                Save Changes
                            </span>
                        </button>
                    </div>
                </form>
            </div>
         </div>
    </div>
    <!--end::Row-->
</x-admin-layout>
