<?php

namespace App\Http\Middleware\Fooos;
use Closure;
use Log;

class Show {

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

        //fooo id
        $fooo_id = $request->route('fooo');

        //frontend
        $this->fronteEnd();

        //does the fooo exist
        if ($fooo_id == '' || !$fooo = \App\Models\Fooo::Where('fooo_id', $fooo_id)->first()) {
            abort(404);
        }

        //team: does user have permission edit fooos
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_fooos >= 1) {
                return $next($request);
            }
        }

        //client: does user have permission edit fooos
        if (auth()->user()->is_client) {
            if ($fooo->fooo_clientid == auth()->user()->clientid) {
                return $next($request);
            }
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[fooos][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default: show client and project options
        config(['visibility.fooo_modal_client_fields' => true]);

        //merge data
        request()->merge([
            'resource_query' => 'ref=page',
        ]);
    }

}
