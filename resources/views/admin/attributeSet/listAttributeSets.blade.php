@section('title', 'List Attribute Sets')

@push('script')
<script src="{{ mix('js/admin/attributeSet/listAttributeSet.js') }}"></script>
@endpush

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/attributeSet/listAttributeSet.css') }}">
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <x-dt-toolbar>
                @can('attribute_create')
                <a href="{{ route('admin_attribute_set_add') }}" class="btn btn-primary">Add Attribute Set</a>
                @endcan
            </x-dt-toolbar>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="listAttributeSets" data-url="{{ route('admin_attribute_set_table') }}">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th class="min-w-125px">Attribute Set Name</th>
                        <th class="min-w-125px">Status</th>
                        <th class="min-w-100px">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>