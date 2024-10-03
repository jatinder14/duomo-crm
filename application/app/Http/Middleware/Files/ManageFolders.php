<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for files
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Files;
use App\Permissions\ProjectPermissions;
use Closure;
use Log;

class ManageFolders {

    /**
     * The project permisson repository instance.
     */
    protected $projectpermissons;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(ProjectPermissions $projectpermissons) {

        $this->projectpermissons = $projectpermissons;
    }

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] files
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //various frontend and visibility settings
        $this->fronteEnd();

        //[project] - does user have 'project level' viewing permissions
        if ($this->projectpermissons->check('manage-folders', request('fileresource_id'))) {
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][files][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    private function fronteEnd() {

        /**
         * shorten resource type and id (for easy appending in blade templates)
         * [usage]
         *   replace the usual url('foo') with urlResource('foo'), in blade templated
         * */
        if (request('fileresource_type') != '' || is_numeric(request('fileresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&fileresource_type=' . request('fileresource_type') . '&fileresource_id=' . request('fileresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

    }

}
