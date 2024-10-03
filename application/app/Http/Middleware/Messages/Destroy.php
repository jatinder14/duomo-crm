<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for template
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Messages;
use Closure;
use Log;

class Destroy {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] messages
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //message id
        $message_unique_id = $request->route('message');

        //does the message exist
        if (!$message = \App\Models\Message::Where('message_unique_id', $message_unique_id)->first()) {
            Log::error("message could not be found", ['process' => '[messages][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'message id' => $message_id ?? '']);
            abort(404);
        }

        //validate owner
        

        //permission: does user have permission edit messages
        if (auth()->user()->is_team) {
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][messages][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}