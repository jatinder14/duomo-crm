<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [convert] process for the leads
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Leads;
use Illuminate\Contracts\Support\Responsable;

class ConvertDetailsResponse implements Responsable {

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

        //update address details in the form
        $jsondata['dom_val'][] = [
            'selector' => '#convert_lead_street',
            'value' => $lead->lead_street,
        ];
        $jsondata['dom_val'][] = [
            'selector' => '#convert_lead_city',
            'value' => $lead->lead_city,
        ];
        $jsondata['dom_val'][] = [
            'selector' => '#convert_lead_state',
            'value' => $lead->lead_state,
        ];
        $jsondata['dom_val'][] = [
            'selector' => '#convert_lead_zip',
            'value' => $lead->lead_zip,
        ];
        $jsondata['dom_val'][] = [
            'selector' => '#convert_lead_street',
            'value' => $lead->lead_street,
        ];
        $jsondata['dom_action'][] = [
            'selector' => '#convert_lead_country',
            'action' => 'trigger-select-change',
            'value' => $lead->lead_country,
        ];
        $jsondata['dom_action'][] = [
            'selector' => '#convert_lead_website',
            'action' => 'trigger-select-change',
            'value' => $lead->lead_website,
        ];
        $jsondata['dom_classes'][] = [
            'selector' => '#leadConvertToCustomer',
            'action' => 'remove',
            'value' => 'overlay',
        ];

        //ajax response
        return response()->json($jsondata);
    }

}
