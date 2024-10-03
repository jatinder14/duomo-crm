<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for holidays
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Holidays;
use Closure;
use Log;

class Create {

    /**
     * This middleware does the following:
     *   1. checks users permissions to [create] a new resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.holidays')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //frontend
        $this->fronteEnd();

        //does user have permission to create a new holiday
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_holidays >= 2) {
                //permission granted
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][holidays][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //defaults
        config([
            'visibility.attendance_modal_client_fields' => true,
            'visibility.attendance_show_attendance_option' => true,
        ]);
    }
}
