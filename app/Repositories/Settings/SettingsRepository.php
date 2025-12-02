<?php

namespace App\Repositories\Settings;

use App\Models\Country;
use App\Models\Setting;
use App\Models\State;
use App\Models\Timezone;
use App\Models\Currency;

class SettingsRepository implements SettingsRepositoryInterface
{
    public function getAll()
    {
        $settings = Setting::get();

        return $settings;
    }

    public function settingsIn($keys)
    {
        return Setting::whereIn('key', $keys)->get();
    }

    public function getByKey($key)
    {
        return Setting::where('key', $key)->first();
    }

    public function getByKeys($keys)
    {
        return Setting::whereIn('key', $keys)->get();
    }

    public function getByCategories($categories)
    {
        return Setting::whereIn('category', $categories)->get();
    }

    public function update(array $input)
    {
        foreach ($input as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if (!empty($setting)) {
                $setting->value = $value;
                $setting->save();
                cache()->forget('settings'); //To reflect the settings immediately
            }
        }

        return $this->settingsIn(array_keys($input));
    }

    public function resetValue($setting)
    {
        if (is_string($setting)) {
            $setting = Setting::where('key', $setting)->first();
        }

        if (!empty($setting)) {
            $setting->value = '';
            $setting->save();
            cache()->forget('settings'); //To reflect the settings immediately
        }
    }

    public function setValueAsArray($setting, $data)
    {
        if (is_string($setting)) {
            $setting = Setting::where('key', $setting)->first();
        }

        if (!empty($setting)) {
            $setting->valueArray = $data;
            $setting->save();
            cache()->forget('settings'); //To reflect the settings immediately
        }
    }

    public function getKeyValue()
    {
        return Setting::select('key', 'value')->get()->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        });
    }

    public function searchTimezones($keyword)
    {
        $timezones = Timezone::where('name', 'like', "%{$keyword}%");

        return $timezones->paginate(30, ['*'], 'page', request()->get('page'));
    }

    public function getTimezoneByTimezone($timezone)
    {
        return Timezone::where('timezone', $timezone)->first();
    }

    public function searchCountry($keyword)
    {
        $countries = Country::where('short_name', 'like', "%{$keyword}%");

        return $countries->paginate(30, ['*'], 'page', request()->get('page'));
    }

    public function getCountry($countryId)
    {
        return Country::find($countryId);
    }

    public function getCurrency($CurrencyId)
    {
        return Currency::find($CurrencyId);
    }


    public function searchState($keyword)
    {
        $states = State::where('name', 'like', "%{$keyword}%");

        return $states->paginate(30, ['*'], 'page', request()->get('page'));
    }

    public function getAllSettings($categories)
    {
        $allSettings = Setting::whereIn('category', $categories)->get();
        $settingsArray = [];

        foreach ($allSettings as $setting) {
            $settingsArray[$setting->category][$setting->key] = $setting->value;
        }

        return $settingsArray;
    }
}
