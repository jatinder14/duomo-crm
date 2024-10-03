<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the holidays
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Holidays;
use Illuminate\Contracts\Support\Responsable;

class CreateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for holidays
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //render the form
        $html = view('pages/holidays/components/modals/record-holiday')->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal holiday
        $jsondata['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        //ajax response
        return response()->json($jsondata);
    }

}
