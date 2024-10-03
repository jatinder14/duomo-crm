<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Canned;
use Closure;
use Log;

class Edit {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] canned
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
            Log::error("canned could not be found", ['process' => '[canned][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'canned id' => $canned_id ?? '']);
            abort(404);
        }

        //permission: does user have permission edit canned
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_canned == 'no' && $canned_response->canned_visibility == 'public') {
                abort(403);
            }
            return $next($request);
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][canned][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //some settings
        config([
            'settings.canned' => true,
            'settings.bar' => true,
        ]);
    }
}
