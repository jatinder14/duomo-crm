<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [assign user] precheck processes for projects
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Projects;
use App\Models\Project;
use App\Permissions\ProjectPermissions;
use Closure;
use Log;

class BulkAssign {

    /**
     * The permisson repository instance.
     */
    protected $projectpermissions;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(ProjectPermissions $projectpermissions) {

        //project permissions repo
        $this->projectpermissions = $projectpermissions;

    }

    /**
     * Check user permissions to edit a project
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.projects')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //permission team
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_assign_projects == 'yes') {
                return $next($request);
            }
        }

        //no items were passed with this request
        Log::error("permission denied", ['process' => '[permissions][projects][assign]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project id' => $project_id ?? '']);
        abort(403);
    }
}
