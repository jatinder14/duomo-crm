<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Settings\Files;
use Illuminate\Contracts\Support\Responsable;

class EditFolderResponse implements Responsable {

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

        //page
        $html = view('pages/settings/sections/files/modals/add-edit-inc', compact('folder'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        //postrun
        $jsondata['postrun_functions'][] = [
            'value' => 'NXSettingsFileFolder',
        ];

        //render
        return response()->json($jsondata);

    }

}