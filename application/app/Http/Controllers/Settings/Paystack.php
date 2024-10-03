<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for paystack settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Paystack\IndexResponse;
use App\Http\Responses\Settings\Paystack\UpdateResponse;
use Illuminate\Http\Request;
use Validator;

class Paystack extends Controller {

    /**
     * The settings repository instance.
     */
    protected $settingsrepo;

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

        $settings = \App\Models\Settings2::Where('settings2_id', 1)->first();

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

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'settings2_paystack_secret_key' => 'required',
            'settings2_paystack_public_key' => 'required',
            'settings2_paystack_display_name' => 'required',
            'settings2_paystack_currency_code' => 'required',
        ], $messages);

        //errors
        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        //reset existing account owner
        \App\Models\Settings2::where('settings2_id', 1)
            ->update([
                'settings2_paystack_secret_key' => request('settings2_paystack_secret_key'),
                'settings2_paystack_public_key' => request('settings2_paystack_public_key'),
                'settings2_paystack_display_name' => request('settings2_paystack_display_name'),
                'settings2_paystack_currency_code' => request('settings2_paystack_currency_code'),
                'settings2_paystack_status' => (request('settings2_paystack_status') == 'on') ? 'enabled' : 'disabled',
            ]);

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
                'paystack',
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
        ];
        return $page;
    }

}
