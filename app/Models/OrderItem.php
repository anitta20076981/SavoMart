<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'order_items';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['cart_item_id', 'order_id', 'product_id', 'quantity', 'unit_price', 'total_price', 'status' ,'return_status'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function productInventry()
    {
        return $this->belongsTo(ProductInventory::class, 'product_id', 'product_id');
    }

    public function orderReturnItem()
    {
        return $this->hasOne(OrderReturn::class, 'order_item_id', 'id');
    }
}
