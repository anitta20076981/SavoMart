<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerSection extends Model
{
    use SoftDeletes;

    protected $table = 'banner_sections';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'price', 'status'];
}
