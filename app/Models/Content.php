<?php

namespace App\Models;

// use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    protected $table = 'contents';

    // use Sluggable;
    use SoftDeletes;
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = ['content_category_id', 'name', 'title', 'content', 'file', 'thumbnail', 'slug', 'status', 'is_deletable'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
    
    public function category()
    {
        return $this->hasMany('App\Models\ContentCategory', 'id', 'content_category_id');
    }
}