<?php

namespace App\Http\Middleware\Search;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\LeadRepository;
use Closure;
use Log;

class Index {

    //vars
    protected $projectrepo;
    protected $taskrepo;
    protected $leadrepo;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(ProjectRepository $projectrepo, TaskRepository $taskrepo, LeadRepository $leadrepo) {
        $this->projectrepo = $projectrepo;
        $this->taskrepo = $taskrepo;
        $this->leadrepo = $leadrepo;
    }

    /**
     * This middleware does the following
     *   1. validates that the fooo exists
     *   2. checks users permissions to [view] the fooo
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //frontend
        $this->fronteEnd();

        //make sure we have request type
        if (!request()->filled('search_type')) {
            request()->merge([
                'search_type' => 'all',
            ]);
        }

        //my projects (assigned and managed)
        $my_projects = $this->projectrepo->usersAssignedAndManageProjects(auth()->id(), 'list');
        request()->merge([
            'my_projects' => $my_projects,
        ]);

        //my tasks (assigned and managed)
        $my_tasks = $this->taskrepo->usersAssignedAndManageTasks(auth()->id(), 'list');
        request()->merge([
            'my_tasks' => $my_tasks,
        ]);

        //my leads (assigned and managed)
        $my_leads = $this->leadrepo->usersAssignedLeads(auth()->id(), 'list');
        request()->merge([
            'my_leads' => $my_leads,
        ]);

        //client: does user have permission edit fooos
        if (auth()->user()->is_team) {
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[search][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //clients
        if (auth()->user()->role->role_clients >= 1) {
            config(['search.clients' => true]);
        }

        //contacts
        if (auth()->user()->role->role_contacts >= 1) {
            config(['search.contacts' => true]);
        }

        //projects
        if (config('modules.projects')) {
            if (auth()->user()->role->role_projects >= 1) {
                config(['search.projects' => true]);
            }
        }

        //tasks
        if (config('modules.tasks')) {
            if (auth()->user()->role->role_tasks >= 1) {
                config(['search.tasks' => true]);
            }
        }
        //leads
        if (config('modules.leads')) {
            if (auth()->user()->role->role_leads >= 1) {
                config(['search.leads' => true]);
            }
        }
        //contracts
        if (config('modules.contracts')) {
            if (auth()->user()->role->role_contracts >= 1) {
                config(['search.contracts' => true]);
            }
        }
        //proposals
        if (config('modules.proposals')) {
            if (auth()->user()->role->role_proposals >= 1) {
                config(['search.proposals' => true]);
            }
        }
        //tickets
        if (config('modules.tickets')) {
            if (auth()->user()->role->role_tickets >= 1) {
                config(['search.tickets' => true]);
            }
        }

        //files
        if (config('modules.projects') || config('modules.clients')) {
            config(['search.files' => true]);
        }

        //attachments
        if (config('modules.leads') || config('modules.tasks')) {
            config(['search.attachments' => true]);
        }

    }

}
