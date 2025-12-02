<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriesAttributeSet extends Model
{
    use SoftDeletes;

    protected $table = 'categories_attribute_sets';

    public $timestamps = true;
}
