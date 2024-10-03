<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Items;
use Closure;
use Log;

class TasksView {

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

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_items >= 1) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][items tasks][view]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}