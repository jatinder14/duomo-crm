<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Fooos;

use App\Models\Fooo;
use Closure;
use Log;

class Index {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] fooos
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
        if (request()->filled('foooresource_type') && request()->filled('foooresource_id')) {
            //project fooos
            if (request('foooresource_type') == 'project') {
                request()->merge([
                    'filter_fooo_projectid' => request('foooresource_id'),
                ]);
            }
            //client fooos
            if (request('foooresource_type') == 'client') {
                request()->merge([
                    'filter_fooo_clientid' => request('foooresource_id'),
                ]);
            }
        }

        //client user permission
        if (auth()->user()->is_client) {
            //exclude draft fooos
            request()->merge([
                'filter_fooo_exclude_status' => 'draft',
            ]);
            return $next($request);
        }

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_fooos >= 1) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[fooos][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('fooo/edit/etc') with urlResource('fooo/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('foooresource_type') != '' || is_numeric(request('foooresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&foooresource_type=' . request('foooresource_type') . '&foooresource_id=' . request('foooresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //default show some table columns
        config([
            'visibility.fooos_col_client' => true,
            'visibility.fooos_col_project' => true,
            'visibility.fooos_col_payments' => true,
            'visibility.filter_panel_client_project' => true,
        ]);

        //permissions -viewing
        if (auth()->user()->role->role_fooos >= 1) {
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
                    'visibility.fooos_col_client' => false,
                ]);
            }
        }

        //permissions -adding
        if (auth()->user()->role->role_fooos >= 2) {
            config([
                //visibility
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.fooos_col_checkboxes' => true,
            ]);
        }

        //permissions -deleting
        if (auth()->user()->role->role_fooos >= 3) {
            config([
                //visibility
                'visibility.action_buttons_delete' => true,
            ]);
        }

        //columns visibility
        if (request('foooresource_type') == 'project') {
            config([
                //visibility
                'visibility.fooos_col_client' => false,
                'visibility.fooos_col_project' => false,
                'visibility.filter_panel_client_project' => false,
            ]);
        }

        //columns visibility
        if (request('foooresource_type') == 'client') {
            config([
                //visibility
                'visibility.fooos_col_client' => false,
                'visibility.fooos_col_payments' => false,
                'visibility.filter_panel_client_project' => false,
            ]);
        }
    }
}
