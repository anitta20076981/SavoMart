<?php

namespace App\Repositories\Attribute;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\ProductAttribute;
use App\Models\ProductConfigureAttributeValue;
use Illuminate\Database\Eloquent\Builder;

class AttributeRepository implements AttributeRepositoryInterface
{
    public function getForDatatable($data)
    {
        return Attribute::select(['*'])
            ->where(function (Builder $query) use ($data) {
                if ($data['status'] != '') {
                    $query->where('status', '=', $data['status']);
                }
            })->orderBy('id', 'DESC');
    }

    public function createAttribute($input)
    {
        if ($attribute = Attribute::create($input)) {
            return $attribute;
        }

        return false;
    }

    public function updateAttribute(array $input)
    {
        $attribute = Attribute::find($input['id']);
        unset($input['id']);

        if ($attribute->update($input)) {
            return $attribute;
        }

        return false;
    }

    public function getAttribute($attributeId)
    {
        return Attribute::findOrFail($attributeId);
    }

    public function getAttributesForMap($attributeIds)
    {
        return $attributes = Attribute::where('status', 'active')
            ->whereIn('id', $attributeIds)
            ->with('attributeOptions')
            ->get();
    }

    public function getAttributes($attributeIds)
    {
        $attributes = Attribute::where('status', 'active')
            ->whereIn('id', $attributeIds)
            ->with('attributeOptions')
            ->get();

        return $attributes;
    }

    public function getAttributeWithOptionsForConfiguration($attributeId, $optionIds)
    {
        $attribute = Attribute::where('id', $attributeId)
            ->with('attributeOptions', function ($query) use ($optionIds) {
                $query->whereIn('id', $optionIds);
            });

        return $attribute->get();
    }

    public function getOptionsWithAttributesForConfiguration($optionId)
    {
        $attribute = AttributeOption::where('id', $optionId)->with('attribute')->first();

        return $attribute;
    }

    public function getAttributeUsingOptionIds($optionIds)
    {
        $attribute = AttributeOption::whereIn('id', $optionIds)->with('attribute')->get();

        return $attribute;
    }

    public function pluckInputTypeById($key)
    {
        $attributes = Attribute::where('id', $key)->pluck('input_type')->first();

        return $attributes;
    }

    public function createProductAttributes($attributeInputData)
    {
        if ($productAttribute = ProductAttribute::create($attributeInputData)) {
            return $productAttribute;
        }

        return false;
    }

    public function deleteProductConfiguredAttributes($productId)
    {
        return ProductConfigureAttributeValue::where('product_id', $productId)->delete();
    }

    public function updateProductConfiguredAttributes($attributeInputData)
    {
        return ProductConfigureAttributeValue::firstOrCreate($attributeInputData);
    }

    public function updateProductAttributes($attributeInputData)
    {
        $productAttributeGet = ProductAttribute::where('product_id', $attributeInputData['product_id'])
            ->where('attribute_id', $attributeInputData['attribute_id'])->first();
        $productAttribute = ProductAttribute::where('product_id', $attributeInputData['product_id'])
            ->where('attribute_id', $attributeInputData['attribute_id']);

        if ($productAttributeGet) {
            $productAttribute->update(
                [
                    'product_id' => $attributeInputData['product_id'],
                    'attribute_id' => $attributeInputData['attribute_id'],
                    'value' => $attributeInputData['value'],
                ]
            );
        } else {
            $productAttribute->Create(
                [
                    'product_id' => $attributeInputData['product_id'],
                    'attribute_id' => $attributeInputData['attribute_id'],
                    'value' => $attributeInputData['value'],
                ]
            );
        }

        if ($productAttribute) {
            return $productAttribute;
        }

        return false;
    }

    public function getProductAttributeValue($productId, $attributeId)
    {
        $attribute = ProductAttribute::where('product_id', $productId)->where('attribute_id', $attributeId)->first();

        if (isset($attribute->value)) {
            return $attribute->value;
        } else {
            return '';
        }
    }

    public function getParentProductAttributes($parentId, $attributeId)
    {
        $attribute = ProductAttribute::where('product_id', $parentId)->where('attribute_id', '!=', $attributeId)->get();

        return $attribute;
    }

    public function getProductAttributeProductType($productId, $attributeId)
    {
        $attribute = ProductAttribute::where('product_id', $productId)->where('attribute_id', $attributeId)->first();

        if (isset($attribute->product_type)) {
            return $attribute->product_type;
        } else {
            return '';
        }
    }

    public function getAttributeByCode($attributeCode)
    {
        return Attribute::where('code', $attributeCode)->firstOrFail();
    }

    public function deleteAttribute($attributeId)
    {
        return Attribute::find($attributeId)->delete();
    }

    public function attributeStatusUpdate($attributeId)
    {
        $attribute = Attribute::find($attributeId);
        $attribute->status = $attribute->status ? 0 : 1;
        $attribute->save();

        return $attribute;
    }

