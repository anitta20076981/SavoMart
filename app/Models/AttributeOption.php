<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeOption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'attribute_id', 'swatch', 'label', 'value','label_ar', 'value_ar',
    ];

    protected $table = 'attribute_options';

    public $timestamps = true;

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}