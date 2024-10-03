<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the contracts
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Contracts;
use Illuminate\Contracts\Support\Responsable;

class PublishResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for contracts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //change status
        $jsondata['dom_visibility'][] = [
            'selector' => '#doc_status_ribbon_draft',
            'action' => 'hide',
        ];
        $jsondata['dom_visibility'][] = [
            'selector' => '#doc_status_ribbon_awaiting_signatures',
            'action' => 'show',
        ];

        //hide publish button
        $jsondata['dom_visibility'][] = [
            'selector' => '#document-action-publish',
            'action' => 'hide',
        ];

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
