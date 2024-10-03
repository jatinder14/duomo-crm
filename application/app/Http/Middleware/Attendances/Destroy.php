<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [destro] precheck processes for tickets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Attendances;
use Closure;
use Log;

class Destroy {

    /**
     * This 'bulk actions' middleware does the following
     *   1. If the request was for a sinle item
     *         - single item actions must have a query string '?id=123'
     *         - this id will be merged into the expected 'ids' request array (just as if it was a bulk request)
     *   2. loop through all the 'ids' that are in the post request
     *
     * HTML for the checkbox is expected to be in this format:
     *   <input type="checkbox" name="ids[{{ $attendance->id }}]"
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //for a single item request - merge into an $ids[x] array and set as if checkox is selected (on)
        if (is_numeric($request->route('attendance'))) {
            $ids[$request->route('attendance')] = 'on';
            request()->merge([
                'ids' => $ids,
            ]);
        }

        //loop through each attendance and check permissions
        if (is_array(request('ids'))) {

            //validate each item in the list exists
            foreach (request('ids') as $id => $value) {
                //only checked items
                if ($value == 'on') {
                    //validate
                    if (!$attendance = \App\Models\Attendance::Where('attendance_id', $id)->first()) {
                        abort(409, __('lang.one_of_the_selected_items_nolonger_exists'));
                    }
                }
                //permission on each one
                if (auth()->user()->is_team) {
                    if (auth()->user()->role->role_attendances >= 3) {
                        //own scope - permissions
                        if (auth()->user()->role->role_attendances_scope == 'own') {
                            if ($attendance->attendancer_creatorid != auth()->id()) {
                                abort(403, __('lang.permission_denied_for_this_item') . " - #$id");
                            }
                        }
                    } else {
                        abort(403);
                    }
                }
            }
            //client - no permissions
            if (auth()->user()->is_client) {
                abort(403);
            }
        } else {
            //no items were passed with this request
            Log::error("no items were sent with this request", ['process' => '[permissions][attendances][change-category]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'attendance id' => $id ?? '']);
            abort(409);
        }

        //all is on - passed
        return $next($request);
    }
}
