<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRelation extends Model
{
    use SoftDeletes;

    protected $table = 'product_relations';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['product_id', 'related_product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'related_product_id', 'id')->withDefault();
    }
}
