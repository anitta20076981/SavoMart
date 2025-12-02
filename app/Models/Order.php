<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'order';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['customer_id', 'order_no', 'cart_id', 'address_id', 'payment_type', 'date', 'total_items', 'grand_total', 'status', 'invoice_status', 'shipment_status','delivery_date'];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($order) {
            $order->orderItems()->delete();
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function existingOrderItem()
    {
        return $this->hasOne(OrderItem::class, 'order_id');
    }

    public function location()
    {
        return $this->hasOne(CustomerDetails::class, 'id', 'address_id');
    }

    public function shipmentAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'id', 'shipping_address_id')->where('status', 'active');
    }



    public function billingAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'id', 'billing_address_id')->where('status', 'active');
    }

    public function invoice()
    {
        return $this->hasOne(OrderInvoice::class, 'order_id', 'id');
    }

    public function shipment()
    {
        return $this->hasMany(OrderShipments::class, 'order_id', 'id');
    }

    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id');
    }

}
