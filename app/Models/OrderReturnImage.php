<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class OrderReturnImage extends Model
{
    use SoftDeletes;

    protected $table = 'order_return_images';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['order_return_id', 'file'];

    protected $appends = ['return_image_url'];

    public function getReturnImageUrlAttribute()
    {
        return $this->file ? Storage::disk('savomart')->url($this->file) : '';
    }
}
