<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'categories';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'name_ar', 'logo', 'icon', 'parent_category_id', 'status'];

    protected $appends = ['logo_url', 'icon_url'];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($catagory) {
            $catagory->productCatagory()->delete();
            $catagory->children()->delete();
        });
    }

    // public function logoUrl(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (mixed $value) => ($this->logo ? Storage::disk('savomart')->url($this->logo) : null),
    //     );
    // }

    public function getLogoUrlAttribute()
    {
        return isset($this->logo) ? Storage::disk('savomart')->url($this->logo) : null;
    }

    public function getIconUrlAttribute()
    {
        return isset($this->icon) ? Storage::disk('savomart')->url($this->icon) : null;
    }

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_category_id', 'id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with(['childrenRecursive']);
    }

    public function attributeSets()
    {
        return $this->belongsToMany('AttributeSet', 'categories_attributes', 'categories_id', 'attributes_set_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'id');
    }

    public function productCatagory()
    {
        return $this->hasMany('App\Models\ProductCategories', 'category_id', 'id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductCategories::class, 'category_id', 'id', 'id', 'product_id');
    }
}
