<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the canned
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Canned;
use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for canned members
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
            $html = view('pages/canned/components/table/table', compact('canned_responses'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#canned-table-container',
                'action' => 'replace',
                'value' => $html);
        } else {
            //prepend content on top of list
            $html = view('pages/canned/components/table/ajax', compact('canned_responses'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#canned-td-container',
                'action' => 'prepend',
                'value' => $html);
        }

        //update counter
        $jsondata['dom_html'][] = [
            'selector' => '#canned_category_count_'.request('filter_categoryid'),
            'action' => 'replace',
            'value' => $count,
        ];

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
