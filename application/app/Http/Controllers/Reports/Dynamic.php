<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\DynamicResponse;

class Dynamic extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

    }

    /**
     * Update a resource
     * @return \Illuminate\Http\Response
     */
    public function showDynamic() {

        $page = $this->pageSettings();

        // Get the current URL
        $current_url = url()->current();
        $new_url = str_replace('/reports/', '/report/', $current_url);

        //set dynamic url for use in template
        switch (request()->segment(2)) {
        case 'invoices':
        case 'estimates':
        case 'projects':
        case 'tasks':
        case 'leads':
        case 'contracts':
        case 'timesheets':
        case 'financial':
        case 'proposals':
        case 'clients':
            $page['dynamic_url'] = $new_url;
            break;
        default:
            $page['dynamic_url'] = url('report/start');
            break;
        }

        //reponse payload
        $payload = [
            'page' => $page,
        ];

        //process reponse
        return new DynamicResponse($payload);

    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.reports'),
                __('lang.summary'),
            ],
            'page' => 'reports',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_reports' => 'active',
        ];

        //return
        return $page;
    }
}