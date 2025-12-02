<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $dates = ['deleted_at'];

    protected $table = 'product_attributes';

    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'product_type',
        'attribute_id',
        'value',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
