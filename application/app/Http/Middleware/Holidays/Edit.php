<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for product holidays
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Holidays;
use Closure;
use Log;

class Edit {

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

        //holiday id
        $id = $request->route('holiday');

        //does the holiday exist
        if ($id == '' || !$holiday = \App\Models\Holiday::Where('holiday_id', $id)->first()) {
            Log::error("holiday could not be found", ['process' => '[permissions][holidays][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'holiday id' => $id ?? '']);
            abort(404);
        }

        //permission: does user have permission edit holidays
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_holidays >= 2) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][holidays][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}
