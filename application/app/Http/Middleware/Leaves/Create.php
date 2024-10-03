<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for leaves
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Leaves;
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
        if (!config('visibility.modules.leaves')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //frontend
        $this->fronteEnd();

        //does user have permission to create a new leave
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_leaves >= 2) {
                //permission granted
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][leaves][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //defaults
        config([
            'visibility.leave_modal_client_fields' => true,
            'visibility.leave_show_leave_option' => true,
        ]);

    }
}
