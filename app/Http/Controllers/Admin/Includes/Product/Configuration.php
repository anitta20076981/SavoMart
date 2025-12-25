<?php

namespace App\Http\Controllers\Admin\Includes\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

trait Configuration
{
    public function configurationForm(Request $request)
    {
        $attributeSetId = $request->attribute_set_id;

        $response['html'] = (string) view('admin.products.configuration.configurationForm', compact('attributeSetId'));
        $response['scripts'][] = (string) mix('js/admin/products/configurationForm.js');

        return $response;
    }

    public function attributeListData(Request $request)
    {
        $attributeSetId = $request->attribute_set_id;
        $attributes = $this->attributeRepo->getAttributesForProductConfiguration($attributeSetId);
        $dataTableJSON = DataTables::of($attributes)
            ->addIndexColumn()
            ->addColumn('checked_status', function ($attribute) use ($request) {
                return $request->has('checked_attribute_ids') && in_array($attribute->id, $request->checked_attribute_ids) ? 'checked' : '';
            })
            ->make();

        return $dataTableJSON;
    }

    public function attributeOptionList(Request $request)
    {
        $attributeIds = $request->attributeIds;
        $explode_id = explode(',', $attributeIds);
        $attributes = $this->attributeRepo->getAttributes($explode_id);

        $response['html'] = (string) view('admin.products.configuration.attributeValues', compact('attributes'));
        $response['scripts'][] = (string) mix('js/admin/products/configurationForm.js');

        return $response;
    }

    public function attributeConfigurationSummary(Request $request)
    {
        $productName = $request->pruduct_name ?? null;
        $productSku = $request->pruduct_sku ?? null;
        $productPrice = $request->pruduct_price ?? null;

        $optionsArrays = $attributeArray = [];
        $attributes = $request->attribute_options;
        $optionsArrayKey = 0;

        foreach ($attributes as $key => $attributeOptions) {
            foreach ($attributeOptions as $option) {
                $option = $this->attributeRepo->getOptionsWithAttributesForConfiguration($option);

                $optionsArrays[$optionsArrayKey][$option->id] = $option->label;
            }
            $attributeArray[] = $option->attribute->id;
            $optionsArrayKey++;
        }

        $simpleProductAttributesArray = $this->_combinations($optionsArrays);

        $productArray = [];
        $product['attribute_values'] = '';
        $product['attribute_values_ids'] = '';

        foreach ($simpleProductAttributesArray as $productAttribute) {
            $implodeStrings = '';
            $implodeKeys = '';
            $attributeNames = [];
            $attributeIds = [];

            $implodeStrings .= strtolower(implode('-', str_replace(' ', '-', $productAttribute)));
            $implodeKeys .= strtolower(implode(',', array_keys($productAttribute)));

            $explodeKeys = array_map('intval', explode(',', $implodeKeys));
            $attributeDetails = $this->attributeRepo->getAttributeUsingOptionIds($explodeKeys);

            if (isset($attributeDetails)) {
                foreach ($attributeDetails as $attributeDetail) {
                    array_push($attributeNames, $attributeDetail->attribute->name);
                    array_push($attributeIds, $attributeDetail->attribute->id);
                }

                $implodeNames = implode(',', $attributeNames);
                $implodeIds = implode(',', $attributeIds);
            }

            $product['attribute_values'] = $implodeStrings;
            $product['attribute_values_ids'] = $implodeKeys;
            $product['attribute_ids'] = $implodeIds;
            $product['attribute_names'] = $implodeNames;

            array_push($productArray, $product);
        }

        $response['html'] = (string) view(
            'admin.products.configuration.configurationSummary',
            compact('productName', 'productSku', 'productPrice', 'productArray')
        );
        $response['scripts'][] = (string) mix('js/admin/products/configurationForm.js');

        return $response;
    }

