<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for foo templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Team;

use Closure;
use Log;

class Create {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] foos
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->is_admin || auth()->user()->role->role_team == 3) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[foo templates][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}
