<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Model
{
    use SoftDeletes;

    protected $table = 'wishlists';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['product_id', 'customer_id'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function customer()
    {
        return $this->hasOne('Customer', 'id', 'customer_id');
    }
}
