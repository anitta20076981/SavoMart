<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class OrderInvoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_invoice';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $appends = ['invoice_url'];

    protected $fillable = ['invoice_no', 'order_id', 'total_tax_amount', 'grand_total'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(OrderInvoiceItem::class, 'invoice_id');
    }

    public function getInvoiceUrlAttribute()
    {
        return $this->invoice_no ? Storage::disk('ashtaal')->url('order/invoice/' . $this->invoice_no . '_invoice.pdf') : '';
    }
}
