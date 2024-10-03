<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for tap settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Tap\IndexResponse;
use App\Http\Responses\Settings\Tap\UpdateResponse;
use Illuminate\Http\Request;
use Validator;

class Tap extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //crumbs, page data & stats
        $page = $this->pageSettings();

        //settings
        $settings = \App\Models\Settings2::find(1);

        //reponse payload
        $payload = [
            'page' => $page,
            'settings' => $settings,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update() {

        //settings
        $settings = \App\Models\Settings2::find(1);

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'settings2_tap_secret_key' => 'required',
            'settings2_tap_publishable_key' => 'required',
            'settings2_tap_currency_code' => 'required',
            'settings2_tap_display_name' => 'required',
        ], $messages);

        //errors
        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        //update settings
        $settings->settings2_tap_secret_key = request('settings2_tap_secret_key');
        $settings->settings2_tap_publishable_key = request('settings2_tap_publishable_key');
        $settings->settings2_tap_currency_code = request('settings2_tap_currency_code');
        $settings->settings2_tap_language = request('settings2_tap_language');
        $settings->settings2_tap_display_name = request('settings2_tap_display_name');
        $settings->settings2_tap_status = (request('settings2_tap_status')) == 'on' ? 'enabled' : 'disabled';
        $settings->save();

        //reponse payload
        $payload = [];

        //generate a response
        return new UpdateResponse($payload);
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
                __('lang.payment_methods'),
                'tap',
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
        ];
        return $page;
    }

}
