<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for product attendances
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Attendances;
use Closure;
use Log;

class Edit {

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

        //attendance id
        $id = $request->route('attendance');

        //does the attendance exist
        if ($id == '' || !$attendance = \App\Models\Attendance::Where('attendance_id', $id)->first()) {
            Log::error("attendance could not be found", ['process' => '[permissions][attendances][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'attendance id' => $id ?? '']);
            abort(404);
        }

        //permission: does user have permission edit attendances
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_attendances >= 2) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][attendances][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}
