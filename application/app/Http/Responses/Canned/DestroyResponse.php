<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [destroy] process for the canned
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Canned;
use Illuminate\Contracts\Support\Responsable;

class DestroyResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * remove the canned from the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //hide and remove all deleted rows
        $jsondata['dom_visibility'][] = array(
            'selector' => '#canned_' . $id,
            'action' => 'slideup-slow-remove',
        );

        //update counter
        $jsondata['dom_html'][] = [
            'selector' => '#canned_category_count_' . $category_id,
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
