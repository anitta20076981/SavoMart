<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeletes;

    protected $table = 'cart_items';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['cart_id', 'product_id', 'quantity', 'unit_price', 'total_price' ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function productInventry()
    {
        return $this->belongsTo(ProductInventory::class, 'product_id', 'product_id');
    }
}
