<?php

namespace App\Repositories\Country;

use App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{
    public function getCountry($id)
    {
        return Country::find($id);
    }

    public function getAllCountries()
    {
        return Country::get();
    }

    public function getDefaultCountry()
    {
        return Country::find(105);
    }
}
