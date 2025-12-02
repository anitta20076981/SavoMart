<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInvoiceItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_invoice_items';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['invoice_id', 'order_item_id', 'product_id', 'quantity', 'tax_amount', 'total_amount', 'status', 'unit_price'];

    public function invoice()
    {
        return $this->belongsTo(OrderInvoice::class, 'invoice_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
