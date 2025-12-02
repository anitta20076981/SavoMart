 <div class="d-flex align-items-center" data-order-filter="product" data-product_id="{{ $orderItem->product_id }}" data-order-product-node="product_{{ $orderItem->product_id }}">
     <a href="{{ route('admin_products_edit', ['id' => $orderItem->product_id]) }}" class="symbol symbol-50px">
         @if (!isset($orderItem->product->productThumbnail->image_path))
         @else
             <span class="symbol-label" style="background-image:url({{ Storage::disk('ashtaal')->url($orderItem->product->productThumbnail->image_path) }}"></span>
         @endif
     </a>
     <div class="ms-5">
         <a href="{{ route('admin_products_edit', ['id' => $orderItem->product_id]) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $orderItem->product->name }}</a>
         <div class="fw-semibold fs-7">Price: $
             <span data-order-filter="price">{{ $orderItem->price }}</span>
         </div>
     </div>
     <div class="ms-5 added-product" hidden data-product-values="true">
         <input type="hidden" data-product-id="true" value={{ $orderItem->product_id }}>
         <input type="hidden" data-order_item_id="true" value={{ $orderItem->id }}>
         <input type="hidden" class="product-price" data-product-price="true" value={{ $orderItem->price }}>
         <input type="number" data-product-qty="true" class="form-control mb-2 w-125px float-end orderQty" readonly placeholder="Qty" value={{ $orderItem->quantity }}>
     </div>
 </div>
