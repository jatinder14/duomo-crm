<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Templates\Contracts;
use Closure;
use Log;

class Edit {

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
        $template_id = $request->route('contract');

        //does the contract exist
        if ($template_id == '' || !$template = \App\Models\ContractTemplate::Where('contract_template_id', $template_id)->first()) {
            Log::error("template could not be found", ['process' => '[template][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'template id' => $template_id ?? '']);
            abort(403);
        }

        //permission: does user have permission edit contracts
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_templates_contracts >= 2) {
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][template][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}
