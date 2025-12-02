<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = 'cart';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['customer_id', 'date', 'total_items', 'grand_total', 'status','cart_status'];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($cart) {
            $cart->cartItems()->delete();
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function existingCartItem()
    {
        return $this->hasOne(CartItem::class, 'cart_id');
    }

    public function shipmentAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'id', 'shipping_address_id')->where('status', 'active');
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function shipmentMethod()
    {
        return $this->hasOne(ShipmentMethod::class, 'id', 'shipment_method_id');
    }

    public function billingAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'id', 'billing_address_id')->where('status', 'active');
    }
}