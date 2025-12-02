<div class="d-flex align-items-center" data-order-filter="product" data-product_id="{{ $product->id }}" data-order-product-node="product_{{ $product->id }}">
    <a href="javascript:void(0);" kt-load-remote-init="false" kt-load-remote-html="true" data-url="{{ route('admin_products_view', ['id' => $product->id]) }}" class="symbol symbol-50px">
        @if (!isset($product->productThumbnail->image_path) || !Storage::disk('foodovity')->exists($product->productThumbnail->image_path))
            <img alt="Logo" src="{{ asset('images/admin/logos/logo-small.png') }}" />
        @else
            <span class="symbol-label" style="background-image:url({{ Storage::disk('foodovity')->url($product->productThumbnail->image_path) }}"></span>
        @endif
    </a>

    <div class="ms-5">
        <a href="javascript:void(0);" kt-load-remote-init="false" kt-load-remote-html="true" data-url="{{ route('admin_products_view', ['id' => $product->id]) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $product->name }}</a>
        <div class="fw-semibold fs-7">Price:
            <span data-order-filter="price">{{ ($product->final_price) }}</span>
        </div>
        <div class="text-muted fs-7">SKU: {{ $product->sku }}</div>
        {{-- @if ($product->productInventory->min_salable_quantity != 0)
            <div class="text-muted fs-7">Minimum Salable Qty: {{ $product->productInventory->min_salable_quantity == 0 ? '' : $product->productInventory->min_salable_quantity }}</div>
        @endif --}}
        {{-- @if ($product->productInventory->max_salable_quantity != 0)
            <div class="text-muted fs-7">Maximum Salable Qty: {{ $product->productInventory->max_salable_quantity == 0 ? '' : $product->productInventory->max_salable_quantity }}</div>
        @endif --}}
    </div>
    <div class="ms-5 added-product" hidden data-product-values="true">
        <input type="hidden" data-product-id="true" value={{ $product->id }}>
        <input type="hidden" class="product-price" data-product-price="true" value={{ $product->final_price }}>
        <input type="hidden" class="product-base-price" data-product-base-price="true" value={{ $product->price }}>
        <input type="hidden" class="product-min-salable-qty" data-product-min-salable-qty="true" value={{ $product->productInventory->min_salable_quantity }}>
        <input type="hidden" class="product-max-salable-qty" data-product-max-salable-qty="true" value={{ $product->productInventory->max_salable_quantity }}>
        <input type="hidden" class="product-qty" data-product-min-salable-qty="true" value={{ $product->productInventory->quantity }}>

        <input type="number" data-product-qty="true" class="form-control mb-2 w-125px float-end orderQty" placeholder="Qty" value={{ $product->productInventory->min_salable_quantity == 0 ? 1 : $product->productInventory->min_salable_quantity }} min={{ $product->productInventory->min_salable_quantity == 0 ? $product->productInventory->quantity : $product->productInventory->min_salable_quantity }} max={{ $product->productInventory->max_salable_quantity == 0 ? '' : $product->productInventory->max_salable_quantity }}>

    </div>


</div>
