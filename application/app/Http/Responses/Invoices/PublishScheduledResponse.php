<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [publish] process for the invoices
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Invoices;
use Illuminate\Contracts\Support\Responsable;

class PublishScheduledResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for invoices
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        request()->session()->flash('success-notification', __('lang.request_has_been_completed'));

        $jsondata['redirect_url'] = url("/invoices/$id");

        //response
        return response()->json($jsondata);
    }
}
