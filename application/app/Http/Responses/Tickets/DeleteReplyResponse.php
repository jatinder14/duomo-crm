<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the tickets
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tickets;
use Illuminate\Contracts\Support\Responsable;

class DeleteReplyResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for tickets
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //remove the reply
        $jsondata['dom_visibility'][] = [
            'selector' => "#ticket_reply_$id",
            'action' => 'hide-remove',
        ];

        //ajax response
        return response()->json($jsondata);
    }

}
