<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold"> Product Details</h2>
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
                @if ($product)
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header" data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">




                        <div class="row">
                            <div class="col-4">
                                <img src="{{ $image }}" width="150px" class="list-image">
                            </div>
                            <div class="col-4">
                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        Product :
                                    </label>
                                    <div class="">
                                        {{ $product ? $product->name : '' }}
                                    </div>
                                </div>
                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        SKU :
                                    </label>
                                    <div class="">
                                        {{ $product ? $product->sku : '' }}
                                    </div>
                                </div>
                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        Status :
                                    </label>
                                    <div class="">
                                        {{ $product ? $product->status : '' }}
                                    </div>
                                </div>
                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        Type :
                                    </label>
                                    <div class="">
                                        @if ($product)
                                            @switch($product->type)
                                                @case('configurable_product')
                                                    @php $htmlClass = 'Configurable Product'; @endphp
                                                @break

                                                @case('virtual_product')
                                                    @php $htmlClass = 'Virtual Product'; @endphp
                                                @break

                                                @case('simple_product')
                                                    @php $htmlClass = 'Simple Product'; @endphp
                                                @break

                                                @case('grouped_product')
                                                    @php $htmlClass = 'Grouped Product'; @endphp
                                                @break

                                                @case('bundle_product')
                                                    @php $htmlClass = 'Bundle Product'; @endphp
                                                @break

                                                @case('bundle_product')
                                                    @php $htmlClass = 'Dundle Product'; @endphp
                                                @break

                                                @default
                                                    @php $htmlClass = 'Simple Product'; @endphp
                                            @endswitch
                                            {{ $htmlClass }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        Base Price :
                                    </label>
                                    <div class="">
                                        {{ $product ? $product->price : 00.0 }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">

                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        <span class="">Stock : </span>
                                    </label>
                                    <div class="">
                                        <span class="">{{ $product ? $product->productInventory->quantity : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        Seller :
                                    </label>
                                    <div class="">
                                        @if ($product)
                                            @if ($product->customer)
                                                {{ $product->customer->name }}
                                            @else
                                                Foodovity <i class="bi bi-patch-check-fill text-primary"></i>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        Catagory :
                                    </label>
                                    <div class="">
                                        @if ($product)
                                            @if ($product->categories)
                                                @foreach ($product->categories as $items)
                                                    <span class="">{{ $items->category->name }}</span>,
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 fv-row float-start">
                                    <label class="fw-bold float-start px-2 ps-0">
                                        Description :
                                    </label>
                                    <div class="">
                                        {!! $product->description !!}
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                @else
                    Currently This Product Is Not Active
                @endif

            </div>
        </div>
    </div>
</div>
