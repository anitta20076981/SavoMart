<div class="d-flex align-items-center">
    <a target="_blank" href="{{ route('admin_products_edit', ['id' => $ordersItem->product->id]) }}" class="symbol symbol-50px">
        @if (!isset($ordersItem->product->productThumbnail->image_path))
            <img alt="Logo" src="{{ asset('images/admin/logos/logo-small.png') }}" />
        @else
            <span class="symbol-label" style="{{ Storage::disk('foodovity')->url($ordersItem->product->productThumbnail->image_path) }}"></span>
        @endif
    </a>

    <div class="ms-5">
        <a target="_blank" href="{{ route('admin_products_edit', ['id' => $ordersItem->product->id]) }}" class="fw-bold text-gray-600 text-hover-primary">{{ $ordersItem->product->name }}</a>
    </div>
</div>
