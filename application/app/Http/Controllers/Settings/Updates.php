<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for update settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Updates\CheckResponse;
use App\Http\Responses\Settings\Updates\IndexResponse;
use App\Repositories\SettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class Updates extends Controller {

    /**
     * The settings repository instance.
     */
    protected $settingsrepo;

    public function __construct(SettingsRepository $settingsrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

        $this->settingsrepo = $settingsrepo;

    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //[MT]
        if (config('system.settings_type') != 'standalone') {
            abort(404);
        }

        //crumbs, page data & stats
        $page = $this->pageSettings();

        $settings = \App\Models\Settings::find(1);

        //reponse payload
        $payload = [
            'page' => $page,
            'settings' => $settings,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUpdates() {

        //crumbs, page data & stats
        $page = $this->pageSettings();

        //fix updates URL (Oct 2023)
        $updates_server_url = str_replace('http://', 'https://', config('app.updates_server'));
        
        Log::error("starting to check for updates with server ($updates_server_url)", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'server_url' => $updates_server_url]);

        try {
            $response = Http::asForm()->post($updates_server_url, [
                'licence_key' => request('licence_key'),
                'ip_address' => request('ip_address'),
                'url' => request('url'),
                'current_version' => request('current_version'),
                'domain_name' => request()->getHost(), //added in v2.0
                'email' => request('email'),
                'name' => request('name'),
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $message = substr($e->getMessage(), 0, 150);
            //log
            Log::error("unable to connect to updates server", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'server_url' => $updates_server_url, 'error' => $message]);
            return new CheckResponse([
                'type' => 'server-error',
            ]);
        }

        //check if we got an error
        if ($response->failed() || $response->clientError() || $response->serverError() || !$response->successful()) {
            //log
            Log::error("unable to connect to updates server", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'server_url' => $updates_server_url, 'error' => $response]);
            return new CheckResponse([
                'type' => 'server-error',
            ]);
        }

        if (!request()->filled('licence_key')) {
            //log
            Log::error("purchase license key not provided", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return new CheckResponse([
                'type' => 'license-error',
            ]);
        }

        //get the result
        $result = $response->json();
        Log::info("received a response from the updates server - payload", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $result]);
        
        //we have received an error from the update server
        if ($result['status'] == 'failed') {
            return new CheckResponse([
                'type' => 'generic-error',
                'dom' => $result['dom'],
            ]);
        }

        //we have received an error from the update server
        if ($result['status'] == 'failed-message') {
            //all ok
            return new CheckResponse([
                'type' => 'failed-message',
                'message' => $result['message'],
                'message_heading' => $result['message_heading'] ?? 'Error',
            ]);
        }

        //we have received an error from the update server
        if ($result['status'] == 'success') {

            /* ---------------------------------------------------------
             * PHP version checks (if specified by updates server)
             * @added version 2.0 (Jan 2022)
             * --------------------------------------------------------*/
            //minimum version
            if (isset($result['php_version_minimum']) && $result['php_version_minimum'] != '') {
                if (!version_compare(PHP_VERSION, $result['php_version_minimum'], ">=")) {
                    return new CheckResponse([
                        'type' => 'php-error-message',
                        'message_type' => 'alert-warning',
                        'message_heading' => 'PHP Version Error',
                        'message' => '<h5>A new update is available <strong>('.$result['update_version'].')</strong></h5>.<br/>However, it requires a minimum PHP version <strong>(PHP ' . $result['php_version_minimum'] . ')</strong>. Your server is running <strong>(PHP ' . phpversion() . ')</strong><br /><br />You must first update your PHP version. <br /><br />You can update your PHP version from inside your web hosting control panel',

                        //optional link
                        'message_url_link' => $result['message_url_link'] ?? null,
                        'message_url_anchor' => $result['message_url_anchor'] ?? null,
                    ]);
                }
            }
            //maximum version
            if (isset($result['php_version_maximum']) && $result['php_version_maximum'] != '') {
                if (!version_compare(PHP_VERSION, $result['php_version_maximum'], "<")) {
                    return new CheckResponse([
                        'type' => 'php-error-message',
                        'message_type' => 'alert-warning',
                        'message_heading' => 'PHP Version Error',
                        'message' => '<h5>A new update is available <strong>('.$result['update_version'].')</strong></h5>.<br/>However, your server is running PHP version <strong>(PHP ' . phpversion() . ')</strong>. The maximum version currently supported is <strong>(PHP ' . $result['php_version_maximum'] . ')</strong><br /><br />You must first change your PHP version. <br /><br />You can change your PHP version from inside your web hosting control panel',

                        //optional link
                        'message_url_link' => $result['message_url_link'] ?? null,
                        'message_url_anchor' => $result['message_url_anchor'] ?? null,
                    ]);
                }
            }

            /* ---------------------------------------------------------
             * Everything is ok, show download link and optiona
             * messages
             * --------------------------------------------------------*/
            return new CheckResponse([
                'type' => 'success',
                'update_version' => $result['update_version'],
                'url' => $result['url'],

                //optional messages (from updates server)
                //@added version 2.0 (Jan 2022)
                'message_heading' => $result['message_heading'] ?? null,
                'message_type' => (isset($result['message_type']) && in_array($result['message_type'], ['info', 'danger'])) ? 'alert-' . $result['message_type'] : 'alert-warning',
                'message' => $result['message'] ?? null,

                //optional link
                //@added version 2.0 (Jan 2022)
                'message_url_link' => $result['message_url_link'] ?? null,
                'message_url_anchor' => $result['message_url_anchor'] ?? null,
            ]);
        }
    }
    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'crumbs' => [
                __('lang.settings'),
                __('lang.updates'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
        ];
        return $page;
    }

}
