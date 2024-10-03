<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Messages;
use Closure;
use Log;

class Create {

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


        //team users
        if (auth()->user()->is_team) {
            return $next($request);
        }


        //permission denied
        Log::error("permission denied", ['process' => '[fooos][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}