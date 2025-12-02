<?php

namespace App\Repositories\Country;

interface CountryRepositoryInterface
{
    public function getCountry($id);

    public function getAllCountries();

    public function getDefaultCountry();
}
