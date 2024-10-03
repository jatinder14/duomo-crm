<?php

namespace App\Http\Middleware\Reports;
use Closure;
use Log;

class Show {

    /**
     * This middleware does the following
     *   1. validates that the fooo exists
     *   2. checks users permissions to [view] the fooo
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {


        //team: does user have permission edit fooos
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_reports == 'yes') {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[fooos][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

}
