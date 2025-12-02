<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderShipments extends Model
{
    use SoftDeletes;

    protected $table = 'order_shipments';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['order_id', 'shipment_method_id', 'status', 'tracking_no', 'shipment_no'];

    public function shipmentMethod()
    {
        return $this->hasOne(ShipmentMethod::class, 'id', 'shipment_method_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function shippingItems()
    {
        return $this->hasMany(OrderShipmentItems::class, 'shipment_id');
    }
}
