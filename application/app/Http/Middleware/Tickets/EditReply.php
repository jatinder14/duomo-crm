<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for tickets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Tickets;
use Closure;
use Log;

class EditReply {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] tickets
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //ticket id
        $ticketreply_id = $request->route('ticket');

        //does the ticket exist
        if (!$reply = \App\Models\TicketReply::Where('ticketreply_id', $ticketreply_id)->first()) {
            Log::error("ticket reply could not be found", ['process' => '[permissions][tickets][edit-reply]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'ticket id' => $ticket_id ?? '']);
            abort(409, __('lang.ticket_not_found'));
        }

        //permission: does user have permission edit tickets
        if (permissionEditTicketReply($reply)) {
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][tickets][edit-reply]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }
}
