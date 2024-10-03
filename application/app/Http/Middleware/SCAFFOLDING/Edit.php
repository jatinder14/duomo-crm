<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Fooos;
use Closure;
use Log;

class Edit {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] fooos
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
            Log::error("fooo could not be found", ['process' => '[fooos][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'fooo id' => $fooo_id ?? '']);
            abort(404);
        }

        //permission: does user have permission edit fooos
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_fooos >= 2) {
                //global permissions
                if (auth()->user()->role->role_fooos_scope == 'global') {
                    
                    return $next($request);
                }
                //own permissions
                if (auth()->user()->role->role_fooos_scope == 'own') {
                    if ($fooo->fooo_creatorid == auth()->id()) { 
                        return $next($request);
                    }
                }
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
        Log::error("permission denied", ['process' => '[permissions][fooos][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //some settings
        config([
            'settings.fooo' => true,
            'settings.bar' => true,
        ]);
    }
}
