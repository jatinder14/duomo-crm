<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [import] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Import\Attendances;
use Closure;
use Log;

class Create {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] clients
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //frontend
        $this->fronteEnd();

        //permission: does user have permission create attendances
        if (auth()->user()->role->role_attendances >= 2) {
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[attendances][import]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

    }
}