    /**
     * Attribute Option
     */
    public function getAttributeOptions($attributeId)
    {
        if ($attributeOption = AttributeOption::where('attribute_id', $attributeId)) {
            return $attributeOption->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
        }

        return false;
    }

    public function createAttributeOption($input)
    {
        if ($attributeOption = AttributeOption::create($input)) {
            return $attributeOption;
        }

        return false;
    }

    public function updateAttributeOption($input, $newAttributeOptions)
    {
        $attributeOption = AttributeOption::where('id', $input['id'])
            ->updateOrCreate(
                [
                    'attribute_id' => $input['attribute_id'],
                    'swatch' => $input['swatch'],
                    'label' => $input['label'],
                    'value' => $input['value'],
                    'label_ar' => $input['label_ar'],
                    'value_ar' => $input['value_ar'],
                ]
            );

        if ($attributeOption) {
            return $attributeOption;
        }

        return false;
    }

    public function deleteAttributeOption($attributeId, $newAttributeOptions)
    {
        return AttributeOption::where('attribute_id', $attributeId)->whereNotIn('id', $newAttributeOptions)->delete();
    }

    public function getAttributesForAttributeSet($exceptAttributes = [])
    {
        return Attribute::select(['id', 'name'])
            ->whereNotIn('id', $exceptAttributes)->where('status', 'active')->get();
    }

    public function getAllForAttributeSet($availableAttributes)
    {
        return Attribute::select(['id', 'name'])
            ->where('status', 'active')->whereIn('id', $availableAttributes)->get();
    }

    public function getVarientProductsAttributeOptions($varientProductIds)
    {
        return ProductAttribute::whereIn('product_id', $varientProductIds)
            ->where('product_type', 'virtual')
            ->groupBy('value')
            ->pluck('value');
    }

    public function deleteVarientProductsAttributes($varientProductIds)
    {
        $productAttributes = ProductAttribute::whereIn('product_id', $varientProductIds)->pluck('id');

        if (ProductAttribute::destroy($productAttributes->toArray())) {
            return true;
        }

        return false;
    }

    public function getVarientProductsAttribute($varientProductIds)
    {
        return ProductAttribute::whereIn('product_id', $varientProductIds)
            ->where('product_type', 'virtual')
            ->groupBy('attribute_id')
            ->pluck('attribute_id');
    }

