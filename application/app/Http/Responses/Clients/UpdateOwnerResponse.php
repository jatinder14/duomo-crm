<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the clients
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Clients;
use Illuminate\Contracts\Support\Responsable;

class UpdateOwnerResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //show edit form
        if ($response == 'show-form') {

            $html = view('pages/clients/components/modals/change-owner', compact('page', 'users'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#commonModalBody',
                'action' => 'replace',
                'value' => $html,
            ];

            return response()->json($jsondata);
        }

        //update owner
        if ($response == 'update-owner') {

            $jsondata['redirect_url'] = url("clients/$client_id/contacts");

            request()->session()->flash('success-notification', __('lang.request_has_been_completed'));

            return response()->json($jsondata);
        }

    }

}
