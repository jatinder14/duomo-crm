<?php

namespace App\Http\Middleware\Templates\Proposals;
use Closure;
use Log;

class Show {

    /**
     * This middleware does the following
     *   1. validates that the proposal exists
     *   2. checks users permissions to [view] the proposal
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //proposal id
        $proposal_id = $request->route('proposal');

        //frontend
        $this->fronteEnd();

        //does the proposal exist
        if ($proposal_id == '' || !$proposal = \App\Models\Proposal::Where('proposal_id', $proposal_id)->first()) {
            abort(404);
        }

        //team: does user have permission edit proposals
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_proposals >= 1) {
                return $next($request);
            }
        }

        //client: does user have permission edit proposals
        if (auth()->user()->is_client) {
            if ($proposal->proposal_clientid == auth()->user()->clientid) {
                return $next($request);
            }
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[proposals][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default: show client and project options
        config(['visibility.proposal_modal_client_fields' => true]);

        //merge data
        request()->merge([
            'resource_query' => 'ref=page',
        ]);
    }

}
