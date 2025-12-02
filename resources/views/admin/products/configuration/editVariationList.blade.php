<table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_ecommerce_sales_table">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0" style="background: #e3e4ea2e;">
            <th>Image</th>
            <th class="p-5">Sku</th>
            <th>Name</th>
            <th>Attributes</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Delete</th>
        </tr>
        </tr>
    </thead>
    <tbody class="fw-semibold text-gray-600">
        @foreach ($productArray as $key => $product)
            <tr data-variation-row="{{ $key }}">
                <td style="max-width:100%">
                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                        <div class="image-input-wrapper w-50px h-50px"></div>
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" style="left: 76%;top: 15px;" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" name="product_variations[{{ $key }}][thumb]" accept=".png, .jpg, .jpeg">
                            <input type="hidden" name="product_variations[{{ $key }}][thumb_remove]">
                        </label>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel" data-kt-initialized="1">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                    </div>
                </td>
                <td class="p-5 fv-row fv-plugins-icon-container">
                    <input type="text" class="form-control" value="{{ $productSku . '-' . $product['attribute_values'] }}" name="product_variations[{{ $key }}][sku]" data-fv-not-empty="true" data-fv-not-empty___message="The sku is required">
                    <input type="hidden" name="product_variations[{{ $key }}][attribute_ids]" value="{{ $product['attribute_ids'] }}">
                    <input type="hidden" name="product_variations[{{ $key }}][attribute_values_ids]" value="{{ $product['attribute_values_ids'] }}">
                </td>
                <td class="fv-row fv-plugins-icon-container">
                    <input type="text" class="form-control" value="{{ $productName . '-' . $product['attribute_values'] }}" name="product_variations[{{ $key }}][name]" data-fv-not-empty="true" data-fv-not-empty___message="The name is required">
                </td>
                <td>
                    {{ $product['attribute_names'] }}
                </td>
                <td><input type="number" class="form-control" value="{{ $productPrice }}"name="product_variations[{{ $key }}][price]"></td>
                <td><input type="number" class="form-control" value="{{ $productQty }}" placeholder="Quantity" name="product_variations[{{ $key }}][qty]"></td>
                <td>
                    <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" aria-label="Select example" name="product_variations[{{ $key }}][status]">
                        <option value="publish" selected>Publish</option>
                        <option value="draft">Draft</option>
                    </select>
                </td>
                <td>
                    <button type="button" data-variation-row-delete="{{ $key }}" class="btn btn-sm btn-icon btn-light-danger">
                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1" transform="rotate(-45 7.05025 15.5356)" fill="currentColor"></rect>
                                <rect x="8.46447" y="7.05029" width="12" height="2" rx="1" transform="rotate(45 8.46447 7.05029)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
