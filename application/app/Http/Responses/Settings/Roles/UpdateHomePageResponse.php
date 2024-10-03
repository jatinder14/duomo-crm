<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the roles settings
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Settings\Roles;
use Illuminate\Contracts\Support\Responsable;

class UpdateHomePageResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for roles
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal', 'action' => 'close-modal',
        ];

        //notice error
        $jsondata['notification'] = [
            'type' => 'success',
            'value' => __('lang.request_has_been_completed'),
        ];

        //ajax response
        return response()->json($jsondata);
    }

}