    public function searchAttributes($keyword)
    {
        $attributes = Attribute::select(['id', 'name', 'input_type', 'code'])
            ->where('status', 'active')
            ->where('name', 'like', "%{$keyword}%");

        return $attributes->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function searchCartRuleAttributes($keyword)
    {
        $attributes = Attribute::select(['id', 'name', 'input_type', 'code'])->where('name', 'like', "%{$keyword}%")
            ->where('status', 'active')->orderBy('name')->get();

        $productAttributeOptions = $attributeOptions = [];

        if (request()->get('page') == 1) {
            $attributeOptions[] = [
                'text' => 'Cart Attributes',
                'children' => [
                    ['id' => 'total_item_quantity', 'attribute_id' => 0, 'text' => 'Total Items Quantity', 'attribute_type' => 'price', 'product_configarable_type' => null, 'attribute' => 'cart|total_item_quantity'],
                    // ['id' => 'payment_method', 'attribute_id' => 0, 'text' => 'Payment Method', 'attribute_type' => 'dropdown', 'product_configarable_type' => null, 'attribute' => 'cart|payment_method'],
                    // ['id' => 'shipment_method', 'attribute_id' => 0, 'text' => 'Shipment Method', 'attribute_type' => 'dropdown', 'product_configarable_type' => null, 'attribute' => 'cart|shipment_method'],
                    ['id' => 'shipment_zip_post', 'attribute_id' => 0, 'text' => 'Shipment Zip/Post Code', 'attribute_type' => 'textfield', 'product_configarable_type' => null, 'attribute' => 'cart|shipment_zip_post'],
                    ['id' => 'shipping_state', 'attribute_id' => 0, 'text' => 'Shipping State', 'attribute_type' => 'dropdown', 'product_configarable_type' => null, 'attribute' => 'cart|shipping_state'],
                    ['id' => 'shipping_country', 'attribute_id' => 0, 'text' => 'Shipping Country', 'attribute_type' => 'dropdown', 'product_configarable_type' => null, 'attribute' => 'cart|shipping_country'],
                ],
            ];
            $attributeOptions[] = [
                'text' => 'Cart Item Attributes',
                'children' => [
                    ['id' => 'price_in_cart', 'attribute_id' => 0, 'text' => 'Price In Cart', 'attribute_type' => 'price', 'product_configarable_type' => null, 'attribute' => 'cart_item|price_in_cart'],
                    ['id' => 'qty_in_cart', 'attribute_id' => 0, 'text' => 'Quantity In Cart', 'attribute_type' => 'price', 'product_configarable_type' => null, 'attribute' => 'cart_item|qty_in_cart'],
                    ['id' => 'total_weight', 'attribute_id' => 0, 'text' => 'Total Weight', 'attribute_type' => 'price', 'product_configarable_type' => null, 'attribute' => 'cart_item|total_weight'],
                    ['id' => 'sub_total', 'attribute_id' => 0, 'text' => 'Sub Total', 'attribute_type' => 'price', 'product_configarable_type' => null, 'attribute' => 'cart_item|sub_total'],
                    // ['id' => 'additional', 'attribute_id' => 0, 'text' => 'Additional Information', 'attribute_type' => 'textfield', 'product_configarable_type' => null, 'attribute' => 'cart_item|additional'],
                ],
            ];

            foreach ($attributes as $attribute) {
                $productAttributeOptions[] = ['id' => $attribute->code, 'attribute_id' => $attribute->id, 'text' => $attribute->name, 'attribute_type' => $attribute->input_type, 'product_configarable_type' => 'none', 'attribute' => 'product|' . $attribute->code];
                // $productAttributeOptions[] = ['id' => $attribute->code . '_parent', 'attribute_id' => $attribute->id, 'text' => $attribute->name . '(Parent Only)', 'attribute_type' => $attribute->input_type, 'product_configarable_type' => 'parent_only', 'attribute' => 'product|parent::' . $attribute->code];
                // $productAttributeOptions[] = ['id' => $attribute->code . '_child', 'attribute_id' => $attribute->id, 'text' => $attribute->name . '(Children Only)', 'attribute_type' => $attribute->input_type, 'product_configarable_type' => 'children_only', 'attribute' => 'product|children::' . $attribute->code];
            }
            $extraProductOptions = [

                ['id' => 'catagories', 'attribute_id' => 0, 'text' => 'Catagories', 'attribute_type' => 'catagories', 'product_configarable_type' => null, 'attribute' => 'product|catagories'],
                // ['id' => 'catagories_parent', 'attribute_id' => 0, 'text' => 'Catagories(Parent Only)', 'attribute_type' => 'catagories', 'product_configarable_type' => 'parent_only', 'attribute' => 'product|parent::catagories'],
                // ['id' => 'catagories_child', 'attribute_id' => 0, 'text' => 'Catagories(Children Only)', 'attribute_type' => 'catagories', 'product_configarable_type' => 'children_only', 'attribute' => 'product|children::catagories'],

                ['id' => 'attribute_set', 'attribute_id' => 0, 'text' => 'Attribute Set', 'attribute_type' => 'attribute_set', 'product_configarable_type' => null, 'attribute' => 'product|attribute_set'],
            ];
            $productAttributeOptions = array_merge($extraProductOptions, $productAttributeOptions);

            $attributeOptions[] = [
                'text' => 'Product Attributes',
                'children' => $productAttributeOptions,
            ];
        }

        return $attributeOptions;
    }

    public function searchCatalogRuleAttributes($keyword)
    {
        $attributes = Attribute::select(['id', 'name', 'input_type', 'code'])->where('name', 'like', "%{$keyword}%")
            ->where('status', 'active')->orderBy('name')->get();

        $attributeOptions = [];

        if (request()->get('page') == 1) {
            foreach ($attributes as $attribute) {
                $attributeOptions[] = ['id' => $attribute->code, 'attribute_id' => $attribute->id, 'text' => $attribute->name, 'input_type' => $attribute->input_type];
            }
            $extraOptions = [
                ['id' => 'catagories', 'text' => 'Catagories', 'input_type' => 'catagories'],
                ['id' => 'attribute_set', 'text' => 'Attribute Set', 'input_type' => 'attribute_set'],
            ];
            $attributeOptions = array_merge($extraOptions, $attributeOptions);
        }

        return $attributeOptions;
    }

    public function getAttributeOptionsByValue($attributeId, $value)
    {
        return AttributeOption::where('attribute_id', $attributeId)->where('value', $value)->first();
    }

    public function getAttributeByCodeAndCondition($attributeCode, $operator)
    {
        return Attribute::where('code', $attributeCode)->firstOrFail();
    }

    public function getAttributeCodeById($id)
    {
        return Attribute::where('id', $id)->value('code');
    }

    public function getAttributesForProductConfiguration($attributeSetId)
    {
        $attribute = Attribute::with('attributeSet')
            ->whereHas('attributeSet', function ($q) use ($attributeSetId) {
                $q->where('attribute_set_id', '=', $attributeSetId);
            })
            ->whereIn('input_type', ['dropdown', 'textswatch', 'visualswatch'])
            ->where('code', '!=', 'brand')
            ->get();

        return $attribute;
    }

    public function getAttributesValueByTypeKey($productId, $attributeCode)
    {
        $attribute = ProductAttribute::with('product', 'attribute', 'attribute.attributeOptions')
            ->whereHas('product', function ($q) use ($productId) {
                $q->where('id', '=', $productId);
            })
            ->whereHas('attribute', function ($q) use ($attributeCode) {
                $q->where('code', '=', $attributeCode);
            })
            ->pluck('value');

        return $attribute;
    }

    public function getAttributeOptionsById($attributeId)
    {
        $attributeOption = AttributeOption::where('attribute_id', $attributeId)->get();

        return $attributeOption;
    }
}