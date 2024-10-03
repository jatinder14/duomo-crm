<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the attendances
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Attendances;
use Illuminate\Contracts\Support\Responsable;

class CreateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for attendances
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {
        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }
        //render the form
        $html = view('pages/attendances/components/modals/record-attendance', compact('statuses'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal attendance
        $jsondata['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        //ajax response
        return response()->json($jsondata);
    }

}
