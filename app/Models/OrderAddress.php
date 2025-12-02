<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAddress extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_addresses';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['order_id', 'first_name', 'last_name', 'details', 'street_address', 'country', 'state', 'city', 'postel_code', 'contact'];

    public function order()
    {
        return $this->belongsTo('Order', 'id', 'order_id');
    }


}
