<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturn extends Model
{
    use SoftDeletes;

    protected $table = 'order_returns';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['order_id', 'reason', 'location', 'status', 'order_item_id', 'reject_reason', 'pickup_date', 'completed_date', 'confirmed_date', 'rejected_date','status','reject_reason'];

    public function orderReturnImages()
    {
        return $this->hasMany(OrderReturnImage::class, 'order_return_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderReturnItem::class, 'order_return_id', 'id');
    }
}
