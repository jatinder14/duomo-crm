<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for product leaves
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Leaves;
use Closure;
use Log;

class Edit {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] leaves
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //leave id
        $id = $request->route('leave');

        //does the leave exist
        if ($id == '' || !$leave = \App\Models\Leave::Where('leave_id', $id)->first()) {
            Log::error("leave could not be found", ['process' => '[permissions][leaves][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'leave id' => $id ?? '']);
            abort(404);
        }

        //permission: does user have permission edit leaves
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_leaves >= 2) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][leaves][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}
