<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Templates\Proposals;
use Closure;
use Log;

class Create {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] proposals
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //frontend
        $this->fronteEnd();

        //permission: does user have permission create proposals
        if (auth()->user()->role->role_proposals >= 2) {      
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[proposals][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default: show client and project options
        config(['visibility.proposal_modal_client_project_fields' => true]);

        /**
         * [embedded request]
         * the add new proposal request is being made from an embedded view (project page)
         *      - validate the project
         *      - do no display 'project' & 'client' options in the modal form
         *  */
        if (request()->filled('proposalresource_id') && request()->filled('proposalresource_type')) {

            //project resource
            if (request('proposalresource_type') == 'project') {
                if ($project = \App\Models\Project::Where('project_id', request('proposalresource_id'))->first()) {

                    //hide some form fields
                    config([
                        'visibility.proposal_modal_client_project_fields' => false,
                    ]);

                    //add some form fields data
                    request()->merge([
                        'proposal_projectid' => $project->project_id,
                        'proposal_clientid' => $project->project_clientid,
                    ]);

                } else {
                    //error not found
                    Log::error("the resource project could not be found", ['process' => '[proposals][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    abort(404);
                }
            }

            //client resource
            if (request('proposalresource_type') == 'client') {
                if ($client = \App\Models\Client::Where('client_id', request('proposalresource_id'))->first()) {

                    //hide some form fields
                    config([
                        'visibility.proposal_modal_client_project_fields' => false,
                        'visibility.proposal_modal_clients_projects' => true,
                    ]);

                    //required form data
                    request()->merge([
                        'proposal_clientid' => $client->client_id,
                    ]);

                    //clients projects list
                    $projects = \App\Models\Project::Where('project_clientid', request('proposalresource_id'))->get();
                    config(
                        [
                            'settings.clients_projects' => $projects,
                        ]
                    );
                } else {
                    //error not found
                    Log::error("the resource project could not be found", ['process' => '[proposals][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    abort(404);
                }
            }
        }
    }
}
