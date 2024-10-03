<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the contracts
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Contracts;
use Illuminate\Contracts\Support\Responsable;

class SignatureResponse implements Responsable {

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

        //render the page
        $html = view('pages/documents/elements/signatures-contracts', compact('document'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#doc-signatures-container',
            'action' => 'replace',
            'value' => $html,
        ];

        //skip dom rests
        $jsondata['skip_dom_reset'] = true;
        $jsondata['skip_dom_reset'] = true;

        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal', 'action' => 'close-modal',
        ];

        //response
        return response()->json($jsondata);
    }

}
