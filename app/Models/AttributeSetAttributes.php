<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeSetAttributes extends Model
{
    use HasFactory;

    protected $table = 'attribute_set_attributes';

    protected $fillable = ['attribute_id', 'attribute_set_id'];

    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }
}
