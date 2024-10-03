<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for tickets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Holidays;

use App\Models\Holiday;
use Closure;
use Log;

class Index {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] holidays
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
        // if (request()->filled('holidayresource_type') && request()->filled('holidayresource_id')) {
        //     //project holidays
        //     if (request('holidayresource_type') == 'project') {
        //         request()->merge([
        //             'filter_timer_projectid' => request('holidayresource_id'),
        //         ]);
        //     }
        //     //client holidays
        //     if (request('holidayresource_type') == 'client') {
        //         request()->merge([
        //             'filter_timer_clientid' => request('holidayresource_id'),
        //         ]);
        //     }
        // }

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_holidays >= 1) {
                //limit to own holidays, if applicable
                if (auth()->user()->role->role_holidays_scope == 'own' || request()->segment(2) == 'my') {
                    request()->merge([
                        'filter_timer_creatorid' => auth()->id(),
                    ]);
                }
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][holidays][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('holiday/edit/etc') with urlResource('holiday/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('holidayresource_type') != '' || is_numeric(request('holidayresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&holidayresource_type=' . request('holidayresource_type') . '&holidayresource_id=' . request('holidayresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //default show some table columns
        config([
            'visibility.holidays_col_related' => true,
            'visibility.holidays_col_action' => true,
            'visibility.filter_panel_resource' => true,
        ]);

        //permissions -viewing
        if (auth()->user()->role->role_holidays >= 1) {
            if (auth()->user()->is_team) {
                config([
                    //visibility
                    'visibility.list_page_actions_filter_button' => true,
                    'visibility.list_page_actions_add_button' => true,
                    'visibility.list_page_actions_search' => true,
                ]);
            }
            if (auth()->user()->is_client) {
                config([
                    //visibility
                    'visibility.list_page_actions_search' => true,
                    'visibility.holidays_col_client' => false,
                    'visibility.holidays_col_action' => false,
                    'visibility.holidays_grouped_by_users' => true,
                ]);
            }

            //disable whe grouping holidays
            if (request('filter_grouping') == 'task') {
                config([
                    //visibility
                    'visibility.holidays_grouped_by_users' => true,
                ]);
            }
        }

        if (auth()->user()->role->role_holidays == 1) {
            config([
                'visibility.holidays_col_action' => false,
            ]);

        }

        //permissions -adding
        if (auth()->user()->role->role_holidays >= 2) {
            config([
                //visibility
                'visibility.list_page_actions_add_button' => false,
                'visibility.holidays_col_action' => false,
            ]);
        }

        //permissions -deleting
        if (auth()->user()->role->role_holidays >= 3) {
            config([
                //visibility
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.holidays_col_checkboxes' => true,
                'visibility.action_buttons_delete' => true,
                'visibility.holidays_col_action' => true,
            ]);
            //disable whe grouping holidays
            if (request('filter_grouping') == 'task' || request('filter_grouping') == 'user') {
                config([
                    //visibility
                    'visibility.holidays_disable_actions' => true,
                    'visibility.action_buttons_delete' => false,
                ]);
            }
        }

        //columns visibility
        if (request('holidayresource_type') != '') {
            config([
                //visibility
                'visibility.holidays_col_related' => false,
                'visibility.filter_panel_resource' => false,
            ]);
        }

        //importing and exporting
        config([
            'visibility.list_page_actions_exporting' => (auth()->user()->role->role_content_export == 'yes') ? true : false,
        ]);
    }
}
