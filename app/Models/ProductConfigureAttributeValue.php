<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductConfigureAttributeValue extends Model
{
    protected $table = 'product_configured_attribute_values';

    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function attributeValue()
    {
        return $this->hasOne(AttributeOption::class, 'id', 'attribute_value_id');
    }
}
