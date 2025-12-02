@section('title', 'Data Transfer')

@push('style')
    <link rel="stylesheet" href="{{ mix('css/admin/dashboard/home.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/dashboard/home.js') }}"></script>
@endpush

<x-admin-layout :breadcrumbs="$breadcrumbs">
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10">
        <div class="row g-6 g-xl-9" style="justify-content: center!important;">
            <div class="col-md-6 col-xl-4">
               <a href="{{ route('admin_data_transfer_import_index') }}" class="card border-hover-primary ">
                  <div class="card-body p-9">
                        <div class="fs-1 fw-bold text-dark text-center">
                            Import        
                        </div>
                  </div>
               </a>
            </div>
            <div class="col-md-6 col-xl-4">
                <a href="{{ route('admin_data_transfer_export_index') }}" class="card border-hover-primary ">
                   <div class="card-body p-9">
                        <div class="fs-1 fw-bold text-dark text-center">
                            Export        
                        </div>
                   </div>
                </a>
             </div>
         </div>
    </div>
    <!--end::Row-->
</x-admin-layout>
