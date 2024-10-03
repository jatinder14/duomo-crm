<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [destroy] process for the canned
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Canned;
use Illuminate\Contracts\Support\Responsable;

class CannedMessagesResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * remove the canned from the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $html = view('pages/canned/components/misc/canned', compact('canned_responses'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#canned-reponses-container',
            'action' => 'replace',
            'value' => $html,
        ];

        $jsondata['skip_dom_reset'] = true;

        $jsondata['skip_dom_tinymce'] = true;

        $jsondata['skip_checkboxes_reset'] = true;

        //response
        return response()->json($jsondata);

    }

}
