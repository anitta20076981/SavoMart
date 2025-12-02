<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'status', 'iso2', 'iso3', 'short_name', 'long_name', 'flag', 'country_code',
    ];

    public function getImageAttribute()
    {
        return $this->iso2 . '.png';
    }
}