    public function addVariationList(Request $request)
    {
        $productName = $request->pruduct_name ?? null;
        $productSku = $request->pruduct_sku ?? null;
        $productPrice = $request->pruduct_price ?? null;
        $productQty = $request->pruduct_qty;

        $optionsArrays = $attributeArray = [];
        $attributes = $request->attribute_options;
        $optionsArrayKey = 0;

        foreach ($attributes as $key => $attributeOptions) {
            foreach ($attributeOptions as $option) {
                $option = $this->attributeRepo->getOptionsWithAttributesForConfiguration($option);

                $optionsArrays[$optionsArrayKey][$option->id] = $option->label;
            }
            $attributeArray[] = $option->attribute->id;
            $optionsArrayKey++;
        }

        $simpleProductAttributesArray = $this->_combinations($optionsArrays);

        $productArray = [];
        $product['attribute_values'] = '';
        $product['attribute_values_ids'] = '';

        foreach ($simpleProductAttributesArray as $productAttribute) {
            $implodeStrings = '';
            $implodeKeys = '';
            $attributeNames = [];
            $attributeIds = [];

            $implodeStrings .= strtolower(implode('-', str_replace(' ', '-', $productAttribute)));
            $implodeKeys .= strtolower(implode(',', array_keys($productAttribute)));

            $explodeKeys = array_map('intval', explode(',', $implodeKeys));
            $attributeDetails = $this->attributeRepo->getAttributeUsingOptionIds($explodeKeys);

            if (isset($attributeDetails)) {
                foreach ($attributeDetails as $attributeDetail) {
                    array_push($attributeNames, $attributeDetail->attribute->name);
                    array_push($attributeIds, $attributeDetail->attribute->id);
                }

                $implodeNames = implode(',', $attributeNames);
                $implodeIds = implode(',', $attributeIds);
            }

            $product['attribute_values'] = $implodeStrings;
            $product['attribute_values_ids'] = $implodeKeys;
            $product['attribute_ids'] = $implodeIds;
            $product['attribute_names'] = $implodeNames;

            array_push($productArray, $product);
        }

        $response['html'] = (string) view(
            'admin.products.configuration.addVariationList',
            compact('productName', 'productSku', 'productPrice', 'productArray', 'productQty')
        );
        $response['scripts'][] = (string) mix('js/admin/products/configurationForm.js');

        return $response;
    }

    public function editVariationList(Request $request)
    {
        $productName = $request->pruduct_name ?? null;
        $productSku = $request->pruduct_sku ?? null;
        $productPrice = $request->pruduct_price ?? null;
        $productQty = $request->pruduct_qty;

        $optionsArrays = $attributeArray = [];
        $attributes = $request->attribute_options;
        $optionsArrayKey = 0;

        foreach ($attributes as $key => $attributeOptions) {
            foreach ($attributeOptions as $option) {
                $option = $this->attributeRepo->getOptionsWithAttributesForConfiguration($option);

                $optionsArrays[$optionsArrayKey][$option->id] = $option->label;
            }
            $attributeArray[] = $option->attribute->id;
            $optionsArrayKey++;
        }

        $simpleProductAttributesArray = $this->_combinations($optionsArrays);

        $productArray = [];
        $product['attribute_values'] = '';
        $product['attribute_values_ids'] = '';

        foreach ($simpleProductAttributesArray as $productAttribute) {
            $implodeStrings = '';
            $implodeKeys = '';
            $attributeNames = [];
            $attributeIds = [];

            $implodeStrings .= strtolower(implode('-', str_replace(' ', '-', $productAttribute)));
            $implodeKeys .= strtolower(implode(',', array_keys($productAttribute)));

            $explodeKeys = array_map('intval', explode(',', $implodeKeys));
            $attributeDetails = $this->attributeRepo->getAttributeUsingOptionIds($explodeKeys);

            if (isset($attributeDetails)) {
                foreach ($attributeDetails as $attributeDetail) {
                    array_push($attributeNames, $attributeDetail->attribute->name);
                    array_push($attributeIds, $attributeDetail->attribute->id);
                }

                $implodeNames = implode(',', $attributeNames);
                $implodeIds = implode(',', $attributeIds);
            }

            $product['attribute_values'] = $implodeStrings;
            $product['attribute_values_ids'] = $implodeKeys;
            $product['attribute_ids'] = $implodeIds;
            $product['attribute_names'] = $implodeNames;

            array_push($productArray, $product);
        }

        $response['html'] = (string) view(
            'admin.products.configuration.editVariationList',
            compact('productName', 'productSku', 'productPrice', 'productArray', 'productQty')
        );
        $response['scripts'][] = (string) mix('js/admin/products/configurationForm.js');

        return $response;
    }

    private function _combinations($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return [];
        }

