<?php

namespace App\Http\Middleware\Templates\Contracts;
use Closure;
use Log;

class Show {

    /**
     * This middleware does the following
     *   1. validates that the contract exists
     *   2. checks users permissions to [view] the contract
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //contract id
        $contract_id = $request->route('contract');

        //frontend
        $this->fronteEnd();

        //does the contract exist
        if ($contract_id == '' || !$contract = \App\Models\Contract::Where('contract_id', $contract_id)->first()) {
            abort(404);
        }

        //team: does user have permission edit contracts
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_contracts >= 1) {
                return $next($request);
            }
        }

        //client: does user have permission edit contracts
        if (auth()->user()->is_client) {
            if ($contract->contract_clientid == auth()->user()->clientid) {
                return $next($request);
            }
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[contracts][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default: show client and project options
        config(['visibility.contract_modal_client_fields' => true]);

        //merge data
        request()->merge([
            'resource_query' => 'ref=page',
        ]);
    }

}
