@section('title', 'Dashboard')

@push('style')
    <link rel="stylesheet" href="{{ mix('css/admin/dashboard/home.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/dashboard/home.js') }}"></script>
@endpush

<x-admin-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10">
        <!--begin::Col-->
        <div class="col-xl-6 mb-5 mb-xl-10">
            <!--begin::List widget 6-->
            <div class="card card-flush h-md-100">
                <!--begin::Header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Top Selling Products</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">8k social visitors</span>
                    </h3>
                    <!--end::Title-->
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <a href="{{ route('admin_products_list') }}" class="btn btn-sm btn-light">View All</a>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-4">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                    <th class="p-0 w-50px pb-1">ITEM</th>
                                    <th class="ps-0 min-w-140px"></th>
                                    <th class="text-end min-w-140px p-0 pb-1">TOTAL PRICE</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody>
                                {{-- {{ dd($data['toplistingProducts']) }} --}}
                                @foreach ($data['toplistingProducts'] as $items)
                                    <tr>
                                        <td>
                                            <img src="{{ (isset($items->product->thumbnail) && $items->product->thumbnail) != '' ? Storage::disk('savomart')->url($items->product->thumbnail) : asset('images/admin/logos/logo111.jpeg') }}" class="w-50px" alt="" />
                                        </td>
                                        <td class="ps-0">
                                            <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0"> {{ $items->product->name ?? '' }}</a>
                                            <span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Total Quantity: {{ $items->total_quantity }}</span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">{{ $items->all_price }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::List widget 6-->
        </div>
        <!--end::Col-->
    </div>
    <div class="row">
        <div class="">
            <div class="card h-md-100 mb-12 mb-xl-5">
                <div class="card-header align-items-center border-0">
                    <h3 class="fw-bold text-gray-900 m-0">Recent Orders</h3>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Filter</div>
                        </div>

                        <div class="separator mb-3 opacity-75"></div>

                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 recent-order-filter" data-extra-filter="today">Day</a>
                        </div>

                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 recent-order-filter" data-extra-filter="weekly">Weekly</a>
                        </div>

                    </div>

                </div>

                <div class="card-body pt-2">
                    <div class="tab-content">
                        <div class="card-body pt-0">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="listRecentOrders" data-url="{{ route('admin_order_recent_order_table') }}">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th>#</th>
                                        <th class="min-w-125px">Order No</th>
                                        <th class="min-w-125px">Customer Name</th>
                                        <th class="min-w-125px">Order Status</th>
                                    </tr>
                                </thead>

                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($data['recentOrders'] as $index => $items)
                                        <tr>
                                            <td class="ps-0">{{ $index + 1 }}</td>

                                            </td>
                                            <td class="ps-0">
                                                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">{{ $items->order_no }}</a>
                                            </td>
                                            <td>
                                                <span class="text-gray-800 fw-bold d-block fs-6 ps-0  text-start ">{{ $items->customer->first_name }}</span>
                                            </td>
                                            <td class=" text-start pe-0">
                                                <span class="text-gray-800 fw-bold d-block fs-6">{{ $items->status }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </tbody>
                            </table>
                            <a href="{{ route('admin_order_list') }}" class="btn btn-warning btn-sm me-lg-n7 mt-5">Order Management</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