        $result = [];

        if ($i == count($arrays) - 1) {
            foreach ($arrays[$i] as $vKey => $v) {
                $result[] = [$vKey => $v];
            }

            return $result;
        }

        $tmp = $this->_combinations($arrays, $i + 1);

        foreach ($arrays[$i] as $vKey => $v) {
            foreach ($tmp as $tKey => $t) {
                $result[] = is_array($t) ? [$vKey => $v] + $t : [$vKey => $v, $tKey => $t];
            }
        }

        return $result;
    }

    private function _createProductVariations($request, $productId)
    {

        foreach ($request->product_variations as $variation) {
            $status = $this->_productStatus($variation['qty'] ?? 0);

            $input = [
                'name' => $variation['name'],
                'sku' => $variation['sku'],
                'attribute_set_id' => $request->attribute_set_id,
                'customer_id' => isset($request->customer_id) ? $request->customer_id : null,
                'status' => $variation['status'],
                'visibility' => 'not_visible_individual',
                'price' => $variation['price'] ?? 0,
                'type' => 'virtual_product',
                'parent_id' => $productId,
                'stock_status' => $status,
                'quantity' => $variation['qty'] ?? 0,
                'brand_id' => $request->brand_id,
                'discount_id' => $request->discount_option,
                'special_price_from' => isset($request->special_price_from) ? $request->special_price_from : null,
                'special_price_to' => isset($request->special_price_to) ? $request->special_price_to : null,
                'special_price' => $request->special_price,
                'discount_amount' => $request->discount_option == 'fixed_price' ? $request->discounted_price : 0,
                'discount_percentage' => $request->discount_option == 'percentage' ? $request->discount_percentage : 0,
                'tax_category_id' => isset($request->tax_category_id) ? $request->tax_category_id : null,
            ];

            $product = $this->productRepo->save($input);

            $variationIds = $variation['attribute_values_ids'];

            if ($request->has('categories')) {
                foreach ($request->categories as $key => $category) {
                    $productCategory = [
                        'product_id' => $product->id,
                        'category_id' => $category,
                    ];
                    $this->productRepo->saveProductCategory($productCategory);
                }
            }

            $productInventoryInput = [
                'product_id' => $product->id,
                'quantity' => $variation['qty'] ?? 0,
                'min_salable_quantity' => '0',
                'max_salable_quantity' => '0',
            ];

            $this->_createProductVariationsAttributes($variationIds, $product->id, $productId);

            if (isset($variation['thumb']) && $variation['thumb'] != '') {
                $filePath = 'products/thumbnail';
                $productImages = [
                    'product_id' => $product->id,
                    'image_path' => Storage::disk('savomart')->putFile($filePath, $variation['thumb']),
                    'image_role' => 'THUMBNAIL',
                ];
                $this->productRepo->saveImages($productImages);
            }
        }
    }

    private function _updateProductVariations($request, $productId)
    {
        $variantsIds = $this->productRepo->getVariationsUsingParentId($productId);
        $this->attributeRepo->deleteVarientProductsAttributes($variantsIds);
        // $this->productRepo->deleteProductInventory($variantsIds);
        $deleteVariants = $this->productRepo->deleteVariantsUsingParentId($productId);

        $this->_createProductVariations($request, $productId);
    }

    public function skuValidation(Request $request)
    {
        $varientSku = [];

        if (isset($request->product_variations) && count($request->product_variations)) {
            foreach ($request->product_variations as $variant) {
                $varientSku[] = $variant['sku'];
            }

            $counts = array_count_values($varientSku);

            $hasDuplicates = false;

            foreach ($counts as $value => $count) {
                if ($count > 1) {
                    $hasDuplicates = true;

                    break;
                }
            }

            if ($hasDuplicates) {
                return response()->json([
                    'valid' => false,
                    'message' => 'This sku is already exist in configuration list',
                ]);
            }

            if ($request->sku == $request->varient_sku) {
                return response()->json([
                    'valid' => false,
                    'message' => 'sku should to be unique',
                ]);
            }
        }

        if ($this->productRepo->isSkuExist($request->varient_sku)) {
            return response()->json([
                'valid' => false,
                'message' => 'This sku is already taken',
            ]);
        } else {
            return response()->json(['valid' => true]);
        }
    }
}