<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class BannerItem extends Model
{
    use SoftDeletes;

    protected $table = 'banner_item';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['banner_id', 'title', 'link', 'file'];

    protected $appends = ['banner_image_url'];


    public function banner()
    {
        return $this->belongsTo('App\Models\Banner', 'banner_id', 'id');
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->file && $this->file ? Storage::disk('grocery')->url($this->file) : '';
    }
}
