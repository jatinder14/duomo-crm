<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Templates\Proposals;

use App\Models\Proposal;
use Closure;
use Log;

class Index {

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

        //various frontend and visibility settings
        $this->fronteEnd();

        //embedded request: limit by supplied resource data
        if (request()->filled('proposalresource_type') && request()->filled('proposalresource_id')) {
            //project proposals
            if (request('proposalresource_type') == 'project') {
                request()->merge([
                    'filter_proposal_projectid' => request('proposalresource_id'),
                ]);
            }
            //client proposals
            if (request('proposalresource_type') == 'client') {
                request()->merge([
                    'filter_proposal_clientid' => request('proposalresource_id'),
                ]);
            }
        }

        //client user permission
        if (auth()->user()->is_client) {
            //exclude draft proposals
            request()->merge([
                'filter_proposal_exclude_status' => 'draft',
            ]);
            return $next($request);
        }

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_proposals >= 1) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[proposals][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('proposal/edit/etc') with urlResource('proposal/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('proposalresource_type') != '' || is_numeric(request('proposalresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&proposalresource_type=' . request('proposalresource_type') . '&proposalresource_id=' . request('proposalresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //default show some table columns
        config([
            'visibility.proposals_col_client' => true,
            'visibility.proposals_col_project' => true,
            'visibility.proposals_col_payments' => true,
            'visibility.filter_panel_client_project' => true,
        ]);

        //permissions -viewing
        if (auth()->user()->role->role_proposals >= 1) {
            if (auth()->user()->is_team) {
                config([
                    //visibility
                    'visibility.list_page_actions_filter_button' => true,
                    'visibility.list_page_actions_search' => true,
                    'visibility.stats_toggle_button' => true,
                ]);
            }
            if (auth()->user()->is_client) {
                config([
                    //visibility
                    'visibility.list_page_actions_search' => true,
                    'visibility.proposals_col_client' => false,
                ]);
            }
        }

        //permissions -adding
        if (auth()->user()->role->role_proposals >= 2) {
            config([
                //visibility
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.proposals_col_checkboxes' => true,
            ]);
        }

        //permissions -deleting
        if (auth()->user()->role->role_proposals >= 3) {
            config([
                //visibility
                'visibility.action_buttons_delete' => true,
            ]);
        }

        //columns visibility
        if (request('proposalresource_type') == 'project') {
            config([
                //visibility
                'visibility.proposals_col_client' => false,
                'visibility.proposals_col_project' => false,
                'visibility.filter_panel_client_project' => false,
            ]);
        }

        //columns visibility
        if (request('proposalresource_type') == 'client') {
            config([
                //visibility
                'visibility.proposals_col_client' => false,
                'visibility.proposals_col_payments' => false,
                'visibility.filter_panel_client_project' => false,
            ]);
        }
    }
}
