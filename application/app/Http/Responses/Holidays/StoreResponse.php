<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the holidays
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Holidays;
use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for holidays
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //prepend content on top of list or show full table
        if ($count == 1) {
            $html = view('pages/holidays/components/table/table', compact('holidays'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#holidays-table-wrapper',
                'action' => 'replace',
                'value' => $html);
        } else {
            //prepend content on top of list
            $html = view('pages/holidays/components/table/ajax', compact('holidays'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#holidays-td-container',
                'action' => 'prepend',
                'value' => $html);
        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
