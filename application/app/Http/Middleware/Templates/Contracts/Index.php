<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Templates\Contracts;

use App\Models\Contract;
use Closure;
use Log;

class Index {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] contracts
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //various frontend and visibility settings
        $this->fronteEnd();

        //embedded request: limit by supplied resource data
        if (request()->filled('contractresource_type') && request()->filled('contractresource_id')) {
            //project contracts
            if (request('contractresource_type') == 'project') {
                request()->merge([
                    'filter_contract_projectid' => request('contractresource_id'),
                ]);
            }
            //client contracts
            if (request('contractresource_type') == 'client') {
                request()->merge([
                    'filter_contract_clientid' => request('contractresource_id'),
                ]);
            }
        }

        //client user permission
        if (auth()->user()->is_client) {
            //exclude draft contracts
            request()->merge([
                'filter_contract_exclude_status' => 'draft',
            ]);
            return $next($request);
        }

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_contracts >= 1) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[contracts][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('contract/edit/etc') with urlResource('contract/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('contractresource_type') != '' || is_numeric(request('contractresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&contractresource_type=' . request('contractresource_type') . '&contractresource_id=' . request('contractresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //default show some table columns
        config([
            'visibility.contracts_col_client' => true,
            'visibility.contracts_col_project' => true,
            'visibility.contracts_col_payments' => true,
            'visibility.filter_panel_client_project' => true,
        ]);

        //permissions -viewing
        if (auth()->user()->role->role_contracts >= 1) {
            if (auth()->user()->is_team) {
                config([
                    //visibility
                    'visibility.list_page_actions_filter_button' => true,
                    'visibility.list_page_actions_search' => true,
                    'visibility.stats_toggle_button' => true,
                ]);
            }
            if (auth()->user()->is_client) {
                config([
                    //visibility
                    'visibility.list_page_actions_search' => true,
                    'visibility.contracts_col_client' => false,
                ]);
            }
        }

        //permissions -adding
        if (auth()->user()->role->role_contracts >= 2) {
            config([
                //visibility
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.contracts_col_checkboxes' => true,
            ]);
        }

        //permissions -deleting
        if (auth()->user()->role->role_contracts >= 3) {
            config([
                //visibility
                'visibility.action_buttons_delete' => true,
            ]);
        }

        //columns visibility
        if (request('contractresource_type') == 'project') {
            config([
                //visibility
                'visibility.contracts_col_client' => false,
                'visibility.contracts_col_project' => false,
                'visibility.filter_panel_client_project' => false,
            ]);
        }

        //columns visibility
        if (request('contractresource_type') == 'client') {
            config([
                //visibility
                'visibility.contracts_col_client' => false,
                'visibility.contracts_col_payments' => false,
                'visibility.filter_panel_client_project' => false,
            ]);
        }
    }
}
