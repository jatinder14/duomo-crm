<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [clone] process for the projects
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Contracts;
use Illuminate\Contracts\Support\Responsable;

class CreateCloneResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for projects
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        /** -------------------------------------------------------------------------
         * Show the propsal form
         * -------------------------------------------------------------------------*/
        if ($response == 'create') {
            //render the form
            $html = view('pages/contracts/components/modals/clone', compact('contract', 'categories'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#commonModalBody',
                'action' => 'replace',
                'value' => $html);

            //show modal projectter
            $jsondata['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

            // POSTRUN FUNCTIONS------
            $jsondata['postrun_functions'][] = [
                'value' => 'NXContractClone',
            ];

            //ajax response
            return response()->json($jsondata);
        }

        /** -------------------------------------------------------------------------
         * store response
         * -------------------------------------------------------------------------*/
        if ($response == 'store') {

            $jsondata['redirect_url'] = url('contracts/' . $contract->doc_id);
            request()->session()->flash('success-notification', __('lang.request_has_been_completed'));

            //ajax response
            return response()->json($jsondata);
        }
    }
}