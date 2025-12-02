<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
// use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Banner extends Model
{
    use SoftDeletes;

    protected $table = 'banners';

    use SoftDeletes;
    use HasSlug;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            foreach ($model->items() as $item) {
                if (Storage::disk('grocery')->delete($item->file)) {
                    $item->delete();
                }
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'slug', 'status', 'multiple', 'banner_section_id', 'is_deletable'];

    protected $visible = ['is_deletable'];

    public $timestamps = true;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function items()
    {
        return $this->hasMany('App\Models\BannerItem', 'banner_id', 'id');
    }

    public function advertisement()
    {
        return $this->hasOne('Advertisement', 'banner_id', 'id');
    }
}