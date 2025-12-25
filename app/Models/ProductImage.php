<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use SoftDeletes;

    protected $table = 'product_images';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['product_id', 'image_path', 'image_role', 'alt_text'];

    protected $appends = ['image_url'];

    public function product()
    {
        return $this->belongsTo('Product');
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::disk('savomart')->url($this->image_path) : '';
    }
}
