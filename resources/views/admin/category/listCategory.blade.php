@section('title', 'List Categories')

@push('script')
<script src="{{ mix('js/admin/category/listCategory.js') }}"></script>
@endpush

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/category/listCategory.css') }}">
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <x-dt-toolbar>
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                    <div class="px-5 py-3">
                        <div class="fs-4 text-dark fw-bold">Filter Options</div>
                    </div>
                    <div class="separator border-gray-200"></div>
                    <div class="px-5 py-3">
                        <div class="mb-5">
                            <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                            <select id="status" class="form-select" data-kt-select2="true" data-placeholder="Select status" data-allow-clear="true" data-dropdown-parent="#kt-toolbar-filter">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Parent Category</label>
                            <select id="category_id" name="category_id" class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select Parent Category" data-option-url="{{ route('admin_options_categories') }}">
                            </select>
                            @error('category_id')
                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-table-filter="reset">Reset</button>
                            <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-table-filter="filter">Apply</button>
                        </div>

                    </div>
                </div>
                @can('categories_create')
                <a href="{{ route('admin_categories_add') }}" class="btn btn-primary">Add Category</a>
                @endcan
            </x-dt-toolbar>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="listCategories">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th class="min-w-125px">Name</th>
                        <th class="min-w-125px">Icon</th>
                        <th class="min-w-125px">Parent Category</th>
                        <th class="min-w-125px">Status</th>
                        <th class="min-w-100px">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600">
                    @foreach ($categories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>{{ $category->name }}</td>

                        <td>
                            <img src="{{ 
                            $category->icon && Storage::disk('savomart')->exists($category->icon)
                            ? Storage::disk('savomart')->url($category->icon)
                            : asset('images/admin/svg/files/blank-image.svg')
                        }}" width="40" alt="icon">
                        </td>

                        <td>
                            {{ $category->parentCategory ? $category->parentCategory->name : '' }}
                        </td>

                        <td>{{ $category->status }}</td>

                        <td>
                            @can('categories_update')
                            <a href="{{ route('admin_categories_edit', ['id' => $category->id]) }}" class="btn btn-sm btn-light-primary">
                                Edit
                            </a>
                            @endcan

                            @can('categories_delete')
                            <form action="{{ route('admin_categories_delete', ['id' => $category->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-light-danger">
                                    Delete
                                </button>
                            </form>
                            @endcan
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-admin-layout>