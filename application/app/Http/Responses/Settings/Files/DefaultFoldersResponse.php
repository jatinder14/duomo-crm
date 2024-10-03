<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [categories] process for the milestones settings
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Settings\Files;
use Illuminate\Contracts\Support\Responsable;

class DefaultFoldersResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for sources
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $html = view('pages/settings/sections/files/table/page', compact('page', 'folders'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => "#settings-wrapper",
            'action' => 'replace',
            'value' => $html);

        //left menu activate
        if (request('url_type') == 'dynamic') {
            $jsondata['dom_attributes'][] = [
                'selector' => '#settings-menu-files',
                'attr' => 'aria-expanded',
                'value' => false,
            ];
            $jsondata['dom_action'][] = [
                'selector' => '#settings-menu-files',
                'action' => 'trigger',
                'value' => 'click',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => '#settings-menu-files-defaultfolders',
                'action' => 'add',
                'value' => 'active',
            ];
        }
        
        //ajax response
        return response()->json($jsondata);
    }
}
