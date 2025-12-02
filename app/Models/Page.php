<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    protected $table = 'pages';

    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'title', 'content', 'file', 'thumbnail', 'slug', 'status', 'is_deletable', 'category_id'];

    protected $appends = ['file_url', 'thumbnail_url'];

    public function getSlugOptions(): SlugOptions
    {
        $slugOptions = SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');

        if ($this->is_deletable == 0) {
            $slugOptions->doNotGenerateSlugsOnUpdate();
        }

        return $slugOptions;
    }

    public function getFileUrlAttribute()
    {
        return $this->file ? Storage::disk('grocery')->url($this->file) : '';
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? Storage::disk('grocery')->url($this->thumbnail) : '';
    }

    public function images()
    {
        return $this->hasMany('App\Models\PageImage', 'page_id', 'id');
    }
}
