@section('title', 'Import')

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
                <form action="{{ route('admin_data_transfer_import_handle') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label"> Sample File </label>
                        <a href="{{ route('admin_data_transfer_import_dowload_sample') }}">Click Here</a>
                    </div>

                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Select File to Import</label>
                        <div class="input-group">
                            <input type="file" name="file" id="file" class="form-control" aria-describedby="file-icon">
                            <span class="input-group-text" id="file-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Set the Entity Type.</div>
                        @error('file')
                            <div class="invalid-feedback"> {{ $message }} </div>
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
