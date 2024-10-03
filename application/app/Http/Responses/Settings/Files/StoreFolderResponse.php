<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Settings\Files;
use Illuminate\Contracts\Support\Responsable;

class StoreFolderResponse implements Responsable {

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


        $html = view('pages/settings/sections/files/table/table', compact('folders'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => "#file-folders-table",
            'action' => 'replace-with',
            'value' => $html);

        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal', 'action' => 'close-modal',
        ];

        //ajax response
        return response()->json($jsondata);

    }

}