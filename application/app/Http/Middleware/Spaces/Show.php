<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Spaces;

use App\Models\Space;
use Closure;
use Log;

class Show {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] spaces
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //space/project unique id
        $project_uniqueid = $request->route('space');

        if (!$project = \App\Models\Project::Where('project_uniqueid', $project_uniqueid)->Where('project_type', 'space')->first()) {
            abort(404);
        }

        //set the project is as a route param
        request()->merge([
            'space_id' => $project->project_id,
            'filter_project_type' => 'space',
        ]);

        return $next($request);

        //permission denied
        Log::error("permission denied", ['process' => '[spaces][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('space/edit/etc') with urlResource('space/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('spaceresource_type') != '' || is_numeric(request('spaceresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&spaceresource_type=' . request('spaceresource_type') . '&spaceresource_id=' . request('spaceresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }
    }
}
