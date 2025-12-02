<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeSet extends Model
{
    use SoftDeletes;

    protected $table = 'attribute_sets';

    protected $fillable = ['name', 'status'];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($attributeSet) {
            if ($attributeSet->attributeSetAttributes) {
                $attributeSet->attributeSetAttributes()->delete();
            }
        });
    }

    public function attributeSetAttributes()
    {
        return $this->hasMany(AttributeSetAttributes::class, 'attribute_set_id', 'id');
    }

    public function attributes()
    {
        return $this->hasManyThrough(Attribute::class, AttributeSetAttributes::class, 'attribute_set_id', 'id', 'id', 'attribute_id');
    }

    public function configurableAttributes()
    {
        return $this->hasManyThrough(Attribute::class, AttributeSetAttributes::class, 'attribute_set_id', 'id', 'id', 'attribute_id')
            ->whereIn('input_type', ['dropdown', 'textswatch', 'visualswatch'])
            ->where('code', '!=', 'brand');
    }

    public function attributesCategories()
    {
        return $this->belongsToMany('AttributeSet', 'categories_attributes', 'attributes_set_id', 'categories_id');
    }
}
