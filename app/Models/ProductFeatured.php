<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFeatured extends Model
{
    use SoftDeletes;

    protected $table = 'product_featured';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['product_id', 'from', 'to', 'status'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
