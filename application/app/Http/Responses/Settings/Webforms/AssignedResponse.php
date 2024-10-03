<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [index] process for the webforms settings
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Settings\Webforms;
use Illuminate\Contracts\Support\Responsable;

class AssignedResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for webforms
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //initial
        $jsondata = [];

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //show the form
        if ($action == 'show') {
            $html = view('pages/settings/sections/webforms/modals/assigned', compact('assigned', 'webform'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#actionsModalBody',
                'action' => 'replace',
                'value' => $html,
            ];

        }

        //show refreshed row
        if ($action == 'update') {
            $html = view('pages/settings/sections/webforms/table/ajax', compact('page', 'webforms'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#webform_' . $id,
                'action' => 'replace-with',
                'value' => $html,
            ];
            //notice error
            $jsondata['notification'] = [
                'type' => 'success',
                'value' => __('lang.request_has_been_completed'),
            ];
            //close modal
            $jsondata['dom_visibility'][] = [
                'selector' => '#actionsModal', 'action' => 'close-modal',
            ];
        }

        //ajax response
        return response()->json($jsondata);
    }
}
