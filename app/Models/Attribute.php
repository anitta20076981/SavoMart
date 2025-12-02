<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'attributes';

    public $timestamps = true;

    protected $fillable = ['name', 'name_ar', 'input_type', 'code', 'status', 'is_required'];

    public function productAttributes()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id', 'id');
    }

    public function attributeSets()
    {
        return $this->hasMany(AttributeSet::class, 'attribute_id', 'id');
    }

    public function attributeOptions()
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id', 'id');
    }

    public function attributeSet()
    {
        return $this->hasMany(AttributeSetAttributes::class, 'attribute_id', 'id');
    }
}