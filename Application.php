<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'description',
        'logo',
        'status',
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->hasMany('Product', 'brand_id');
    }
}
