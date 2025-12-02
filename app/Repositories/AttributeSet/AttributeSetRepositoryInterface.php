<?php

namespace App\Repositories\AttributeSet;

interface AttributeSetRepositoryInterface
{
    public function getForDatatable($data);

    public function createAttributeSet($input);

    public function updateAttributeSet(array $input);

    public function getAttributeSet($attributeSetId);

    public function getAttributeSetForProducts($attributeId);

    public function getAllAttributeSets();

    public function searchAttributeSets($keyword);

    public function deleteAttributeSet($attributeSetId);

    public function attributeStatusUpdate($attributeSetId);

    public function createAttributeSetAttribute($input);

    public function updateAttributeSetAttribute($input);

    public function deleteAttributeSetAttribute($attributeSetId, $attributes);

    public function searchAttributeSet($keyword);

    public function getAttributeSetByOperator($setId, $operator);
}
