<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturnItem extends Model
{
    use SoftDeletes;

    protected $table = 'order_return_items';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['order_return_id', 'order_item_id', 'product_id', 'price', 'quantity', 'total', 'return_status'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function products()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function orderReturn()
    {
        return $this->belongsTo(OrderReturn::class, 'order_return_id', 'id');
    }
}
