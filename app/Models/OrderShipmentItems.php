<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderShipmentItems extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_shipment_items';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['shipment_id', 'order_item_id', 'product_id', 'quantity', 'price', 'total', 'status'];

    public function shipment()
    {
        return $this->belongsTo(OrderShipments::class, 'shipment_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
