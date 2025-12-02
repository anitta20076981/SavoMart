<?php

namespace App\Repositories\Attribute;

interface AttributeRepositoryInterface
{
    public function getForDatatable($data);

    public function createAttribute($input);

    public function updateAttribute(array $input);

    public function getAttribute($attributeId);

    public function getAttributes($attributeId);

    public function getAttributeWithOptionsForConfiguration($attributeId, $optionIds);

    public function getAttributeByCode($attributeCode);

    public function deleteAttribute($attributeId);

    public function attributeStatusUpdate($attributeId);

    public function createAttributeOption(array $input);

    public function getAttributeOptions($attributeId);

    public function updateAttributeOption(array $input, array $updateAttributeOption);

    public function deleteAttributeOption(array $input, array $updateAttributeOption);

    public function getAttributesForAttributeSet(array $exceptAttributes = []);

    public function getAttributesForMap($attributeIds);

    public function getAllForAttributeSet(array $availableAttributes);

    public function searchAttributes($keyword);

    public function searchCartRuleAttributes($keyword);

    public function searchCatalogRuleAttributes($keyword);

    public function pluckInputTypeById($key);

    public function createProductAttributes(array $attributeInputData);

    public function deleteProductConfiguredAttributes($productId);

    public function updateProductConfiguredAttributes(array $attributeInputData);

    public function updateProductAttributes(array $attributeInputData);

    public function getProductAttributeValue($productId, $attributeId);

    public function getParentProductAttributes($parentId, $attributeId);

    public function getProductAttributeProductType($productId, $attributeId);

    public function getOptionsWithAttributesForConfiguration($optionId);

    public function getAttributeUsingOptionIds($optionIds);

    public function getVarientProductsAttributeOptions($varientProductIds);

    public function getVarientProductsAttribute($varientProductIds);

    public function getAttributeOptionsByValue($attributeId, $value);

    public function getAttributeCodeById($id);

    public function getAttributesForProductConfiguration($attributeSetId);

    public function getAttributesValueByTypeKey($productId, $attributeCode);

    public function getAttributeOptionsById($attributeId);
}
