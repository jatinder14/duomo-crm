<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for attendances
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Attendances;
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
        if (!config('visibility.modules.attendances')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //frontend
        $this->fronteEnd();

        //does user have permission to create a new attendance
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_attendances >= 2) {
                //permission granted
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][attendances][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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

        //assigning a attendance and setting its manager
        if (auth()->user()->role->role_assign_attendances == 'yes') {
            config(['visibility.attendance_modal_assign_fields' => true]);
        } else {
            //assign only to current user and also make manager
            request()->merge([
                'assigned' => [auth()->id()],
                'manager' => auth()->id(),
            ]);
        }

        //set attendance permissions
        if (auth()->user()->role->role_set_attendance_permissions == 'yes') {
            config(['visibility.attendance_modal_attendance_permissions' => true]);
        }

        //client resource
        if (request('attendanceresource_type') == 'client') {
            if ($client = \App\Models\Client::Where('client_id', request('attendanceresource_id'))->first()) {

                //hide some form fields
                config(['visibility.attendance_modal_client_fields' => false]);

                //required form data
                request()->merge([
                    'attendance_clientid' => request('attendanceresource_id'),
                ]);

            } else {
                //error not found
                Log::error("the resource attendance could not be found", ['process' => '[permissions][attendances][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                abort(404);
            }
        }

    }
}
