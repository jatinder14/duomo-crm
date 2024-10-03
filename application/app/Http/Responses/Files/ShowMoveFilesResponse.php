<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the files
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Files;
use Illuminate\Contracts\Support\Responsable;

class ShowMoveFilesResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for files
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //page
        $html = view('pages/files/components/modals/move-file', compact('folders'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        $jsondata['skip_dom_reset'] = true;
        $jsondata['skip_checkboxes_reset'] = true;

        // POSTRUN FUNCTIONS------
        $jsondata['postrun_functions'][] = [
            'value' => 'NXMoveFiles',
        ];

        //render
        return response()->json($jsondata);

    }

}
