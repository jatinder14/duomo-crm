<?php

namespace App\Http\Middleware\Canned;
use Closure;
use Log;

class Show {

    /**
     * This middleware does the following
     *   1. validates that the canned exists
     *   2. checks users permissions to [view] the canned
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //canned id
        $canned_id = $request->route('canned');

        //frontend
        $this->fronteEnd();

        //does the canned exist
        if ($canned_id == '' || !$canned = \App\Models\Canned::Where('canned_id', $canned_id)->first()) {
            abort(404);
        }

        //team: does user have permission edit canned
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_canned >= 1) {
                return $next($request);
            }
        }

        //client: does user have permission edit canned
        if (auth()->user()->is_client) {
            if ($canned->canned_clientid == auth()->user()->clientid) {
                return $next($request);
            }
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[canned][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default: show client and project options
        config(['visibility.canned_modal_client_fields' => true]);

        //merge data
        request()->merge([
            'resource_query' => 'ref=page',
        ]);
    }

}
