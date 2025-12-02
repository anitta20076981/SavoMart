<?php

namespace App\Http\Middleware;

use App\Repositories\Currency\CurrencyRepositoryInterface as CurrencyRepository;
use App\Repositories\Settings\SettingsRepositoryInterface as SettingsRepository;
use Closure;

class Settings
{
    protected $settingsRepo;

    protected $currencyRepo;

    public function __construct(SettingsRepository $settingsRepo, CurrencyRepository $currencyRepo)
    {
        $this->settingsRepo = $settingsRepo;
        $this->currencyRepo = $currencyRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Load general settings to cache and remains for 5 min
         */
        $settings = cache()->remember('settings', 86400, function () {
            try {
                $settingsArray = $this->settingsRepo->getAllSettings(['store', 'config', 'inventory', 'catalog', 'security', 'sales', 'sales_tax', 'sales_checkout', 'general', 'default']);
            } catch (\Exception $e) {
                $settingsArray = [];
            }

            return $settingsArray;
        });
        config()->set('settings', $settings);

        if (isset($settings['timezone'])) {
            config()->set('app.timezone', $settings['timezone']);
        }

        if (isset($settings['currency_id'])) {
            $currencyId = $settings['currency_id'] ? $settings['currency_id'] : 4;
            $currency = cache()->remember('currency', 86400, function () use ($currencyId) {
                return $this->currencyRepo->getCurrency($currencyId);
            });
            config()->set('app.currency', $currency->toArray());
        }

        // if (isset($settings['date_only_js']) && isset($settings['date_only_display']) && isset($settings['date_only_store'])) {
        //     $dateFormat = [
        //         'date_only_js' => $settings['date_only_js'],
        //         'date_only_display' => $settings['date_only_display'],
        //         'date_only_store' => $settings['date_only_store'],
        //     ];
        //     config()->set('date_format', $dateFormat);
        // }
        if (isset($settings['config']['date_only_js']) && isset($settings['config']['date_only_display']) && isset($settings['config']['date_only_store'])) {

            $dateFormat = [
                'date_only_js' => $settings['config']['date_only_js'],
                'date_only_display' => $settings['config']['date_only_display'],
                'date_only_store' => $settings['config']['date_only_store'],

                'time_only_js' => 'H:i',
                'time_only_display' => 'H:i',
                'time_only_store' => 'H:i',

                'date_time_js' => $settings['config']['date_only_js'] . ' H:i',
                'date_time_display' => $settings['config']['date_only_display'] . ' H:i',
                'date_time_store' => $settings['config']['date_only_store'] . ' H:i',

                'date_time_first_store' => $settings['config']['date_only_store'] . ' 00:00:00',
                'date_time_last_store' => $settings['config']['date_only_store'] . ' 23:59:59',

                'date_time_first_display' => $settings['config']['date_only_display'] . ' 00:00:00',
                'date_time_last_display' => $settings['config']['date_only_display'] . ' 23:59:59',

                'day_first' => 'Y-m-d 00:00:00',
                'day_last' => 'Y-m-d  23:59:59',

            ];
            config()->set('date_format', $dateFormat);
        }

        return $next($request);
    }
}
