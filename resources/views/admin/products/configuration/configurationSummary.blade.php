<h2>Step 3: Summary</h2>
<p>Here are the products you're about to create.</p>

<div class="mb-10"></div>



<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
        <thead>
            <tr class="fw-bold text-muted">
                <th class="w-25px">
                    <span class="text-muted me-2 fs-7 fw-bold">#</span>
                </th>
                <th class="min-w-200px">Sku</th>
                <th class="min-w-150px">Name</th>
                <th class="min-w-150px">Attributes</th>
                <th class="min-w-150px">Price</th>
            </tr>
        </thead>
        <tbody>
            @php
                $key = 1;
            @endphp
            @foreach ($productArray as $product)
                <tr>
                    <td>
                        <span class="text-muted me-2 fs-7 fw-bold">{{ $key++ }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="d-flex justify-content-start flex-column">
                                <a href="" class="text-dark fw-bold text-hover-primary fs-6">{{ $productSku . '-' . $product['attribute_values'] }}</a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="#" class="text-dark fw-bold text-hover-primary d-block fs-6">{{ $productName . '-' . $product['attribute_values'] }}</a>
                    </td>
                    <td>
                        <div class="d-flex flex-column w-100 me-2">
                            <span class="text-muted me-2 fs-7 fw-bold">{{ $product['attribute_names'] }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column w-100 me-2">
                            <span class="text-muted me-2 fs-7 fw-bold">{{ $productPrice }}</span>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
