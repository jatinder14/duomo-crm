<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the tickets
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tickets;
use Illuminate\Contracts\Support\Responsable;

class ChangeStatusResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view ticket
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $jsondata = [];

        //show form
        if ($action == 'show') {
            $html = view('pages/tickets/components/modals/change-status', compact('statuses'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#actionsModalBody',
                'action' => 'replace',
                'value' => $html,
            ];
        }

        //update statuses
        if ($action == 'update') {
            foreach ($tickets as $ticket) {
                //replace row
                $html = view('pages/tickets/components/table/ajax-inc', compact('ticket'))->render();
                $jsondata['dom_html'][] = [
                    'selector' => '#ticket_' . $ticket->ticket_id,
                    'action' => 'replace-with',
                    'value' => $html,
                ];
            }

            //close modal
            $jsondata['dom_visibility'][] = [
                'selector' => '#actionsModal', 'action' => 'close-modal',
            ];

            //notice
            $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));
        }

        //ajax response
        return response()->json($jsondata);

    }

}
