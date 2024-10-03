<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the estimates
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Estimates;
use Illuminate\Contracts\Support\Responsable;

class UpdateAutomationResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for estimates
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        if (request('ref') == 'list') {
            $html = view('pages/estimates/components/table/ajax', compact('estimates', 'page', 'tags'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#estimate_" . $estimate->bill_estimateid,
                'action' => 'replace-with',
                'value' => $html);
        }

        if ($estimate->estimate_automation_status == 'enabled') {
            $jsondata['dom_visibility'][] = [
                'selector' => '#estimate-automation-icon',
                'action' => 'show',
            ];
        } else {
            $jsondata['dom_visibility'][] = [
                'selector' => '#estimate-automation-icon',
                'action' => 'hide',
            ];
        }

        //close modals
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //ajax response
        return response()->json($jsondata);
    }

}
