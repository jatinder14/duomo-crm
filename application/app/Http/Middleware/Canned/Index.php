<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Canned;

use App\Models\Canned;
use Closure;
use Log;

class Index {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] canned
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //various frontend and visibility settings
        $this->fronteEnd();

        //default category
        if (!request()->filled('filter_categoryid')) {
            request()->merge([
                'filter_categoryid' => -1,
            ]);
        }

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_canned == 'no') {
                request()->merge([
                    'show_type' => 'own',
                ]);
            }
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[canned][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('canned/edit/etc') with urlResource('canned/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('cannedresource_type') != '' || is_numeric(request('cannedresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&cannedresource_type=' . request('cannedresource_type') . '&cannedresource_id=' . request('cannedresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //default show some table columns
        config([
            'visibility.list_page_actions_add_button' => true,
        ]);

        //permissions -adding
        if (auth()->user()->role->role_canned == 'yes') {
            config([
                //visibility
                'visibility.action_buttons_manage' => true,
            ]);
        }

    }
}
