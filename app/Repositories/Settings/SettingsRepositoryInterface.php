<?php

namespace App\Repositories\Settings;

interface SettingsRepositoryInterface
{
    public function getAll();

    public function settingsIn($keys);

    public function getByKey($key);

    public function getByKeys($keys);

    public function getByCategories($categories);

    public function update(array $input);

    public function resetValue($setting);

    public function setValueAsArray($setting, $data);

    public function getKeyValue();

    public function searchTimezones($keyword);

    public function getTimezoneByTimezone($timezone);

    public function searchCountry($keyword);

    public function getCountry($countryId);

    public function getCurrency($CurrencyId);

    public function searchState($keyword);

    public function getAllSettings($categories);
}
