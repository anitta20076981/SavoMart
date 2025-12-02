<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'applications';

    protected $fillable = [
        'name',
        'description',
        'logo',
        'status',
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->hasMany('Product', 'application_id');
    }
}
