<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Fooos;
use Closure;
use Log;

class Create {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] fooos
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //frontend
        $this->fronteEnd();

        //permission: does user have permission create fooos
        if (auth()->user()->role->role_fooos >= 2) {      
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[fooos][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default: show client and project options
        config(['visibility.fooo_modal_client_project_fields' => true]);

        /**
         * [embedded request]
         * the add new fooo request is being made from an embedded view (project page)
         *      - validate the project
         *      - do no display 'project' & 'client' options in the modal form
         *  */
        if (request()->filled('foooresource_id') && request()->filled('foooresource_type')) {

            //project resource
            if (request('foooresource_type') == 'project') {
                if ($project = \App\Models\Project::Where('project_id', request('foooresource_id'))->first()) {

                    //hide some form fields
                    config([
                        'visibility.fooo_modal_client_project_fields' => false,
                    ]);

                    //add some form fields data
                    request()->merge([
                        'fooo_projectid' => $project->project_id,
                        'fooo_clientid' => $project->project_clientid,
                    ]);

                } else {
                    //error not found
                    Log::error("the resource project could not be found", ['process' => '[fooos][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    abort(404);
                }
            }

            //client resource
            if (request('foooresource_type') == 'client') {
                if ($client = \App\Models\Client::Where('client_id', request('foooresource_id'))->first()) {

                    //hide some form fields
                    config([
                        'visibility.fooo_modal_client_project_fields' => false,
                        'visibility.fooo_modal_clients_projects' => true,
                    ]);

                    //required form data
                    request()->merge([
                        'fooo_clientid' => $client->client_id,
                    ]);

                    //clients projects list
                    $projects = \App\Models\Project::Where('project_clientid', request('foooresource_id'))->get();
                    config(
                        [
                            'settings.clients_projects' => $projects,
                        ]
                    );
                } else {
                    //error not found
                    Log::error("the resource project could not be found", ['process' => '[fooos][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    abort(404);
                }
            }
        }
    }
}
