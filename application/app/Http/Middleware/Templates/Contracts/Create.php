<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Templates\Contracts;
use Closure;
use Log;

class Create {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] contracts
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //frontend
        $this->fronteEnd();

        //permission: does user have permission create contracts
        if (auth()->user()->role->role_contracts >= 2) {      
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[contracts][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default: show client and project options
        config(['visibility.contract_modal_client_project_fields' => true]);

        /**
         * [embedded request]
         * the add new contract request is being made from an embedded view (project page)
         *      - validate the project
         *      - do no display 'project' & 'client' options in the modal form
         *  */
        if (request()->filled('contractresource_id') && request()->filled('contractresource_type')) {

            //project resource
            if (request('contractresource_type') == 'project') {
                if ($project = \App\Models\Project::Where('project_id', request('contractresource_id'))->first()) {

                    //hide some form fields
                    config([
                        'visibility.contract_modal_client_project_fields' => false,
                    ]);

                    //add some form fields data
                    request()->merge([
                        'contract_projectid' => $project->project_id,
                        'contract_clientid' => $project->project_clientid,
                    ]);

                } else {
                    //error not found
                    Log::error("the resource project could not be found", ['process' => '[contracts][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    abort(404);
                }
            }

            //client resource
            if (request('contractresource_type') == 'client') {
                if ($client = \App\Models\Client::Where('client_id', request('contractresource_id'))->first()) {

                    //hide some form fields
                    config([
                        'visibility.contract_modal_client_project_fields' => false,
                        'visibility.contract_modal_clients_projects' => true,
                    ]);

                    //required form data
                    request()->merge([
                        'contract_clientid' => $client->client_id,
                    ]);

                    //clients projects list
                    $projects = \App\Models\Project::Where('project_clientid', request('contractresource_id'))->get();
                    config(
                        [
                            'settings.clients_projects' => $projects,
                        ]
                    );
                } else {
                    //error not found
                    Log::error("the resource project could not be found", ['process' => '[contracts][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    abort(404);
                }
            }
        }
    }
}
