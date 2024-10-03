<?php

/** ---------------------------------------------------------------------------------------------------------------
 * The purpose of this middleware it to set fallback config values
 * for older versions of Grow CRM that are upgrading. Reason being that new
 * values in the file /config/settings.php will not exist for them (as settings files in not included in updates)
 *
 *
 *
 * @package    Grow CRM
 * @author     NextLoop
 * @revised    9 July 2021
 *--------------------------------------------------------------------------------------------------------------*/

namespace App\Http\Middleware\General;
use Closure;

class BootSystem {

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //do not run this for SETUP path
        if (env('SETUP_STATUS') != 'COMPLETED') {
            return $next($request);
        }

        //save system settings into config array
        $settings = \App\Models\Settings::leftJoin('settings2', 'settings2.settings2_id', '=', 'settings.settings_id')
        ->Where('settings_id', 1)
        ->first();

        //set timezone
        date_default_timezone_set($settings->settings_system_timezone);

        //currency symbol position setting
        if ($settings->settings_system_currency_position == 'left') {
            $settings['currency_symbol_left'] = $settings->settings_system_currency_symbol;
            $settings['currency_symbol_right'] = '';
        } else {
            $settings['currency_symbol_right'] = $settings->settings_system_currency_symbol;
            $settings['currency_symbol_left'] = '';
        }

        //lead statuses
        $settings['lead_statuses'] = [];
        foreach (\App\Models\LeadStatus::get() as $status) {
            $key = $status->leadstatus_id;
            $value = $status->leadstatus_color;
            $settings['lead_statuses'] += [
                $key => $value,
            ];
        }

        //Just a list of all payment geteways - used in dropdowns and filters
        $settings['gateways'] = [
            'Paypal',
            'Stripe',
            'Bank',
            'Cash',
        ];

        //cronjob path
        $settings['cronjob_path'] = '/usr/local/bin/php ' . BASE_DIR . '/application/artisan schedule:run >> /dev/null 2>&1';

        //all team members
        $settings['team_members'] = \App\Models\User::Where('type', 'team')->Where('status', 'active')->get();

        //javascript file versioning to avoid caching when making updates
        $settings['versioning'] = $settings->settings_system_javascript_versioning;

        //save once to config
        config(['system' => $settings]);

        /**
         * how many rows to show in settings. Defaults to a hard set value, if not present
         * fallback value: 5
         */
        config(['settings.custom_fields_display_limit' => config('settings.custom_fields_display_limit') ?? 5]);

        return $next($request);

    }

}