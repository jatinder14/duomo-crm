<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [attach] process for the estimates
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Contracts;
use Illuminate\Contracts\Support\Responsable;

class AttachProjectResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //full payload array
        $payload = $this->payload;

        //render the form
        if ($type == 'form') {
            $html = view('pages/contracts/components/actions/attach-project', compact('payload'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#actionsModalBody',
                'action' => 'replace',
                'value' => $html);

            //show modal footer
            $jsondata['dom_visibility'][] = array('selector' => '#actionsModalFooter', 'action' => 'show');
        }

        //attach/detach completed
        if ($type == 'update') {

            //refresh the list
            if (request('ref') == 'list') {
                $html = view('pages/contracts/components/table/ajax', compact('contracts'))->render();
                $jsondata['dom_html'][] = array(
                    'selector' => "#contract_$bill_estimateid",
                    'action' => 'replace-with',
                    'value' => $html);

                //close modals
                $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
                $jsondata['dom_visibility'][] = array('selector' => '#actionsModal', 'action' => 'close-modal');
            }

            //update initiated on the estimate page
            if (request('ref') == 'page') {
                //redirect
                $jsondata['redirect_url'] = url('');
            }

            //notice
            $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        }

        //ajax response
        return response()->json($jsondata);

    }

}
