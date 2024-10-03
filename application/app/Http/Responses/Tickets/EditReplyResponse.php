<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the tickets
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tickets;
use Illuminate\Contracts\Support\Responsable;

class EditReplyResponse implements Responsable {

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

        //render the text editor
        $html = view("pages/ticket/components/misc/edit-reply", compact('reply'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#ticket_edit_reply_container_' . $reply->ticketreply_id,
            'action' => 'replace',
            'value' => $html);

        //hide the reply text
        $jsondata['dom_visibility'][] = [
            'selector' => '#ticket_reply_text_' . $reply->ticketreply_id,
            'action' => 'hide',
        ];

        //show the text editor
        $jsondata['dom_visibility'][] = [
            'selector' => '#ticket_edit_reply_container_' . $reply->ticketreply_id,
            'action' => 'show',
        ];

        //ajax response
        return response()->json($jsondata);
    }

}
