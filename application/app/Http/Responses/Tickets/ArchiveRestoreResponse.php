<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the tickets
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tickets;
use Illuminate\Contracts\Support\Responsable;

class ArchiveRestoreResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for team members
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

        //update initiated on a list page
        if (request('ref') == 'list') {
            foreach ($tickets as $ticket) {
                //hide anywany
                if ($action == 'archive' && auth()->user()->pref_filter_show_archived_tickets == 'no') {
                    $jsondata['dom_visibility'][] = [
                        'selector' => '#ticket_' . $ticket->ticket_id,
                        'action' => 'hide',
                    ];
                } else {
                    //replace row
                    $html = view('pages/tickets/components/table/ajax-inc', compact('ticket'))->render();
                    $jsondata['dom_html'][] = [
                        'selector' => '#ticket_' . $ticket->ticket_id,
                        'action' => 'replace-with',
                        'value' => $html,
                    ];
                }
            }
            //notice
            $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));
        }

        //editing from main page
        if (request('ref') == 'page') {
            //session
            request()->session()->flash('success-notification', __('lang.request_has_been_completed'));
            //redirect to ticket page
            $jsondata['redirect_url'] = url("tickets/" . $tickets->first()->ticket_id);
        }

        //response
        return response()->json($jsondata);
    }

}
