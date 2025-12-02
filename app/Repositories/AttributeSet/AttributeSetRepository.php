<?php

namespace App\Repositories\AttributeSet;

use App\Models\AttributeSet;
use App\Models\AttributeSetAttributes;

class AttributeSetRepository implements AttributeSetRepositoryInterface
{
    public function getForDatatable($data)
    {
        return AttributeSet::select('id', 'name', 'status')->get();
    }

    public function createAttributeSet($input)
    {
        if ($attribute = AttributeSet::create($input)) {
            return $attribute;
        }

        return false;
    }

    public function updateAttributeSet(array $input)
    {
        $attribute = AttributeSet::find($input['id']);
        unset($input['id']);

        if ($attribute->update($input)) {
            return $attribute;
        }

        return false;
    }

    public function getAttributeSet($attributeSetId)
    {
        return AttributeSet::with('attributes')->find($attributeSetId);
    }

    public function getAttributeSetForProducts($attributeId)
    {
        return AttributeSet::with('attributes')->find($attributeId);
    }

    public function getAttributeSetForConfigurations($attributeId)
    {
    }

    public function getAllAttributeSets()
    {
        return AttributeSet::where('status', 'active')->select('id', 'name')->get()->toArray();
    }

    public function searchAttributeSets($keyword)
    {
        $attributeSet = AttributeSet::where('status', 'active')->Where('name', 'like', "%{$keyword}%");

        return $attributeSet->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function deleteAttributeSet($attributeSetId)
    {
        return AttributeSet::find($attributeSetId)->delete();
    }

    public function attributeStatusUpdate($attributeSetId)
    {
        $attribute = AttributeSet::find($attributeSetId);
        $attribute->status = $attribute->status ? 0 : 1;
        $attribute->save();

        return $attribute;
    }

    /**
     * Summary of AttributeSetAttribute
     *
     * @param  mixed  $input
     * @return mixed
     */
    public function createAttributeSetAttribute($input)
    {
        if ($AttributeSetAttributes = AttributeSetAttributes::create($input)) {
            return $AttributeSetAttributes;
        }

        return false;
    }

    public function updateAttributeSetAttribute($input)
    {
        $attributeSetAttributes = AttributeSetAttributes::where('attribute_set_id', $input['attribute_set_id'])
            ->updateOrCreate(
                [
                    'attribute_set_id' => $input['attribute_set_id'],
                    'attribute_id' => $input['attribute_id'],
                ]
            );

        if ($attributeSetAttributes) {
            return $attributeSetAttributes;
        }

        return false;
    }

    public function deleteAttributeSetAttribute($attributeSetId, $attributes)
    {
        return AttributeSetAttributes::where('attribute_set_id', $attributeSetId)
            ->whereNotIn('id', $attributes)->delete();
    }

    public function searchAttributeSet($keyword)
    {
        $attributes = AttributeSet::select(['id', 'name'])
            ->where('status', 'active')
            ->where('name', 'like', "%{$keyword}%");

        return $attributes->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function getAttributeSetByOperator($setId, $operator)
    {
        return AttributeSet::where('id', $operator, $setId)->pluck('id');
    }
}