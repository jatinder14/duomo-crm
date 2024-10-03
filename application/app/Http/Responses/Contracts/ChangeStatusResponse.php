<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [create] process for the contracts
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Contracts;
use Illuminate\Contracts\Support\Responsable;

class ChangeStatusResponse implements Responsable {

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

        //from list page
        if (request('ref') == 'list') {

            $html = view('pages/contracts/components/table/ajax', compact('contracts'))->render();
            $jsondata['dom_html'][] = [
                'selector' => "#contract_$id",
                'action' => 'replace-with',
                'value' => $html,
            ];

            //refresh stats
            if (isset($stats)) {
                $html = view('misc/list-pages-stats-content', compact('stats'))->render();
                $jsondata['dom_html'][] = [
                    'selector' => '#list-pages-stats-widget',
                    'action' => 'replace',
                    'value' => $html,
                ];
            }

            //notice error
            $jsondata['notification'] = [
                'type' => 'success',
                'value' => __('lang.request_has_been_completed'),
            ];

        } else {
            //redirect
            $jsondata['redirect_url'] = url("/contracts/$id");

            //success
            request()->session()->flash('success-notification', __('lang.request_has_been_completed'));
        }

        //ajax response
        return response()->json($jsondata);

    }
}
