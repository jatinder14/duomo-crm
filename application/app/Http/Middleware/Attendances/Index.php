<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for tickets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Attendances;

use App\Models\Attendance;
use Closure;
use Log;

class Index {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] attendances
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
        // if (request()->filled('attendanceresource_type') && request()->filled('attendanceresource_id')) {
        //     //project attendances
        //     if (request('attendanceresource_type') == 'project') {
        //         request()->merge([
        //             'filter_timer_projectid' => request('attendanceresource_id'),
        //         ]);
        //     }
        //     //client attendances
        //     if (request('attendanceresource_type') == 'client') {
        //         request()->merge([
        //             'filter_timer_clientid' => request('attendanceresource_id'),
        //         ]);
        //     }
        // }

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_attendances >= 1) {
                //limit to own attendances, if applicable
                if (auth()->user()->role->role_attendances_scope == 'own' || request()->segment(2) == 'my') {
                    request()->merge([
                        'filter_timer_creatorid' => auth()->id(),
                    ]);
                }
                return $next($request);
            }
        }

        //client - allow to view only embedded. Also as per project settings
        if (auth()->user()->is_client) {
            if (request()->ajax() && request()->filled('attendanceresource_id')) {
                if ($project = \App\Models\Project::Where('project_id', request('attendanceresource_id'))->first()) {
                    if ($project->clientperm_attendances_view == 'yes') {
                        //goup by tasks
                        request()->merge([
                            'filter_grouping' => 'task',
                        ]);
                        return $next($request);
                    }
                }
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][attendances][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('attendance/edit/etc') with urlResource('attendance/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('attendanceresource_type') != '' || is_numeric(request('attendanceresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&attendanceresource_type=' . request('attendanceresource_type') . '&attendanceresource_id=' . request('attendanceresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //default show some table columns
        config([
            'visibility.attendances_col_related' => true,
            'visibility.attendances_col_action' => true,
            'visibility.filter_panel_resource' => true,
        ]);

        //permissions -viewing
        if (auth()->user()->role->role_attendances >= 1) {
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
                    'visibility.attendances_col_client' => false,
                    'visibility.attendances_col_action' => false,
                    'visibility.attendances_grouped_by_users' => true,
                ]);
            }

            //disable whe grouping attendances
            if (request('filter_grouping') == 'task') {
                config([
                    //visibility
                    'visibility.attendances_grouped_by_users' => true,
                ]);
            }
        }

        if (auth()->user()->role->role_attendances == 1) {
            config([
                'visibility.attendances_col_action' => false,
            ]);

        }

        //permissions -adding
        if (auth()->user()->role->role_attendances >= 2) {
            config([
                //visibility
                'visibility.action_buttons_edit' => true,
                'visibility.attendances_col_checkboxes' => true,
            ]);
        }

        //permissions -deleting
        if (auth()->user()->role->role_attendances >= 3) {
            config([
                //visibility
                'visibility.action_buttons_delete' => true,
            ]);
            //disable whe grouping attendances
            if (request('filter_grouping') == 'task' || request('filter_grouping') == 'user') {
                config([
                    //visibility
                    'visibility.attendances_disable_actions' => true,
                    'visibility.action_buttons_delete' => false,
                ]);
            }
        }

        //columns visibility
        if (request('attendanceresource_type') != '') {
            config([
                //visibility
                'visibility.attendances_col_related' => false,
                'visibility.filter_panel_resource' => false,
            ]);
        }

        //importing and exporting
        config([
            'visibility.list_page_actions_importing' => (auth()->user()->role->role_content_import == 'yes') ? true : false,
            'visibility.list_page_actions_exporting' => (auth()->user()->role->role_content_export == 'yes') ? true : false,
        ]);
    }
}
