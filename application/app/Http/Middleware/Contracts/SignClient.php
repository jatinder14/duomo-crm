<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for product contracts
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Contracts;

use App\Models\Contract;
use Closure;
use Log;

class SignClient {

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

        //contract id
        $doc_unique_id = $request->route('contract');

        //does the contract exist
        if (!$contract = \App\Models\Contract::Where('doc_unique_id', $doc_unique_id)->first()) {
            Log::error("contract could not be found", ['process' => '[contracts][sign-client]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'contract unique id' => $doc_unique_id ?? '']);
            abort(404);
        }

        //frontend
        $this->fronteEnd($contract);

        //client user permission
        if (auth()->check() && auth()->user()->is_tream) {
            abort(409, __('lang.incorrect_user_for_action'));
        }

        //for logged in clients
        if(auth()->check() && (auth()->user()->clientid != $contract->doc_client_id)){
            abort(403);
        }

        //client
        return $next($request);

        //permission denied
        Log::error("permission denied", ['process' => '[contracts][sign-client]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd($contract) {

        //prefill clients name if they are logged in
        if (auth()->check()) {
            config([
                'signining.first_name' => auth()->user()->first_name,
                'signining.last_name' => auth()->user()->last_name,
            ]);
        }

    }

}
