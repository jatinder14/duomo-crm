<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [assign user] precheck processes for leads
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Leads;
use App\Models\Lead;
use App\Permissions\LeadPermissions;
use Closure;
use Log;

class BulkAssign {

    /**
     * The permisson repository instance.
     */
    protected $leadpermissions;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(LeadPermissions $leadpermissions) {

        //lead permissions repo
        $this->leadpermissions = $leadpermissions;

    }

    /**
     * Check user permissions to edit a lead
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.leads')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //permission team
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_assign_leads == 'yes') {
                return $next($request);
            }
        }

        //no items were passed with this request
        Log::error("permission denied", ['process' => '[permissions][leads][assign]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'lead id' => $lead_id ?? '']);
        abort(403);
    }
}
