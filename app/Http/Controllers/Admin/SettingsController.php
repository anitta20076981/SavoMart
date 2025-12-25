<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\SettingsUpdateRequest;
use App\Http\Requests\Admin\Settings\SettingsViewRequest;
use App\Repositories\Settings\SettingsRepositoryInterface as SettingsRepository;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function branding(SettingsViewRequest $request, SettingsRepository $settingsRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'System / Store / Branding'],
        ];
        $settings = $settingsRepo->getAll()->keyBy('key');

        return view('admin.settings.branding', compact('settings', 'breadcrumbs'));
    }

    public function configuration(SettingsViewRequest $request, SettingsRepository $settingsRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'System / Store / Configuration'],
        ];
        $settings = $settingsRepo->getAll()->keyBy('key');
        $old = [];

        if ($settings->get('timezone')->value) {
            $old['timezone'] = $settingsRepo->getTimezoneByTimezone($settings->get('timezone')->value);
        }

        if ($settings->get('country_id')->value) {
            $old['country_id'] = $settingsRepo->getCountry($settings->get('country_id')->value);
        }

        if ($settings->get('currency_id')->value) {
            $old['currency_id'] = $settingsRepo->getCurrency($settings->get('currency_id')->value);
        }

        return view('admin.settings.configuration', compact('settings', 'breadcrumbs', 'old'));
    }

    public function socialSettings(SettingsViewRequest $request, SettingsRepository $settingsRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'System / Store / Social Settings'],
        ];
        $settings = $settingsRepo->getAll()->keyBy('key');

        return view('admin.settings.socialSettings', compact('settings', 'breadcrumbs'));
    }

    public function saveSettings(SettingsRepository $settingsRepo, SettingsUpdateRequest $request)
    {
        $saveData = $request->all();

        if ($request->hasFile('fav_icon')) {
            $favIcon = $request->file('fav_icon');
            $favIconName = 'favicon.' . $favIcon->getClientOriginalExtension();
            $favIconPath = 'app';
            $file = Storage::disk('savomart')->putFileAs($favIconPath, $favIcon, $favIconName);
            $saveData['fav_icon'] = $file;
        } elseif ($request->has('fav_icon_remove') && $request->fav_icon_remove) {
            $saveData['fav_icon'] = '';
        }

        if ($request->hasFile('logo_light')) {
            $logo = $request->file('logo_light');
            $logoName = 'logo_light.' . $logo->getClientOriginalExtension();
            $logoPath = 'app';
            $file = Storage::disk('savomart')->putFileAs($logoPath, $logo, $logoName);
            $saveData['logo_light'] = $file;
        } elseif ($request->has('logo_light_remove') && $request->logo_light_remove) {
            $saveData['logo_light'] = '';
        }

        if ($request->hasFile('logo_dark')) {
            $logo = $request->file('logo_dark');
            $logoName = 'logo_dark.' . $logo->getClientOriginalExtension();
            $logoPath = 'app';
            $file = Storage::disk('savomart')->putFileAs($logoPath, $logo, $logoName);
            $saveData['logo_dark'] = $file;
        } elseif ($request->has('logo_dark_remove') && $request->logo_dark_remove) {
            $saveData['logo_dark'] = '';
        }

        if ($request->has('dateformat')) {
            $saveData['date_only_js'] = implode('', $request->dateformat);
            $saveData['date_only_display'] = implode('', $request->dateformat);
        }

        $settingsRepo->update($saveData);
        cache()->forget('settings');

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}