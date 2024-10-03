<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Canned;
use Closure;
use Log;

class Create {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] canned
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //frontend
        $this->fronteEnd();

        //permission: does user have permission create canned
        if (auth()->user()->is_team) {
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[canned][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default: show client and project options
        config(['visibility.canned_modal_client_project_fields' => true]);

        /**
         * [embedded request]
         * the add new canned request is being made from an embedded view (project page)
         *      - validate the project
         *      - do no display 'project' & 'client' options in the modal form
         *  */
        if (request()->filled('cannedresource_id') && request()->filled('cannedresource_type')) {

            //project resource
            if (request('cannedresource_type') == 'project') {
                if ($project = \App\Models\Project::Where('project_id', request('cannedresource_id'))->first()) {

                    //hide some form fields
                    config([
                        'visibility.canned_modal_client_project_fields' => false,
                    ]);

                    //add some form fields data
                    request()->merge([
                        'canned_projectid' => $project->project_id,
                        'canned_clientid' => $project->project_clientid,
                    ]);

                } else {
                    //error not found
                    Log::error("the resource project could not be found", ['process' => '[canned][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    abort(404);
                }
            }

            //client resource
            if (request('cannedresource_type') == 'client') {
                if ($client = \App\Models\Client::Where('client_id', request('cannedresource_id'))->first()) {

                    //hide some form fields
                    config([
                        'visibility.canned_modal_client_project_fields' => false,
                        'visibility.canned_modal_clients_projects' => true,
                    ]);

                    //required form data
                    request()->merge([
                        'canned_clientid' => $client->client_id,
                    ]);

                    //clients projects list
                    $projects = \App\Models\Project::Where('project_clientid', request('cannedresource_id'))->get();
                    config(
                        [
                            'settings.clients_projects' => $projects,
                        ]
                    );
                } else {
                    //error not found
                    Log::error("the resource project could not be found", ['process' => '[canned][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    abort(404);
                }
            }
        }
    }
}
